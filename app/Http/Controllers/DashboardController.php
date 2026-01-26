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
        // DEBUG: Vérifier ce qu'il y a en base
        \Log::info('=== DASHBOARD DEBUG START ===');
        
        // 1. Récupérer TOUTES les transactions pour debug
        $allTransactions = Transaction::with(['customer.user', 'room.type', 'payments'])
            ->orderBy('check_in', 'asc')
            ->get();
        
        \Log::info('Total transactions in DB: ' . $allTransactions->count());
        foreach ($allTransactions as $t) {
            \Log::info('Transaction ' . $t->id . ': ' . 
                    ($t->customer->name ?? 'N/A') . ' - ' .
                    'Status: ' . $t->status . ' - ' .
                    'Dates: ' . $t->check_in . ' to ' . $t->check_out . ' - ' .
                    'Price: ' . $t->total_price);
        }
        
        // 2. Récupérer les transactions ACTIVES (selon votre logique métier)
        $transactions = Transaction::with(['customer.user', 'room.type', 'payments'])
            ->where(function($query) {
                // Option A: Clients actuellement dans l'hôtel (dates actuelles)
                $query->where([
                    ['check_in', '<=', Carbon::now()],
                    ['check_out', '>=', Carbon::now()],
                ]);
                
                // Option B: OU les réservations futures (statut 'reservation')
                $query->orWhere('status', 'reservation');
                
                // Option C: OU les séjours actifs (statut 'active')
                $query->orWhere('status', 'active');
            })
            ->whereNotIn('status', ['cancelled', 'completed', 'no_show']) // Exclure les statuts terminaux
            ->orderBy('check_in', 'asc')
            ->orderBy('id', 'desc')
            ->get();
        
        \Log::info('Transactions displayed on dashboard: ' . $transactions->count());
        
        // 3. Récupérer les arrivées d'aujourd'hui
        $todayArrivals = Transaction::with(['customer.user', 'room.type', 'payments'])
            ->whereDate('check_in', Carbon::today())
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->get();
        
        // 4. Statistiques
        $stats = [
            'activeGuests' => $transactions->where('status', 'active')->count(),
            'reservations' => $transactions->where('status', 'reservation')->count(),
            'pendingPayments' => 0,
            'urgentPayments' => 0,
            'completedToday' => 0,
            'todayArrivals' => $todayArrivals->count()
        ];
        
        // Calculer les paiements
        foreach ($transactions as $transaction) {
            $balance = $this->calculateBalance($transaction);
            
            if ($balance > 0) {
                $stats['pendingPayments']++;
                
                $checkOut = Carbon::parse($transaction->check_out);
                $now = Carbon::now();
                
                if ($checkOut->diffInHours($now) <= 24) {
                    $stats['urgentPayments']++;
                }
            }
            
            if (Carbon::parse($transaction->check_out)->isToday()) {
                $stats['completedToday']++;
            }
        }
        
        \Log::info('Dashboard stats: ' . json_encode($stats));
        \Log::info('=== DASHBOARD DEBUG END ===');
        
        return view('dashboard.index', [
            'transactions' => $transactions,
            'todayArrivals' => $todayArrivals,
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