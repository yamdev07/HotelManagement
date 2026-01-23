<?php

namespace App\Repositories\Implementation;

use App\Models\Payment;
use App\Repositories\Interface\PaymentRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class PaymentRepository implements PaymentRepositoryInterface
{
    /**
     * Créer un paiement à partir d'une requête (ancienne méthode)
     */
    public function store($request, $transaction, string $status)
    {
        return Payment::create([
            'user_id' => Auth()->id() ?? 1,
            'transaction_id' => $transaction->id,
            'amount' => empty($request->downPayment) ? ($request->payment ?? 0) : $request->downPayment, // ✅ amount au lieu de price
            'payment_method' => $request->payment_method ?? 'cash',
            'reference' => $request->reference ?? ('PAY-' . $transaction->id . '-' . time()),
            'status' => $status,
            'notes' => $status . ' - ' . ($request->notes ?? ''),
            // 'payment_date' => now(), // ❌ Cette colonne n'existe pas chez vous
            // 'created_by' => Auth()->id() ?? 1, // ❌ Cette colonne n'existe pas chez vous
        ]);
    }
    
    /**
     * NOUVELLE MÉTHODE : Créer un paiement directement à partir d'un tableau
     */
    public function create(array $data)
    {
        return Payment::create([
            'user_id' => $data['user_id'] ?? Auth()->id() ?? 1,
            'transaction_id' => $data['transaction_id'],
            'amount' => $data['amount'], // ✅ amount au lieu de price
            'payment_method' => $data['payment_method'] ?? 'cash',
            'reference' => $data['reference'] ?? ('PAY-' . $data['transaction_id'] . '-' . time()),
            'status' => $data['status'] ?? 'completed',
            'notes' => $data['notes'] ?? null,
            // 'payment_date' => $data['payment_date'] ?? now(), // ❌ Cette colonne n'existe pas
            // 'created_by' => $data['created_by'] ?? Auth()->id() ?? 1, // ❌ Cette colonne n'existe pas
        ]);
    }
    
    /**
     * Créer un paiement avec paramètres simplifiés
     */
    public function createPayment($transactionId, $amount, $method = 'cash', $notes = null)
    {
        return Payment::create([
            'user_id' => Auth()->id() ?? 1,
            'transaction_id' => $transactionId,
            'amount' => $amount, // ✅ amount au lieu de price
            'payment_method' => $method,
            'reference' => 'PAY-' . $transactionId . '-' . time(),
            'status' => 'completed',
            'notes' => $notes,
            // 'payment_date' => now(), // ❌ Cette colonne n'existe pas
            // 'created_by' => Auth()->id() ?? 1, // ❌ Cette colonne n'existe pas
        ]);
    }
    
    /**
     * Récupérer les paiements d'une transaction
     */
    public function getByTransaction($transactionId)
    {
        return Payment::where('transaction_id', $transactionId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    /**
     * Récupérer le total des paiements d'une transaction
     */
    public function getTotalByTransaction($transactionId)
    {
        return Payment::where('transaction_id', $transactionId)
            ->where('status', 'completed')
            ->sum('amount'); // ✅ sum('amount') au lieu de sum('price')
    }
    
    /**
     * Créer un remboursement
     */
    public function createRefund($transactionId, $amount, $reason = null)
    {
        return Payment::create([
            'user_id' => Auth()->id() ?? 1,
            'transaction_id' => $transactionId,
            'amount' => -abs($amount), // ✅ amount au lieu de price (négatif pour remboursement)
            'payment_method' => 'refund',
            'reference' => 'REFUND-' . $transactionId . '-' . time(),
            'status' => 'completed',
            'notes' => 'Remboursement' . ($reason ? ' - ' . $reason : ''),
            // 'payment_date' => now(), // ❌ Cette colonne n'existe pas
            // 'created_by' => Auth()->id() ?? 1, // ❌ Cette colonne n'existe pas
        ]);
    }
    
    /**
     * Mettre à jour le statut d'un paiement
     */
    public function updateStatus($paymentId, $status, $notes = null)
    {
        $payment = Payment::findOrFail($paymentId);
        
        $updateData = ['status' => $status];
        if ($notes) {
            $updateData['notes'] = $payment->notes . ' | ' . $notes;
        }
        
        $payment->update($updateData);
        
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
            'status' => 'cancelled',
            'notes' => $payment->notes . ' | Annulé le ' . now()->format('d/m/Y H:i'),
        ]);
        
        return $payment;
    }
    
    /**
     * Rechercher des paiements
     */
    public function search($request)
    {
        return Payment::with(['transaction', 'transaction.customer', 'user'])
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
                // Utiliser created_at au lieu de payment_date qui n'existe pas
                $query->where('created_at', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->where('created_at', '<=', $request->date_to);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }
    
    /**
     * Paiements du jour
     */
    public function getTodayPayments()
    {
        return Payment::whereDate('created_at', today()) // ✅ created_at au lieu de payment_date
            ->where('status', 'completed')
            ->sum('amount'); // ✅ sum('amount') au lieu de sum('price')
    }
    
    /**
     * Paiements par méthode
     */
    public function getPaymentsByMethod($startDate = null, $endDate = null)
    {
        $query = Payment::where('status', 'completed');
        
        if ($startDate) {
            // Utiliser created_at au lieu de payment_date
            $query->where('created_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }
        
        return $query->selectRaw('payment_method, SUM(amount) as total, COUNT(*) as count') // ✅ SUM(amount)
            ->groupBy('payment_method')
            ->get();
    }
}