<?php

namespace App\Repositories\Implementation;

use App\Models\Payment;
use App\Repositories\Interface\PaymentRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentRepository implements PaymentRepositoryInterface
{
    /**
     * Créer un paiement à partir d'une requête (ancienne méthode)
     */
    public function store($request, $transaction, string $status)
    {
        Log::info('PaymentRepository::store appelé', [
            'transaction_id' => $transaction->id,
            'status' => $status,
            'request_keys' => array_keys($request->all()),
            'amount_in_request' => $request->amount ?? 'non défini',
            'payment_method' => $request->payment_method ?? 'cash',
        ]);
        
        // Préparer les données avec les champs corrects
        $paymentData = [
            'user_id' => $transaction->customer_id ?? (auth()->id() ?? 1),
            'created_by' => auth()->id() ?? 1,
            'transaction_id' => $transaction->id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method ?? 'cash',
            'status' => $status,
            'reference' => $request->reference ?? $this->generateReference($request->payment_method ?? 'cash', $transaction->id),
            'description' => ($request->description ?? $request->notes ?? '') . ($status ? ' - ' . $status : ''),
        ];
        
        Log::info('Données de création du paiement', $paymentData);
        
        return Payment::create($paymentData);
    }
    
    /**
     * NOUVELLE MÉTHODE : Créer un paiement directement à partir d'un tableau
     */
    public function create(array $data)
    {
        Log::info('PaymentRepository::create appelé', $data);
        
        return Payment::create([
            'user_id' => $data['user_id'] ?? (auth()->id() ?? 1),
            'created_by' => $data['created_by'] ?? (auth()->id() ?? 1),
            'transaction_id' => $data['transaction_id'],
            'amount' => $data['amount'],
            'payment_method' => $data['payment_method'] ?? Payment::METHOD_CASH,
            'reference' => $data['reference'] ?? $this->generateReference($data['payment_method'] ?? Payment::METHOD_CASH, $data['transaction_id']),
            'status' => $data['status'] ?? Payment::STATUS_COMPLETED,
            'description' => $data['description'] ?? $data['notes'] ?? null,
        ]);
    }
    
    /**
     * Créer un paiement avec paramètres simplifiés
     */
    public function createPayment($transactionId, $amount, $method = Payment::METHOD_CASH, $description = null)
    {
        Log::info('PaymentRepository::createPayment appelé', [
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'method' => $method,
        ]);
        
        return Payment::create([
            'user_id' => auth()->id() ?? 1,
            'created_by' => auth()->id() ?? 1,
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'payment_method' => $method,
            'reference' => $this->generateReference($method, $transactionId),
            'status' => Payment::STATUS_COMPLETED,
            'description' => $description,
        ]);
    }
    
    /**
     * Récupérer les paiements d'une transaction
     */
    public function getByTransaction($transactionId)
    {
        return Payment::with(['user', 'createdBy', 'cancelledByUser'])
            ->where('transaction_id', $transactionId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    /**
     * Récupérer les paiements complétés d'une transaction
     */
    public function getCompletedByTransaction($transactionId)
    {
        return Payment::where('transaction_id', $transactionId)
            ->where('status', Payment::STATUS_COMPLETED)
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    /**
     * Récupérer le total des paiements d'une transaction
     */
    public function getTotalByTransaction($transactionId)
    {
        return Payment::where('transaction_id', $transactionId)
            ->where('status', Payment::STATUS_COMPLETED)
            ->sum('amount');
    }
    
    /**
     * Créer un remboursement
     */
    public function createRefund($transactionId, $amount, $reason = null)
    {
        Log::info('PaymentRepository::createRefund appelé', [
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'reason' => $reason,
        ]);
        
        return Payment::create([
            'user_id' => auth()->id() ?? 1,
            'created_by' => auth()->id() ?? 1,
            'transaction_id' => $transactionId,
            'amount' => -abs($amount),
            'payment_method' => Payment::METHOD_REFUND,
            'reference' => 'REFUND-' . $transactionId . '-' . time(),
            'status' => Payment::STATUS_COMPLETED,
            'description' => 'Remboursement' . ($reason ? ' - ' . $reason : ''),
        ]);
    }
    
    /**
     * Marquer un paiement comme remboursé
     */
    public function markAsRefunded($paymentId, $userId, $reason = null)
    {
        $payment = Payment::findOrFail($paymentId);
        
        $payment->update([
            'status' => Payment::STATUS_REFUNDED,
            'cancelled_at' => now(),
            'cancelled_by' => $userId,
            'cancel_reason' => $reason ?? 'Remboursement',
        ]);
        
        return $payment;
    }
    
    /**
     * Mettre à jour le statut d'un paiement
     */
    public function updateStatus($paymentId, $status, $description = null)
    {
        $payment = Payment::findOrFail($paymentId);
        
        $updateData = ['status' => $status];
        if ($description) {
            $updateData['description'] = ($payment->description ? $payment->description . ' | ' : '') . $description;
        }
        
        $payment->update($updateData);
        
        return $payment;
    }
    
    /**
     * Annuler un paiement
     */
    public function cancel($paymentId, $userId, $reason = null)
    {
        $payment = Payment::findOrFail($paymentId);
        
        $payment->update([
            'status' => Payment::STATUS_CANCELLED,
            'cancelled_at' => now(),
            'cancelled_by' => $userId,
            'cancel_reason' => $reason ?? 'Annulé par l\'utilisateur',
            'description' => ($payment->description ? $payment->description . ' | ' : '') . 'Annulé le ' . now()->format('d/m/Y H:i'),
        ]);
        
        return $payment;
    }
    
    /**
     * Supprimer un paiement (annulation)
     */
    public function delete($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        
        // Marquer comme annulé plutôt que supprimer
        $payment->update([
            'status' => Payment::STATUS_CANCELLED,
            'cancelled_at' => now(),
            'cancelled_by' => auth()->id() ?? 1,
            'cancel_reason' => 'Supprimé',
            'description' => ($payment->description ? $payment->description . ' | ' : '') . 'Supprimé le ' . now()->format('d/m/Y H:i'),
        ]);
        
        return $payment;
    }
    
    /**
     * Rechercher des paiements
     */
    public function search($request)
    {
        return Payment::with([
                'transaction.customer', 
                'user', 
                'createdBy', 
                'cancelledByUser'
            ])
            ->when($request->filled('reference'), function ($query) use ($request) {
                $query->where('reference', 'like', '%' . $request->reference . '%');
            })
            ->when($request->filled('transaction_id'), function ($query) use ($request) {
                $query->where('transaction_id', $request->transaction_id);
            })
            ->when($request->filled('payment_method'), function ($query) use ($request) {
                $query->where('payment_method', $request->payment_method);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->where('created_at', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->where('created_at', '<=', $request->date_to);
            })
            ->when($request->filled('user_id'), function ($query) use ($request) {
                $query->where('user_id', $request->user_id);
            })
            ->when($request->filled('created_by'), function ($query) use ($request) {
                $query->where('created_by', $request->created_by);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 20);
    }
    
    /**
     * Paiements du jour
     */
    public function getTodayPayments()
    {
        return Payment::whereDate('created_at', today())
            ->where('status', Payment::STATUS_COMPLETED)
            ->sum('amount');
    }
    
    /**
     * Montant des paiements du jour
     */
    public function getTodayPaymentsAmount()
    {
        return Payment::whereDate('created_at', today())
            ->where('status', Payment::STATUS_COMPLETED)
            ->sum('amount');
    }
    
    /**
     * Nombre de paiements du jour
     */
    public function getTodayPaymentsCount()
    {
        return Payment::whereDate('created_at', today())
            ->where('status', Payment::STATUS_COMPLETED)
            ->count();
    }
    
    /**
     * Paiements par méthode
     */
    public function getPaymentsByMethod($startDate = null, $endDate = null)
    {
        $query = Payment::where('status', Payment::STATUS_COMPLETED);
        
        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }
        
        return $query->selectRaw('payment_method, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('payment_method')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->payment_method => [
                        'total' => $item->total,
                        'count' => $item->count,
                        'label' => Payment::getPaymentMethods()[$item->payment_method]['label'] ?? $item->payment_method
                    ]
                ];
            });
    }
    
    /**
     * Paiements par période
     */
    public function getPaymentsByPeriod($startDate, $endDate)
    {
        return Payment::with(['transaction.customer', 'user', 'createdBy'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', Payment::STATUS_COMPLETED)
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    /**
     * Statistiques de paiements
     */
    public function getPaymentStats($startDate = null, $endDate = null)
    {
        $query = Payment::query();
        
        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }
        
        $totalAmount = (clone $query)->where('status', Payment::STATUS_COMPLETED)->sum('amount');
        $totalCount = (clone $query)->count();
        $completedCount = (clone $query)->where('status', Payment::STATUS_COMPLETED)->count();
        $pendingCount = (clone $query)->where('status', Payment::STATUS_PENDING)->count();
        $cancelledCount = (clone $query)->where('status', Payment::STATUS_CANCELLED)->count();
        
        return [
            'total_amount' => $totalAmount,
            'total_count' => $totalCount,
            'completed_count' => $completedCount,
            'pending_count' => $pendingCount,
            'cancelled_count' => $cancelledCount,
            'completion_rate' => $totalCount > 0 ? round(($completedCount / $totalCount) * 100, 2) : 0,
            'average_amount' => $completedCount > 0 ? round($totalAmount / $completedCount, 2) : 0,
        ];
    }
    
    /**
     * Générer une référence
     */
    private function generateReference($method, $transactionId)
    {
        $prefixes = [
            Payment::METHOD_CASH => 'CASH',
            Payment::METHOD_CARD => 'CARD',
            Payment::METHOD_TRANSFER => 'VIR',
            Payment::METHOD_MOBILE_MONEY => 'MOMO',
            Payment::METHOD_FEDAPAY => 'FDP',
            Payment::METHOD_CHECK => 'CHQ',
            Payment::METHOD_REFUND => 'REF',
        ];
        
        $prefix = $prefixes[$method] ?? 'PAY';
        $timestamp = time();
        $random = rand(1000, 9999);
        
        return "{$prefix}-{$transactionId}-{$timestamp}-{$random}";
    }
    
    /**
     * Obtenir les méthodes de paiement disponibles
     */
    public function getPaymentMethods()
    {
        return Payment::getPaymentMethods();
    }
    
    /**
     * Vérifier si une transaction est entièrement payée
     */
    public function isTransactionFullyPaid($transactionId, $transactionTotal)
    {
        $totalPaid = $this->getTotalByTransaction($transactionId);
        return $totalPaid >= $transactionTotal;
    }
    
    /**
     * Obtenir le solde restant d'une transaction
     */
    public function getTransactionBalance($transactionId, $transactionTotal)
    {
        $totalPaid = $this->getTotalByTransaction($transactionId);
        return max(0, $transactionTotal - $totalPaid);
    }
    
    /**
     * Récupérer les paiements par session de caisse
     */
    public function getByCashierSession($sessionId)
    {
        return Payment::where('cashier_session_id', $sessionId)
            ->where('status', Payment::STATUS_COMPLETED)
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    /**
     * Total des paiements par session de caisse
     */
    public function getTotalByCashierSession($sessionId)
    {
        return Payment::where('cashier_session_id', $sessionId)
            ->where('status', Payment::STATUS_COMPLETED)
            ->sum('amount');
    }
}