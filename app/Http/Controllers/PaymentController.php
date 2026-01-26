<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Afficher la liste des paiements avec filtres
     */
    public function index(Request $request)
    {
        $query = Payment::with(['transaction.customer', 'transaction.room.type', 'user', 'cancelledByUser', 'createdBy'])
            ->orderBy('created_at', 'DESC');

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('method')) {
            $query->where('payment_method', $request->method);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhere('reference', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('transaction.customer', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('transaction.room', function($q) use ($search) {
                      $q->where('number', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->paginate(20);
        
        // Statistiques
        $stats = [
            'total' => Payment::count(),
            'total_amount' => Payment::where('status', Payment::STATUS_COMPLETED)->sum('amount'),
            'today' => Payment::whereDate('created_at', today())->count(),
            'today_amount' => Payment::whereDate('created_at', today())->where('status', Payment::STATUS_COMPLETED)->sum('amount'),
            'cash' => Payment::where('payment_method', Payment::METHOD_CASH)->where('status', Payment::STATUS_COMPLETED)->count(),
            'card' => Payment::where('payment_method', Payment::METHOD_CARD)->where('status', Payment::STATUS_COMPLETED)->count(),
            'transfer' => Payment::where('payment_method', Payment::METHOD_TRANSFER)->where('status', Payment::STATUS_COMPLETED)->count(),
            'mobile_money' => Payment::where('payment_method', Payment::METHOD_MOBILE_MONEY)->where('status', Payment::STATUS_COMPLETED)->count(),
            'fedapay' => Payment::where('payment_method', Payment::METHOD_FEDAPAY)->where('status', Payment::STATUS_COMPLETED)->count(),
            'check' => Payment::where('payment_method', Payment::METHOD_CHECK)->where('status', Payment::STATUS_COMPLETED)->count(),
        ];

        return view('payment.index', [
            'payments' => $payments,
            'stats' => $stats,
            'paymentMethods' => Payment::getPaymentMethods(),
            'statuses' => [
                Payment::STATUS_PENDING => 'En attente',
                Payment::STATUS_COMPLETED => 'Complété',
                Payment::STATUS_CANCELLED => 'Annulé',
                Payment::STATUS_EXPIRED => 'Expiré',
                Payment::STATUS_FAILED => 'Échoué',
                Payment::STATUS_REFUNDED => 'Remboursé',
            ],
        ]);
    }

    /**
     * Afficher le formulaire de création de paiement
     */
    public function create(Transaction $transaction)
    {
        Log::info("Formulaire paiement transaction #{$transaction->id}", [
            'total_price' => $transaction->total_price,
            'total_payment' => $transaction->total_payment,
            'remaining' => $transaction->getRemainingPayment(),
        ]);

        $remaining = $transaction->getRemainingPayment();
        
        if ($remaining <= 0) {
            return redirect()->route('transaction.show', $transaction)
                ->with('info', 'Cette transaction est déjà entièrement payée.');
        }

        if (in_array($transaction->status, ['cancelled', 'no_show'])) {
            return redirect()->route('transaction.show', $transaction)
                ->with('error', 'Impossible d\'effectuer un paiement sur une transaction annulée ou no show.');
        }

        return view('transaction.payment.create', [
            'transaction' => $transaction,
            'paymentMethods' => Payment::getPaymentMethods(),
            'debug_info' => [
                'remaining_calculated' => $remaining,
                'remaining_formatted' => number_format($remaining, 0, ',', ' ') . ' CFA',
                'payment_rate' => $transaction->getPaymentRate(),
                'total_payments' => $transaction->payments()->where('status', Payment::STATUS_COMPLETED)->count(),
            ],
        ]);
    }

    /**
     * Enregistrer un nouveau paiement - VERSION CORRIGÉE
     */
    public function store(Transaction $transaction, Request $request)
    {
        Log::critical('=== DÉBUT ENREGISTREMENT PAIEMENT ===', [
            'transaction_id' => $transaction->id,
            'user_id' => auth()->id(),
            'remaining_before' => $transaction->getRemainingPayment(),
            'input_data' => $request->all(),
        ]);
        
        // Règles de validation de base
        $validationRules = [
            'amount' => [
                'required',
                'numeric',
                'min:100',
                'max:' . $transaction->getRemainingPayment()
            ],
            'payment_method' => 'required|in:' . implode(',', [
                Payment::METHOD_CASH,
                Payment::METHOD_CARD,
                Payment::METHOD_TRANSFER,
                Payment::METHOD_MOBILE_MONEY,
                Payment::METHOD_FEDAPAY,
                Payment::METHOD_CHECK,
            ]),
            'description' => 'nullable|string|max:500',
        ];

        // Validation
        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            Log::error('Validation échouée', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->all()
            ]);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validation échouée'
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Veuillez corriger les erreurs ci-dessous');
        }

        $validated = $validator->validated();
        
        DB::beginTransaction();
        
        try {
            Log::info('Création du paiement...', [
                'amount' => $validated['amount'],
                'method' => $validated['payment_method'],
                'transaction_id' => $transaction->id,
                'user_id' => auth()->id()
            ]);
            
            // Générer une référence
            $reference = $this->generatePaymentReference($validated['payment_method'], $transaction);
            
            // Préparer les données du paiement
            $paymentData = [
                'user_id' => $transaction->customer_id ?? null, // ID du client
                'created_by' => auth()->id(), // ID de l'utilisateur qui crée le paiement
                'transaction_id' => $transaction->id,
                'amount' => (float) $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'status' => Payment::STATUS_COMPLETED, // Par défaut, le paiement est complet
                'reference' => $reference,
                'description' => $validated['description'] ?? null,
            ];
            
            // Créer le paiement
            $payment = Payment::create($paymentData);
            
            Log::info('Paiement créé avec succès', [
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'reference' => $payment->reference,
                'payment_method' => $payment->payment_method
            ]);
            
            // Mettre à jour le statut de paiement de la transaction
            $transaction->updatePaymentStatus();
            $transaction->refresh();
            
            Log::info('Transaction mise à jour', [
                'transaction_id' => $transaction->id,
                'new_total_payment' => $transaction->total_payment,
                'remaining' => $transaction->getRemainingPayment(),
                'is_fully_paid' => $transaction->isFullyPaid(),
            ]);
            
            // Journalisation d'activité
            if (class_exists('App\Models\Activity')) {
                activity()
                    ->performedOn($payment)
                    ->causedBy(auth()->user())
                    ->withProperties([
                        'amount' => $payment->amount,
                        'method' => $payment->payment_method,
                        'transaction_id' => $transaction->id
                    ])
                    ->log('Paiement enregistré');
            }
            
            DB::commit();
            
            Log::critical('=== PAIEMENT RÉUSSI ===', [
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'transaction_remaining' => $transaction->getRemainingPayment(),
                'transaction_total_payment' => $transaction->total_payment,
            ]);
            
            // Préparer la réponse
            $isFullyPaid = $transaction->isFullyPaid();
            $remaining = $transaction->getRemainingPayment();
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Paiement enregistré avec succès',
                    'data' => [
                        'payment' => [
                            'id' => $payment->id,
                            'amount' => $payment->amount,
                            'reference' => $payment->reference,
                            'method' => $payment->payment_method,
                            'method_label' => $payment->payment_method_label,
                        ],
                        'transaction' => [
                            'id' => $transaction->id,
                            'total_price' => (float) $transaction->total_price,
                            'total_payment' => (float) $transaction->total_payment,
                            'remaining' => $remaining,
                            'payment_rate' => $transaction->getPaymentRate(),
                            'is_fully_paid' => $isFullyPaid,
                            'status' => $transaction->status,
                        ],
                        'calculations' => [
                            'amount' => (float) $validated['amount'],
                            'newRemaining' => $remaining,
                            'paymentRate' => $transaction->getPaymentRate(),
                        ]
                    ]
                ], 200);
            }
            
            // Redirection pour requête normale
            $successMessage = $this->generateSuccessMessage($payment, $isFullyPaid, $remaining);
            
            return redirect()->route('transaction.show', $transaction)
                ->with('success', $successMessage);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('=== ERREUR PAIEMENT ===', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'transaction_id' => $transaction->id,
                'user_id' => auth()->id(),
                'input_data' => $request->all()
            ]);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'enregistrement du paiement',
                    'error' => env('APP_DEBUG') ? $e->getMessage() : 'Une erreur est survenue'
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Générer une référence de paiement
     */
    private function generatePaymentReference($method, $transaction)
    {
        $prefixes = [
            Payment::METHOD_CASH => 'CASH',
            Payment::METHOD_CARD => 'CARD',
            Payment::METHOD_TRANSFER => 'VIR',
            Payment::METHOD_MOBILE_MONEY => 'MOMO',
            Payment::METHOD_FEDAPAY => 'FDP',
            Payment::METHOD_CHECK => 'CHQ',
        ];

        $prefix = $prefixes[$method] ?? 'PAY';
        $timestamp = time();
        $random = rand(1000, 9999);
        
        return "{$prefix}-{$transaction->id}-{$timestamp}-{$random}";
    }

    /**
     * Générer le message de succès
     */
    private function generateSuccessMessage(Payment $payment, bool $isFullyPaid, $remaining)
    {
        $methodLabels = Payment::getPaymentMethods();
        $methodLabel = $methodLabels[$payment->payment_method]['label'] ?? $payment->payment_method;
        
        $message = 'Paiement de ' . number_format($payment->amount, 0, ',', ' ') . ' CFA par ' . $methodLabel . ' enregistré avec succès !';
        
        if ($isFullyPaid) {
            $message .= ' Transaction entièrement payée !';
        } else {
            $message .= ' Solde restant : ' . number_format($remaining, 0, ',', ' ') . ' CFA';
        }
        
        return $message;
    }

    /**
     * Afficher les détails d'un paiement
     */
    public function show(Payment $payment)
    {
        $payment->load(['transaction.customer', 'transaction.room.type', 'user', 'createdBy', 'cancelledByUser']);
        
        if ($payment->transaction) {
            $payment->transaction->updatePaymentStatus();
            $payment->transaction->refresh();
        }
        
        return view('payment.show', [
            'payment' => $payment,
            'paymentMethods' => Payment::getPaymentMethods(),
            'debug_info' => $payment->transaction ? [
                'transaction_totals' => [
                    'price' => $payment->transaction->total_price,
                    'payment' => $payment->transaction->total_payment,
                    'remaining' => $payment->transaction->getRemainingPayment(),
                ],
                'payment_count' => $payment->transaction->payments()->count(),
                'completed_payment_count' => $payment->transaction->payments()->where('status', Payment::STATUS_COMPLETED)->count(),
            ] : null,
        ]);
    }

    /**
     * Annuler un paiement
     */
    public function cancel(Request $request, Payment $payment)
    {
        $request->validate([
            'cancel_reason' => 'required|string|max:500'
        ]);

        Log::info("Annulation paiement #{$payment->id}", [
            'payment_status' => $payment->status,
            'transaction_id' => $payment->transaction_id,
            'user_id' => auth()->id(),
        ]);

        if (!$payment->canBeCancelled()) {
            return redirect()->back()->with('error', 'Ce paiement ne peut pas être annulé.');
        }

        DB::beginTransaction();
        
        try {
            $transaction = $payment->transaction;
            
            // Annuler le paiement
            $payment->update([
                'status' => Payment::STATUS_CANCELLED,
                'cancelled_at' => now(),
                'cancelled_by' => auth()->id(),
                'cancel_reason' => $request->cancel_reason,
            ]);

            // Recalculer le total de la transaction
            if ($transaction) {
                $transaction->updatePaymentStatus();
                $transaction->refresh();
            }

            // Journalisation
            if (class_exists('App\Models\Activity')) {
                activity()
                    ->performedOn($payment)
                    ->causedBy(auth()->user())
                    ->withProperties([
                        'cancel_reason' => $request->cancel_reason,
                    ])
                    ->log('Paiement annulé');
            }

            DB::commit();
            
            Log::info("Paiement #{$payment->id} annulé avec succès");

            return redirect()->route('payments.index')
                ->with('success', 'Paiement annulé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erreur annulation paiement: ' . $e->getMessage(), [
                'payment_id' => $payment->id,
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()
                ->with('error', 'Erreur lors de l\'annulation: ' . $e->getMessage());
        }
    }

    /**
     * Rembourser un paiement
     */
    public function refund(Request $request, Payment $payment)
    {
        $request->validate([
            'cancel_reason' => 'required|string|max:500'
        ]);

        if (!$payment->canBeRefunded()) {
            return redirect()->back()->with('error', 'Ce paiement ne peut pas être remboursé.');
        }

        try {
            DB::beginTransaction();

            // Créer un paiement de remboursement
            $refundPayment = Payment::create([
                'user_id' => $payment->user_id,
                'created_by' => auth()->id(),
                'transaction_id' => $payment->transaction_id,
                'amount' => -$payment->amount,
                'payment_method' => Payment::METHOD_REFUND,
                'status' => Payment::STATUS_COMPLETED,
                'reference' => 'REFUND-' . ($payment->reference ?? 'PAY-' . $payment->id),
                'description' => 'Remboursement: ' . $request->cancel_reason,
            ]);

            // Marquer le paiement original comme remboursé
            $payment->markAsRefunded(auth()->id(), $request->cancel_reason);

            // Recalculer le total de la transaction
            if ($payment->transaction) {
                $payment->transaction->updatePaymentStatus();
                $payment->transaction->refresh();
            }

            // Journalisation
            if (class_exists('App\Models\Activity')) {
                activity()
                    ->performedOn($payment)
                    ->causedBy(auth()->user())
                    ->withProperties([
                        'cancel_reason' => $request->cancel_reason,
                        'refund_payment_id' => $refundPayment->id,
                    ])
                    ->log('Paiement remboursé');
            }

            DB::commit();

            return redirect()->route('payments.index')
                ->with('success', 'Paiement remboursé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erreur remboursement paiement: ' . $e->getMessage(), [
                'payment_id' => $payment->id,
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()
                ->with('error', 'Erreur lors du remboursement: ' . $e->getMessage());
        }
    }

    /**
     * Générer une facture/reçu pour un paiement
     */
    public function invoice(Payment $payment)
    {
        try {
            $payment->load([
                'transaction' => function($query) {
                    $query->with([
                        'customer',
                        'room.type',
                        'payments' => function($q) {
                            $q->where('status', Payment::STATUS_COMPLETED)->orderBy('created_at', 'asc');
                        }
                    ]);
                },
                'user',
                'createdBy',
                'cancelledByUser'
            ]);
            
            if (!$payment->transaction) {
                return redirect()->back()->with('error', 'Transaction non trouvée pour ce paiement.');
            }
            
            $payment->transaction->updatePaymentStatus();
            $payment->transaction->refresh();
            
            $totalPrice = $payment->transaction->getTotalPrice();
            $totalPayment = Payment::getTotalForTransaction($payment->transaction_id);
            $remaining = max(0, $totalPrice - $totalPayment);
            
            Log::info("Génération facture paiement #{$payment->id}", [
                'total_price' => $totalPrice,
                'total_payment' => $totalPayment,
                'remaining' => $remaining,
            ]);
            
            return view('payment.invoice', [
                'payment' => $payment,
                'totalPrice' => $totalPrice,
                'totalPayment' => $totalPayment,
                'remaining' => $remaining,
                'transaction' => $payment->transaction,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur génération facture: ' . $e->getMessage(), [
                'payment_id' => $payment->id,
                'error' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Erreur lors de la génération de la facture : ' . $e->getMessage());
        }
    }

    /**
     * Exporter les paiements
     */
    public function export(Request $request)
    {
        $query = Payment::with(['transaction.customer', 'user', 'createdBy'])
            ->orderBy('created_at', 'DESC');

        if ($request->filled('method')) {
            $query->where('payment_method', $request->method);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->get();

        $csv = fopen('php://temp', 'w');
        
        fputcsv($csv, [
            'ID',
            'Date',
            'Référence',
            'Client',
            'Transaction ID',
            'Montant (CFA)',
            'Méthode',
            'Statut',
            'Description',
            'Créé par',
            'Annulé par',
            'Motif annulation'
        ]);

        foreach ($payments as $payment) {
            fputcsv($csv, [
                $payment->id,
                $payment->created_at->format('d/m/Y H:i'),
                $payment->reference,
                $payment->transaction->customer->name ?? 'N/A',
                $payment->transaction_id,
                number_format($payment->amount, 0, ',', ' '),
                $payment->payment_method_label,
                $payment->status_text,
                $payment->description ?? '',
                $payment->createdBy->name ?? 'N/A',
                $payment->cancelledByUser->name ?? 'N/A',
                $payment->cancel_reason ?? ''
            ]);
        }

        rewind($csv);
        $csvData = stream_get_contents($csv);
        fclose($csv);

        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="paiements_' . date('Y-m-d_H-i') . '.csv"');
    }

    /**
     * API pour vérifier l'état d'une transaction
     */
    public function checkTransactionStatus(Transaction $transaction)
    {
        try {
            $transaction->updatePaymentStatus();
            $transaction->refresh();
            
            $completedPayments = $transaction->payments()->where('status', Payment::STATUS_COMPLETED)->get();
            
            $data = [
                'success' => true,
                'transaction' => [
                    'id' => $transaction->id,
                    'total_price' => (float) $transaction->total_price,
                    'total_payment' => (float) $transaction->total_payment,
                    'remaining' => $transaction->getRemainingPayment(),
                    'is_fully_paid' => $transaction->isFullyPaid(),
                    'payment_rate' => $transaction->getPaymentRate(),
                    'status' => $transaction->status,
                ],
                'payments' => [
                    'total_count' => $transaction->payments()->count(),
                    'completed_count' => $completedPayments->count(),
                    'completed_sum' => $completedPayments->sum('amount'),
                    'list' => $completedPayments
                        ->map(function($payment) {
                            return [
                                'id' => $payment->id,
                                'amount' => (float) $payment->amount,
                                'payment_method' => $payment->payment_method,
                                'reference' => $payment->reference,
                                'description' => $payment->description,
                                'created_at' => $payment->created_at->format('Y-m-d H:i:s'),
                            ];
                        })->take(5)->toArray(),
                ],
                'debug' => [
                    'calculated_at' => now()->format('Y-m-d H:i:s'),
                    'transaction_updated_at' => $transaction->updated_at->format('Y-m-d H:i:s'),
                ],
            ];
            
            Log::debug("Vérification statut transaction #{$transaction->id}", $data);
            
            return response()->json($data);
            
        } catch (\Exception $e) {
            Log::error("Erreur vérification transaction #{$transaction->id}: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id,
            ], 500);
        }
    }

    /**
     * API pour forcer la synchronisation
     */
    public function forceSync(Transaction $transaction)
    {
        try {
            // Recalculer le total des paiements
            $totalPayment = Payment::where('transaction_id', $transaction->id)
                ->where('status', Payment::STATUS_COMPLETED)
                ->sum('amount');
            
            $oldTotal = $transaction->total_payment;
            
            $transaction->update([
                'total_payment' => $totalPayment
            ]);
            $transaction->refresh();
            
            return response()->json([
                'success' => true,
                'message' => 'Synchronisation réussie',
                'data' => [
                    'old_total_payment' => $oldTotal,
                    'new_total_payment' => $totalPayment,
                    'remaining' => $transaction->getRemainingPayment(),
                    'is_fully_paid' => $transaction->isFullyPaid(),
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error("Erreur synchronisation transaction #{$transaction->id}: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id,
            ], 500);
        }
    }
}