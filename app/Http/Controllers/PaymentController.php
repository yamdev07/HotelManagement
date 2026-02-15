<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
            $query->where(function ($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('reference', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhereHas('transaction.customer', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('transaction.room', function ($q) use ($search) {
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
                'remaining_formatted' => number_format($remaining, 0, ',', ' ').' CFA',
                'payment_rate' => $transaction->getPaymentRate(),
                'total_payments' => $transaction->payments()->where('status', Payment::STATUS_COMPLETED)->count(),
            ],
        ]);
    }

    /**
     * Enregistrer un nouveau paiement - VERSION OPTION 2 CORRIGÉE
     */
    public function store(Transaction $transaction, Request $request)
    {
        Log::info('=== NOUVEAU PAIEMENT ===', [
            'transaction_id' => $transaction->id,
            'user_authenticated' => auth()->id(),
            'remaining_before' => $transaction->getRemainingPayment(),
        ]);

        // Validation simplifiée mais robuste
        $validator = Validator::make($request->all(), [
            'amount' => [
                'required',
                'numeric',
                'min:100',
                'max:'.$transaction->getRemainingPayment(),
                function ($attribute, $value, $fail) use ($transaction) {
                    if ($value > $transaction->getRemainingPayment() + 100) {
                        $fail('Le montant ne peut pas dépasser '.number_format($transaction->getRemainingPayment(), 0, ',', ' ').' CFA');
                    }
                },
            ],
            'payment_method' => 'required|in:'.implode(',', array_keys(Payment::getPaymentMethods())),
            'description' => 'nullable|string|max:500',
        ], [
            'amount.max' => 'Le montant ne peut pas dépasser le solde restant de :max CFA',
            'amount.min' => 'Le montant minimum est de :min CFA',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation échouée', $validator->errors()->toArray());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Veuillez corriger les erreurs',
                ], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Erreur de validation');
        }

        $validated = $validator->validated();

        DB::beginTransaction();

        try {
            // OPTION 2 : Trouver le bon user_id de manière intelligente
            $userId = $this->getValidUserId();

            Log::info('Identifiant utilisateur déterminé', [
                'user_id' => $userId,
                'auth_user' => auth()->id(),
            ]);

            // Générer une référence unique
            $reference = $this->generateUniqueReference($validated['payment_method'], $transaction);

            // Préparer les données avec user_id CORRECT
            $paymentData = [
                'user_id' => $userId, // ← CORRECTEMENT DÉTERMINÉ
                'created_by' => auth()->id(), // Celui qui crée le paiement
                'transaction_id' => $transaction->id,
                'amount' => (float) $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'status' => Payment::STATUS_COMPLETED,
                'reference' => $reference,
                'description' => $validated['description'] ?? null,
            ];

            // Création du paiement
            $payment = Payment::create($paymentData);

            Log::info('Paiement créé', [
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'reference' => $payment->reference,
                'method' => $payment->payment_method,
            ]);

            // FORCER la mise à jour des totaux de la transaction
            $this->forceUpdateTransactionTotals($transaction);

            // Si c'est un remboursement (montant négatif), adapter le message
            $isRefund = $payment->amount < 0;

            DB::commit();

            Log::info('=== PAIEMENT TERMINÉ AVEC SUCCÈS ===', [
                'payment_id' => $payment->id,
                'transaction_id' => $transaction->id,
                'new_remaining' => $transaction->getRemainingPayment(),
                'is_fully_paid' => $transaction->isFullyPaid(),
            ]);

            // Réponse adaptée au type de requête
            return $this->handlePaymentResponse($payment, $transaction, $request, $isRefund);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('=== ERREUR CRITIQUE PAIEMENT ===', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'transaction_id' => $transaction->id,
                'user_trying' => auth()->id(),
            ]);

            return $this->handlePaymentError($e, $request);
        }
    }

    /**
     * Méthode intelligente pour obtenir un user_id valide - OPTION 2
     */
    private function getValidUserId()
    {
        // OPTION 2A : Utiliser l'utilisateur connecté (premier choix)
        if (auth()->check()) {
            $authUser = auth()->user();
            if ($authUser && \App\Models\User::find($authUser->id)) {
                Log::debug('Utilisation de l\'utilisateur connecté', ['user_id' => $authUser->id]);

                return $authUser->id;
            }
        }

        // OPTION 2B : Chercher un administrateur ou super admin
        $admin = \App\Models\User::whereIn('role', ['Super', 'Admin', 'Receptionist'])
            ->orderBy('id')
            ->first();

        if ($admin) {
            Log::debug('Utilisation d\'un admin trouvé', ['user_id' => $admin->id, 'role' => $admin->role]);

            return $admin->id;
        }

        // OPTION 2C : Utiliser le premier utilisateur disponible
        $firstUser = \App\Models\User::orderBy('id')->first();
        if ($firstUser) {
            Log::debug('Utilisation du premier utilisateur', ['user_id' => $firstUser->id]);

            return $firstUser->id;
        }

        // OPTION 2D : En dernier recours, créer un utilisateur système
        Log::warning('Aucun utilisateur trouvé, création d\'un utilisateur système');
        $systemUser = $this->createSystemUser();

        return $systemUser->id;
    }

    /**
     * Créer un utilisateur système en cas d'urgence
     */
    private function createSystemUser()
    {
        try {
            $user = \App\Models\User::create([
                'name' => 'Système Paiement',
                'email' => 'payment.system@'.request()->getHost(),
                'password' => bcrypt(uniqid('sys_', true)),
                'role' => 'System',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Log::info('Utilisateur système créé', ['user_id' => $user->id]);

            return $user;

        } catch (\Exception $e) {
            Log::emergency('Impossible de créer un utilisateur système', [
                'error' => $e->getMessage(),
            ]);

            // Dernier recours : utiliser ID 1 (très risqué)
            return (object) ['id' => 1];
        }
    }

    /**
     * Générer une référence unique
     */
    private function generateUniqueReference($method, $transaction)
    {
        $prefixes = [
            'cash' => 'CASH',
            'card' => 'CARD',
            'transfer' => 'VIR',
            'mobile_money' => 'MOMO',
            'fedapay' => 'FDP',
            'check' => 'CHQ',
            'refund' => 'REF',
        ];

        $prefix = $prefixes[$method] ?? 'PAY';
        $timestamp = time();
        $random = mt_rand(1000, 9999);

        return sprintf('%s-%d-%d-%d', $prefix, $transaction->id, $timestamp, $random);
    }

    /**
     * Forcer la mise à jour des totaux de la transaction
     */
    private function forceUpdateTransactionTotals(Transaction $transaction)
    {
        try {
            // 1. Recalculer le total des paiements COMPLÉTÉS
            $totalPayment = Payment::where('transaction_id', $transaction->id)
                ->where('status', Payment::STATUS_COMPLETED)
                ->sum('amount');

            // 2. Recalculer le prix total (au cas où les dates ont changé)
            $totalPrice = $transaction->getTotalPrice();

            // 3. Mettre à jour la transaction
            $transaction->update([
                'total_price' => $totalPrice,
                'total_payment' => $totalPayment,
            ]);

            // 4. Rafraîchir
            $transaction->refresh();

            Log::debug('Transaction mise à jour', [
                'transaction_id' => $transaction->id,
                'total_price' => $totalPrice,
                'total_payment' => $totalPayment,
                'remaining' => $transaction->getRemainingPayment(),
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur mise à jour transaction', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Gérer la réponse après un paiement réussi
     */
    private function handlePaymentResponse(Payment $payment, Transaction $transaction, Request $request, $isRefund = false)
    {
        $isFullyPaid = $transaction->isFullyPaid();
        $remaining = $transaction->getRemainingPayment();

        // Préparer le message de succès
        $action = $isRefund ? 'Remboursement' : 'Paiement';
        $amountFormatted = number_format(abs($payment->amount), 0, ',', ' ').' CFA';
        $methodLabel = $payment->payment_method_label;

        $successMessage = "✅ {$action} de {$amountFormatted} par {$methodLabel} enregistré avec succès !";

        if ($isFullyPaid) {
            $successMessage .= ' Transaction entièrement réglée.';
        } elseif ($remaining > 0) {
            $successMessage .= ' Solde restant : '.number_format($remaining, 0, ',', ' ').' CFA';
        }

        // Réponse AJAX
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'data' => [
                    'payment' => [
                        'id' => $payment->id,
                        'amount' => $payment->amount,
                        'reference' => $payment->reference,
                        'method' => $payment->payment_method,
                        'method_label' => $payment->payment_method_label,
                        'created_at' => $payment->created_at->format('d/m/Y H:i'),
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
                        'amount' => abs($payment->amount),
                        'newRemaining' => $remaining,
                        'paymentRate' => $transaction->getPaymentRate(),
                    ],
                ],
                'redirect_url' => route('transaction.show', $transaction),
                'toast' => [
                    'title' => 'Succès',
                    'message' => $successMessage,
                    'type' => 'success',
                    'duration' => 5000,
                ],
            ], 200);
        }

        // Redirection normale
        return redirect()->route('transaction.show', $transaction)
            ->with('success', $successMessage)
            ->with('payment_details', [
                'id' => $payment->id,
                'amount' => $payment->amount,
                'reference' => $payment->reference,
                'method' => $payment->payment_method,
            ]);
    }

    /**
     * Gérer les erreurs de paiement
     */
    private function handlePaymentError(\Exception $e, Request $request)
    {
        $errorMessage = env('APP_DEBUG')
            ? $e->getMessage()
            : 'Une erreur est survenue lors de l\'enregistrement du paiement. Veuillez réessayer.';

        Log::error('Erreur paiement utilisateur', [
            'error' => $e->getMessage(),
            'user_agent' => $request->userAgent(),
            'ip' => $request->ip(),
        ]);

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'error' => env('APP_DEBUG') ? $e->getTraceAsString() : null,
                'toast' => [
                    'title' => 'Erreur',
                    'message' => $errorMessage,
                    'type' => 'error',
                    'duration' => 7000,
                ],
            ], 500);
        }

        return redirect()->back()
            ->with('error', $errorMessage)
            ->withInput();
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
            'cancel_reason' => 'required|string|max:500',
        ]);

        Log::info("Annulation paiement #{$payment->id}", [
            'payment_status' => $payment->status,
            'transaction_id' => $payment->transaction_id,
            'user_id' => auth()->id(),
        ]);

        if (! $payment->canBeCancelled()) {
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

            Log::error('Erreur annulation paiement: '.$e->getMessage(), [
                'payment_id' => $payment->id,
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()
                ->with('error', 'Erreur lors de l\'annulation: '.$e->getMessage());
        }
    }

    /**
     * Rembourser un paiement
     */
    public function refund(Request $request, Payment $payment)
    {
        $request->validate([
            'cancel_reason' => 'required|string|max:500',
        ]);

        if (! $payment->canBeRefunded()) {
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
                'reference' => 'REFUND-'.($payment->reference ?? 'PAY-'.$payment->id),
                'description' => 'Remboursement: '.$request->cancel_reason,
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

            Log::error('Erreur remboursement paiement: '.$e->getMessage(), [
                'payment_id' => $payment->id,
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()
                ->with('error', 'Erreur lors du remboursement: '.$e->getMessage());
        }
    }

    /**
     * Générer une facture/reçu pour un paiement
     */
    public function invoice(Payment $payment)
    {
        try {
            $payment->load([
                'transaction' => function ($query) {
                    $query->with([
                        'customer',
                        'room.type',
                        'payments' => function ($q) {
                            $q->where('status', Payment::STATUS_COMPLETED)->orderBy('created_at', 'asc');
                        },
                    ]);
                },
                'user',
                'createdBy',
                'cancelledByUser',
            ]);

            if (! $payment->transaction) {
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
            Log::error('Erreur génération facture: '.$e->getMessage(), [
                'payment_id' => $payment->id,
                'error' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Erreur lors de la génération de la facture : '.$e->getMessage());
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
            'Motif annulation',
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
                $payment->cancel_reason ?? '',
            ]);
        }

        rewind($csv);
        $csvData = stream_get_contents($csv);
        fclose($csv);

        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="paiements_'.date('Y-m-d_H-i').'.csv"');
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
                        ->map(function ($payment) {
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
            Log::error("Erreur vérification transaction #{$transaction->id}: ".$e->getMessage());

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
                'total_payment' => $totalPayment,
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
                ],
            ]);

        } catch (\Exception $e) {
            Log::error("Erreur synchronisation transaction #{$transaction->id}: ".$e->getMessage());

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id,
            ], 500);
        }
    }

    /**
     * ✅ Récupérer les détails d'un paiement au format JSON
     * Utilisé par la modale AJAX
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetails($id)
    {
        try {
            // Vérifier les permissions
            if (!in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            // Charger le paiement avec ses relations
            $payment = Payment::with([
                'transaction.customer',
                'transaction.room',
                'user',
                'createdBy',
                'cancelledByUser'
            ])->find($id);

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Paiement non trouvé'
                ], 404);
            }

            // ✅ CORRECTION : Vérifier chaque relation avant d'accéder aux propriétés
            $userName = $payment->user ? $payment->user->name : 'Système';
            $createdByName = $payment->createdBy ? $payment->createdBy->name : 'N/A';
            $cancelledByName = $payment->cancelledByUser ? $payment->cancelledByUser->name : null;
            
            $guestName = 'N/A';
            $roomNumber = 'N/A';
            $transactionData = null;
            
            if ($payment->transaction) {
                $guestName = $payment->transaction->customer->name ?? 'N/A';
                $roomNumber = $payment->transaction->room->number ?? 'N/A';
                
                // Recalculer les totaux si nécessaire
                if (method_exists($payment->transaction, 'updatePaymentStatus')) {
                    $payment->transaction->updatePaymentStatus();
                }
                $payment->transaction->refresh();
                
                // Préparer les données de transaction
                $transactionData = [
                    'id' => $payment->transaction->id,
                    'total_price' => $payment->transaction->total_price,
                    'total_payment' => $payment->transaction->total_payment,
                    'remaining' => method_exists($payment->transaction, 'getRemainingPayment') 
                        ? $payment->transaction->getRemainingPayment() 
                        : 0,
                    'is_fully_paid' => method_exists($payment->transaction, 'isFullyPaid') 
                        ? $payment->transaction->isFullyPaid() 
                        : false,
                    'status' => $payment->transaction->status,
                    'check_in' => $payment->transaction->check_in 
                        ? $payment->transaction->check_in->format('d/m/Y') 
                        : 'N/A',
                    'check_out' => $payment->transaction->check_out 
                        ? $payment->transaction->check_out->format('d/m/Y') 
                        : 'N/A',
                ];
            }

            // Formater les données pour la modale
            $data = [
                'success' => true,
                'payment' => [
                    'id' => $payment->id,
                    'amount' => $payment->amount,
                    'amount_formatted' => number_format($payment->amount, 0, ',', ' ') . ' CFA',
                    'reference' => $payment->reference ?? 'N/A',
                    'status' => $this->getStatusLabel($payment->status),
                    'status_color' => $this->getStatusColor($payment->status),
                    'method' => $payment->payment_method_label ?? $payment->payment_method ?? 'Non spécifié',
                    'transaction_id' => $payment->transaction_id,
                    'guest_name' => $guestName,
                    'room_number' => $roomNumber,
                    'processed_by' => $userName,
                    'created_by' => $createdByName,
                    'cancelled_by' => $cancelledByName,
                    'cancelled_at' => $payment->cancelled_at ? $payment->cancelled_at->format('d/m/Y H:i') : null,
                    'cancel_reason' => $payment->cancel_reason,
                    'date_formatted' => $payment->created_at ? $payment->created_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i'),
                    'created_at' => $payment->created_at ? $payment->created_at->format('d/m/Y H:i:s') : now()->format('d/m/Y H:i:s'),
                    'verified_at' => $payment->verified_at ? $payment->verified_at->format('d/m/Y H:i') : null,
                    'notes' => $payment->description ?? $payment->notes,
                    'is_refund' => $payment->amount < 0,
                    'is_cancellable' => method_exists($payment, 'canBeCancelled') ? $payment->canBeCancelled() : false,
                    'is_refundable' => method_exists($payment, 'canBeRefunded') ? $payment->canBeRefunded() : false,
                    'transaction' => $transactionData,
                ]
            ];

            Log::info("✅ Détails paiement #{$id} chargés avec succès", [
                'user' => auth()->user()->name ?? 'inconnu',
                'role' => auth()->user()->role ?? 'inconnu'
            ]);

            return response()->json($data);

        } catch (\Exception $e) {
            Log::error('❌ Erreur getDetails paiement:', [
                'payment_id' => $id,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur: ' . $e->getMessage(),
                'error_details' => config('app.debug') ? [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile()
                ] : null
            ], 500);
        }
    }

    /**
     * Helper pour le libellé du statut
     */
    private function getStatusLabel($status)
    {
        $labels = [
            'pending' => 'En attente',
            'completed' => 'Complété',
            'cancelled' => 'Annulé',
            'expired' => 'Expiré',
            'failed' => 'Échoué',
            'refunded' => 'Remboursé'
        ];
        
        return $labels[$status] ?? ucfirst($status);
    }

    /**
     * Helper pour la couleur du statut
     */
    private function getStatusColor($status)
    {
        $colors = [
            'pending' => 'warning',
            'completed' => 'success',
            'cancelled' => 'secondary',
            'expired' => 'danger',
            'failed' => 'danger',
            'refunded' => 'info'
        ];
        
        return $colors[$status] ?? 'secondary';
    }
}
