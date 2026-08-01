<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Room;
use App\Models\Transaction;
use Carbon\Carbon;

/**
 * Tableau de bord des revenus (pilotage financier de l'établissement).
 * Réservé à l'Admin/Direction (voir la route). Toutes les données sont scopées
 * à l'hôtel courant : Payment/Transaction/Room portent le trait BelongsToHotel.
 */
class RevenueController extends Controller
{
    public function index()
    {
        $hotel = auth()->user()->hotel;
        abort_unless($hotel, 403);

        $currency = $hotel->displayCurrency();
        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();
        $now = Carbon::now();

        $completed = fn () => Payment::where('status', Payment::STATUS_COMPLETED);

        // ── Revenus (paiements encaissés) ──
        $revToday = (float) $completed()->whereDate('payment_date', $today)->sum('amount');
        $revMonth = (float) $completed()->whereBetween('payment_date', [$monthStart, $now])->sum('amount');
        $revTotal = (float) $completed()->sum('amount');

        // ── Occupation du jour ──
        $totalRooms = Room::count();
        $occupiedToday = Transaction::whereIn('status', ['active', 'pending_checkout'])
            ->whereDate('check_in', '<=', $today)
            ->whereDate('check_out', '>=', $today)
            ->distinct('room_id')->count('room_id');
        $occupancy = $totalRooms > 0 ? round($occupiedToday / $totalRooms * 100) : 0;

        // ── RevPAR du mois (revenu / nombre de chambres) ──
        $revpar = $totalRooms > 0 ? round($revMonth / $totalRooms) : 0;

        // ── Répartition des moyens de paiement (ce mois) ──
        $mixRaw = $completed()
            ->whereBetween('payment_date', [$monthStart, $now])
            ->selectRaw('payment_method, SUM(amount) as total')
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method');

        $methods = [
            'cash' => ['label' => 'Espèces', 'icon' => 'fa-money-bill-wave', 'color' => '#16a34a'],
            'mobile_money' => ['label' => 'Mobile Money', 'icon' => 'fa-mobile-screen', 'color' => '#f59e0b'],
            'fedapay' => ['label' => 'FedaPay', 'icon' => 'fa-globe', 'color' => '#7c3aed'],
            'card' => ['label' => 'Carte', 'icon' => 'fa-credit-card', 'color' => '#2563eb'],
            'transfer' => ['label' => 'Virement', 'icon' => 'fa-building-columns', 'color' => '#0891b2'],
            'check' => ['label' => 'Chèque', 'icon' => 'fa-money-check', 'color' => '#64748b'],
        ];
        $mixTotal = max(1, (float) $mixRaw->sum());
        $mix = collect($methods)->map(fn ($m, $key) => [
            'label' => $m['label'], 'icon' => $m['icon'], 'color' => $m['color'],
            'amount' => (float) ($mixRaw[$key] ?? 0),
            'pct' => round(((float) ($mixRaw[$key] ?? 0)) / $mixTotal * 100),
        ])->filter(fn ($m) => $m['amount'] > 0)->sortByDesc('amount')->values();

        // ── Revenus des 14 derniers jours (mini-graphe) ──
        $from = Carbon::today()->subDays(13);
        $dailyRaw = $completed()
            ->whereBetween('payment_date', [$from->copy()->startOfDay(), $now])
            ->selectRaw('DATE(payment_date) as d, SUM(amount) as total')
            ->groupBy('d')->pluck('total', 'd');

        $daily = [];
        for ($i = 0; $i < 14; $i++) {
            $d = $from->copy()->addDays($i);
            $daily[] = [
                'label' => $d->format('d/m'),
                'day' => $d->translatedFormat('D'),
                'amount' => (float) ($dailyRaw[$d->format('Y-m-d')] ?? 0),
            ];
        }
        $dailyMax = max(1, collect($daily)->max('amount'));

        // ── Top chambres par revenu (ce mois) ──
        $topRooms = Payment::where('payments.status', Payment::STATUS_COMPLETED)
            ->whereBetween('payment_date', [$monthStart, $now])
            ->join('transactions', 'transactions.id', '=', 'payments.transaction_id')
            ->join('rooms', 'rooms.id', '=', 'transactions.room_id')
            ->groupBy('rooms.id', 'rooms.number')
            ->selectRaw('rooms.number as number, SUM(payments.amount) as total')
            ->orderByDesc('total')->limit(5)->get();

        // ── Activité du mois ──
        $reservationsMonth = Transaction::whereBetween('created_at', [$monthStart, $now])->count();
        $checkinsMonth = Transaction::whereIn('status', ['active', 'pending_checkout', 'completed'])
            ->whereBetween('check_in', [$monthStart, $now])->count();

        return view('revenue.index', compact(
            'currency', 'revToday', 'revMonth', 'revTotal', 'occupancy', 'occupiedToday',
            'totalRooms', 'revpar', 'mix', 'daily', 'dailyMax', 'topRooms',
            'reservationsMonth', 'checkinsMonth'
        ));
    }
}
