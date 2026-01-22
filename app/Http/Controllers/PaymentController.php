<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Transaction;
use App\Repositories\Interface\PaymentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentRepositoryInterface $paymentRepository
    ) {}

    /**
     * Afficher la liste des paiements avec filtres
     */
    public function index(Request $request)
    {
        $query = Payment::with(['transaction.customer', 'transaction.room.type', 'user', 'cancelledByUser'])
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
                  ->orWhere('check_number', 'LIKE', "%{$search}%")
                  ->orWhere('mobile_money_number', 'LIKE', "%{$search}%")
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
        ]);
    }

    /**
     * Afficher le formulaire de création de paiement
     */
    public function create(Transaction $transaction)
    {
        // Vérifier si la transaction peut recevoir un paiement
        if ($transaction->getRemainingPayment() <= 0) {
            return redirect()->route('transaction.show', $transaction)
                ->with('error', 'Cette transaction est déjà entièrement payée.');
        }

        if (in_array($transaction->status, ['cancelled', 'no_show'])) {
            return redirect()->route('transaction.show', $transaction)
                ->with('error', 'Impossible d\'effectuer un paiement sur une transaction annulée.');
        }

        return view('transaction.payment.create', [
            'transaction' => $transaction,
            'paymentMethods' => Payment::getPaymentMethods(),
            'mobileMoneyProviders' => Payment::getMobileMoneyProviders(),
            'cardTypes' => Payment::getCardTypes(),
        ]);
    }

    /**
     * Enregistrer un nouveau paiement
     */
    public function store(Transaction $transaction, Request $request)
    {
        $remainingPayment = $transaction->getRemainingPayment();
        
        // Validation des règles de base
        $validator = Validator::make($request->all(), [
            'amount' => [
                'required',
                'numeric',
                'min:100',
                'max:' . $remainingPayment,
                function ($attribute, $value, $fail) {
                    if ($value <= 0) {
                        $fail('Le montant doit être supérieur à 0.');
                    }
                },
            ],
            'payment_method' => 'required|in:cash,card,transfer,mobile_money,fedapay,check',
            'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ], [
            'amount.max' => 'Le montant ne peut pas dépasser le solde restant de :max CFA.',
            'amount.min' => 'Le montant minimum est de :min CFA.',
        ]);

        // Validation conditionnelle selon la méthode de paiement
        if ($request->payment_method === Payment::METHOD_CARD) {
            $validator->addRules([
                'card_last_four' => 'required|string|size:4|regex:/^[0-9]{4}$/',
                'card_type' => 'required|in:visa,mastercard,amex',
            ]);
        }

        if ($request->payment_method === Payment::METHOD_CHECK) {
            $validator->addRules([
                'check_number' => 'required|string|max:50',
                'bank_name' => 'required|string|max:100',
            ]);
        }

        if ($request->payment_method === Payment::METHOD_TRANSFER) {
            $validator->addRules([
                'bank_name' => 'required|string|max:100',
                'account_number' => 'required|string|max:50',
            ]);
        }

        if ($request->payment_method === Payment::METHOD_MOBILE_MONEY) {
            $validator->addRules([
                'mobile_money_provider' => 'required|in:moov_money,mtn_money,flooz,orange_money',
                'mobile_money_number' => 'required|string|max:20',
            ]);
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Préparer les données du paiement
            $paymentData = [
                'user_id' => auth()->id(),
                'transaction_id' => $transaction->id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'status' => Payment::STATUS_COMPLETED,
                'notes' => $request->notes,
                'reference' => $request->reference ?? $this->generatePaymentReference($request->payment_method),
            ];

            // Ajouter les données spécifiques à la méthode
            switch ($request->payment_method) {
                case Payment::METHOD_CARD:
                    $paymentData['card_last_four'] = $request->card_last_four;
                    $paymentData['card_type'] = $request->card_type;
                    break;
                    
                case Payment::METHOD_CHECK:
                    $paymentData['check_number'] = $request->check_number;
                    $paymentData['bank_name'] = $request->bank_name;
                    break;
                    
                case Payment::METHOD_TRANSFER:
                    $paymentData['bank_name'] = $request->bank_name;
                    $paymentData['account_number'] = $request->account_number;
                    break;
                    
                case Payment::METHOD_MOBILE_MONEY:
                    $paymentData['mobile_money_provider'] = $request->mobile_money_provider;
                    $paymentData['mobile_money_number'] = $request->mobile_money_number;
                    break;
            }

            // Créer le paiement
            $payment = Payment::create($paymentData);

            // Mettre à jour le statut de paiement de la transaction
            $transaction->updatePaymentStatus();
            
            // ⭐ IMPORTANT : Rafraîchir l'instance pour avoir les données mises à jour
            $transaction->refresh();
            
            // Vérifier le statut de paiement maintenant que la transaction est à jour
            $isFullyPaid = $transaction->isFullyPaid();
            $newRemaining = $transaction->getRemainingPayment();

            // Journalisation
            activity()
                ->performedOn($payment)
                ->causedBy(auth()->user())
                ->withProperties([
                    'amount' => $request->amount,
                    'method' => $request->payment_method,
                    'transaction_id' => $transaction->id,
                    'customer' => $transaction->customer->name,
                    'fully_paid' => $isFullyPaid,
                    'remaining' => $newRemaining,
                ])
                ->log('Nouveau paiement enregistré');

            DB::commit();

            // Préparer le message de succès
            $successMessage = $this->generateSuccessMessage($payment, $isFullyPaid, $newRemaining);

            return redirect()->route('transaction.show', $transaction)
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Erreur enregistrement paiement: ' . $e->getMessage(), [
                'transaction_id' => $transaction->id,
                'user_id' => auth()->id(),
                'request' => $request->all(),
            ]);

            return redirect()->back()
                ->with('error', 'Erreur lors de l\'enregistrement du paiement: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Générer une référence de paiement
     */
    private function generatePaymentReference($method)
    {
        $prefixes = [
            Payment::METHOD_CASH => 'PAY-CASH',
            Payment::METHOD_CARD => 'PAY-CARD',
            Payment::METHOD_TRANSFER => 'PAY-TRANSFER',
            Payment::METHOD_MOBILE_MONEY => 'PAY-MOMO',
            Payment::METHOD_FEDAPAY => 'FDP',
            Payment::METHOD_CHECK => 'PAY-CHECK',
        ];

        $prefix = $prefixes[$method] ?? 'PAY';
        return $prefix . '-' . time() . rand(100, 999);
    }

    /**
     * Générer le message de succès
     */
    private function generateSuccessMessage(Payment $payment, bool $isFullyPaid, $remaining)
    {
        $methodLabels = [
            Payment::METHOD_CASH => 'en espèces',
            Payment::METHOD_CARD => 'par carte bancaire',
            Payment::METHOD_TRANSFER => 'par virement bancaire',
            Payment::METHOD_MOBILE_MONEY => 'par Mobile Money',
            Payment::METHOD_FEDAPAY => 'par Fedapay',
            Payment::METHOD_CHECK => 'par chèque',
        ];

        $methodLabel = $methodLabels[$payment->payment_method] ?? $payment->payment_method;

        $successMessage = sprintf(
            '<div class="alert alert-success alert-dismissible fade show" role="alert">'
            . '<div class="d-flex align-items-center">'
            . '<i class="fas fa-check-circle me-3 fs-4"></i>'
            . '<div>'
            . '<h5 class="alert-heading mb-1">✅ Paiement enregistré avec succès</h5>'
            . '<p class="mb-1">Montant : <strong>%s CFA</strong> %s</p>',
            number_format($payment->amount, 0, ',', ' '),
            $methodLabel
        );

        if ($payment->reference) {
            $successMessage .= sprintf('<small class="text-muted d-block">Référence : %s</small>', $payment->reference);
        }

        if ($isFullyPaid) {
            $successMessage .= '<div class="mt-2 alert alert-success py-2 border-0 mb-0">'
                . '<i class="fas fa-check-circle me-2"></i>'
                . '<strong>Transaction entièrement payée !</strong> Le séjour peut maintenant être marqué comme terminé.'
                . '</div>';
        } else {
            $successMessage .= sprintf(
                '<div class="mt-2 alert alert-info py-2 border-0 mb-0">'
                . '<i class="fas fa-info-circle me-2"></i>'
                . 'Solde restant : <strong>%s CFA</strong>'
                . '</div>',
                number_format($remaining, 0, ',', ' ')
            );
        }

        $successMessage .= '</div>'
            . '</div>'
            . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'
            . '</div>';

        return $successMessage;
    }

    /**
     * Afficher les détails d'un paiement
     */
    public function show(Payment $payment)
    {
        $payment->load(['transaction.customer', 'transaction.room.type', 'user', 'cancelledByUser']);
        
        return view('payment.show', [
            'payment' => $payment,
            'paymentMethods' => Payment::getPaymentMethods(),
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

        // Vérifier si le paiement peut être annulé
        if (!$payment->canBeCancelled()) {
            return redirect()->back()->with('error', 'Ce paiement ne peut pas être annulé.');
        }

        try {
            DB::beginTransaction();

            // Annuler le paiement
            $payment->cancel(auth()->id(), $request->cancel_reason);

            // Recalculer le total de la transaction
            if ($payment->transaction) {
                $payment->transaction->updatePaymentStatus();
                $payment->transaction->refresh(); // ⭐ Ajout de refresh()
            }

            // Journalisation
            activity()
                ->performedOn($payment)
                ->causedBy(auth()->user())
                ->withProperties(['cancel_reason' => $request->cancel_reason])
                ->log('Paiement annulé');

            DB::commit();

            return redirect()->route('payments.index')
                ->with('success', 'Paiement annulé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Erreur annulation paiement: ' . $e->getMessage(), [
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

        // Vérifier si le paiement peut être remboursé
        if (!$payment->canBeRefunded()) {
            return redirect()->back()->with('error', 'Ce paiement ne peut pas être remboursé.');
        }

        try {
            DB::beginTransaction();

            // Créer un paiement de remboursement (montant négatif)
            $refundPayment = Payment::create([
                'user_id' => auth()->id(),
                'transaction_id' => $payment->transaction_id,
                'amount' => -$payment->amount,
                'payment_method' => Payment::METHOD_REFUND,
                'status' => Payment::STATUS_COMPLETED,
                'reference' => 'REFUND-' . ($payment->reference ?? 'PAY-' . $payment->id),
                'notes' => 'Remboursement: ' . $request->cancel_reason,
            ]);

            // Marquer le paiement original comme remboursé
            $payment->markAsRefunded(auth()->id(), $request->cancel_reason);

            // Recalculer le total de la transaction
            if ($payment->transaction) {
                $payment->transaction->updatePaymentStatus();
                $payment->transaction->refresh(); // ⭐ Ajout de refresh()
            }

            // Journalisation
            activity()
                ->performedOn($payment)
                ->causedBy(auth()->user())
                ->withProperties([
                    'cancel_reason' => $request->cancel_reason,
                    'refund_payment_id' => $refundPayment->id,
                ])
                ->log('Paiement remboursé');

            DB::commit();

            return redirect()->route('payments.index')
                ->with('success', 'Paiement remboursé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Erreur remboursement paiement: ' . $e->getMessage(), [
                'payment_id' => $payment->id,
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()
                ->with('error', 'Erreur lors du remboursement: ' . $e->getMessage());
        }
    }

    /**
     * Restaurer un paiement annulé/expiré
     */
    public function restore(Payment $payment)
    {
        // Vérifier si le paiement peut être restauré
        if (!$payment->canBeRestored()) {
            return redirect()->back()->with('error', 'Ce paiement ne peut pas être restauré.');
        }

        try {
            DB::beginTransaction();

            $oldStatus = $payment->status;

            $payment->restorePayment();

            // Recalculer le total de la transaction
            if ($payment->transaction) {
                $payment->transaction->updatePaymentStatus();
                $payment->transaction->refresh(); // ⭐ Ajout de refresh()
            }

            // Journalisation
            activity()
                ->performedOn($payment)
                ->causedBy(auth()->user())
                ->withProperties(['old_status' => $oldStatus])
                ->log('Paiement restauré');

            DB::commit();

            return redirect()->back()
                ->with('success', 'Paiement restauré avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Erreur restauration paiement: ' . $e->getMessage(), [
                'payment_id' => $payment->id,
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()
                ->with('error', 'Erreur lors de la restauration: ' . $e->getMessage());
        }
    }

    /**
     * Générer une facture/reçu pour un paiement
     */
    public function invoice(Payment $payment)
    {
        try {
            // Charger toutes les relations nécessaires
            $payment->load([
                'transaction' => function($query) {
                    $query->with([
                        'customer',
                        'room.type',
                        'payments' => function($q) {
                            $q->orderBy('created_at', 'asc');
                        }
                    ]);
                },
                'user',
                'cancelledByUser'
            ]);
            
            // Vérifier que toutes les relations existent
            if (!$payment->transaction) {
                return redirect()->back()->with('error', 'Transaction non trouvée pour ce paiement.');
            }
            
            // Recalculer les totaux avec les données fraîches
            $payment->transaction->updatePaymentStatus();
            $payment->transaction->refresh(); // ⭐ Ajout de refresh()
            
            $totalPrice = $payment->transaction->getTotalPrice();
            $totalPayment = $payment->transaction->getTotalPayment();
            $remaining = $totalPrice - $totalPayment;
            
            return view('payment.invoice', [
                'payment' => $payment,
                'totalPrice' => $totalPrice,
                'totalPayment' => $totalPayment,
                'remaining' => $remaining
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erreur génération facture: ' . $e->getMessage(), [
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
        $query = Payment::with(['transaction.customer', 'user'])
            ->orderBy('created_at', 'DESC');

        // Appliquer les filtres
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

        // Générer le CSV
        $csv = fopen('php://temp', 'w');
        
        // En-têtes
        fputcsv($csv, [
            'ID',
            'Date',
            'Référence',
            'Client',
            'Transaction ID',
            'Montant (CFA)',
            'Méthode',
            'Statut',
            'Référence paiement',
            'Créé par',
            'Notes'
        ]);

        // Données
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
                $payment->reference,
                $payment->user->name ?? 'N/A',
                $payment->notes ?? ''
            ]);
        }

        rewind($csv);
        $csvData = stream_get_contents($csv);
        fclose($csv);

        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="paiements_' . date('Y-m-d') . '.csv"');
    }
}