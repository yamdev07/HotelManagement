<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use App\Models\Payment;
use App\Helpers\Helper; // Si vous utilisez Helper

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Récupérer les transactions ACTIVES (clients actuellement dans l'hôtel)
        $transactions = Transaction::with(['customer.user', 'room.type', 'payment']) // 'payment' pas 'payments'
            ->where([
                ['check_in', '<=', Carbon::now()],
                ['check_out', '>=', Carbon::now()],
                // ['status', '=', 'active'] // DÉCOMMENTEZ si vous avez un champ status
            ])
            ->orderBy('check_out', 'ASC')
            ->orderBy('id', 'DESC')
            ->get();

        // 2. Récupérer les arrivées d'aujourd'hui (pour debug)
        $todayArrivals = Transaction::with(['customer.user', 'room.type', 'payment'])
            ->whereDate('check_in', Carbon::today())
            ->get();

        // 3. Compter les statistiques pour les cartes
        $stats = [
            'activeGuests' => $transactions->count(),
            'pendingPayments' => 0,
            'urgentPayments' => 0,
            'completedToday' => 0,
            'todayArrivals' => $todayArrivals->count() // Pour debug
        ];

        // Calculer les paiements en attente et urgents
        foreach ($transactions as $transaction) {
            // Calculer le solde
            $balance = $this->calculateBalance($transaction);
            
            if ($balance > 0) {
                $stats['pendingPayments']++;
                
                // Vérifier si c'est urgent (check-out aujourd'hui ou demain)
                $checkOut = Carbon::parse($transaction->check_out);
                $now = Carbon::now();
                
                // Si check-out dans moins de 24h
                if ($checkOut->diffInHours($now) <= 24) {
                    $stats['urgentPayments']++;
                }
            }
            
            // Vérifier les réservations terminées aujourd'hui
            if (Carbon::parse($transaction->check_out)->isToday()) {
                $stats['completedToday']++;
            }
        }

        return view('dashboard.index', [
            'transactions' => $transactions,
            'todayArrivals' => $todayArrivals, // Pour debug
            'stats' => $stats
        ]);
    }

    /**
     * Calculer le solde d'une transaction
     */
    private function calculateBalance(Transaction $transaction)
    {
        // Protection contre null
        $totalPrice = $transaction->getTotalPrice() ?? 0;
        
        // Calculer le total des paiements (votre modèle Payment utilise 'price' probablement)
        $totalPayment = 0;
        
        // Si la relation payment existe
        if ($transaction->payment) {
            // Votre modèle Payment utilise probablement 'price' et non 'amount'
            $totalPayment = $transaction->payment->sum('price');
        }
        
        return $totalPrice - $totalPayment;
    }

    /**
     * Méthode pour debug (temporaire)
     */
    public function debug()
    {
        // Toutes les transactions d'aujourd'hui
        $today = Carbon::today()->format('Y-m-d');
        
        $transactionsToday = Transaction::with(['customer', 'room'])
            ->whereDate('check_in', $today)
            ->orWhereDate('check_out', $today)
            ->orWhere(function($query) use ($today) {
                $query->where('check_in', '<=', $today)
                      ->where('check_out', '>=', $today);
            })
            ->get();
            
        return response()->json([
            'date_today' => $today,
            'carbon_now' => Carbon::now()->format('Y-m-d H:i:s'),
            'transactions_today' => $transactionsToday->map(function($t) {
                return [
                    'id' => $t->id,
                    'customer' => $t->customer->name ?? 'N/A',
                    'check_in' => $t->check_in,
                    'check_out' => $t->check_out,
                    'check_in_date' => Carbon::parse($t->check_in)->format('Y-m-d'),
                    'check_out_date' => Carbon::parse($t->check_out)->format('Y-m-d'),
                    'is_check_in_today' => Carbon::parse($t->check_in)->isToday(),
                    'is_check_out_today' => Carbon::parse($t->check_out)->isToday(),
                    'is_active_now' => Carbon::parse($t->check_in) <= Carbon::now() && 
                                      Carbon::parse($t->check_out) >= Carbon::now(),
                ];
            })
        ]);
    }
}