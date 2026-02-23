<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(Request $request)
{
    $dateFilter = $request->get('date_filter', 'today');
    $statusFilter = $request->get('status', null);

    $today = Carbon::today();
    $now = Carbon::now();

    $query = Transaction::with([
        'customer',
        'room.type',
        'room.roomStatus',
        'payments' => function ($q) {
            $q->where('status', 'completed');
        },
    ]);

    // --- FILTRE DATE (version sûre date+heure) ---
    switch ($dateFilter) {
        case 'today':
            $query->whereDate('check_in', '<=', $today)
                  ->whereDate('check_out', '>=', $today);
            break;

        case 'tomorrow':
            $tomorrow = $today->copy()->addDay();
            $query->whereDate('check_in', '<=', $tomorrow)
                  ->whereDate('check_out', '>=', $tomorrow);
            break;

        case 'this_week':
            $weekStart = $today->copy()->startOfWeek();
            $weekEnd = $today->copy()->endOfWeek();
            $query->where(function ($q) use ($weekStart, $weekEnd) {
                $q->whereBetween('check_in', [$weekStart, $weekEnd])
                  ->orWhereBetween('check_out', [$weekStart, $weekEnd])
                  ->orWhere(function ($qq) use ($weekStart, $weekEnd) {
                      $qq->where('check_in', '<=', $weekStart)
                         ->where('check_out', '>=', $weekEnd);
                  });
            });
            break;

        case 'all':
        default:
            // rien
            break;
    }

    // --- FILTRE STATUT ---
    if ($statusFilter) {
        $query->where('status', $statusFilter);
    } else {
        $query->whereNotIn('status', ['cancelled', 'no_show']);
    }

    // ✅ Clients dans l’hôtel = pas check-out (au sens temps réel)
    // IMPORTANT: on évite les bugs d'heure en passant par whereDate
    $inHotelQuery = (clone $query)
        ->whereIn('status', ['active']) // si tu veux inclure aussi 'reservation', ajoute-le ici
        ->whereDate('check_in', '<=', $today)
        ->whereDate('check_out', '>=', $today);

    $transactions = $inHotelQuery
        ->orderBy('check_out', 'asc')
        ->get();

    // --- STATS basées sur la même logique ---
    $activeTransactions = $transactions->count();

    $pendingPaymentTransactions = $transactions->filter(function ($transaction) {
        return $this->calculateBalance($transaction) > 0;
    });

    $pendingPaymentsCount = $pendingPaymentTransactions->count();

    $urgentPaymentsCount = $pendingPaymentTransactions->filter(function ($transaction) use ($now) {
        $checkOut = Carbon::parse($transaction->check_out);
        $hoursLeft = $checkOut->diffInHours($now, false);
        return $hoursLeft <= 24 && $hoursLeft > 0;
    })->count();

    $completedTodayCount = $transactions->filter(function ($transaction) use ($today) {
        $isDepartingToday = Carbon::parse($transaction->check_out)->isSameDay($today);
        return $isDepartingToday && $this->calculateBalance($transaction) <= 0;
    })->count();

    $todayArrivalsCount = Transaction::whereDate('check_in', $today)
        ->whereIn('status', ['reservation', 'active'])
        ->whereNotIn('status', ['cancelled', 'no_show'])
        ->count();

    $todayDeparturesCount = Transaction::whereDate('check_out', $today)
        ->where('status', 'active')
        ->count();

    $availableRoomsCount = Room::where('room_status_id', 1)->count();
    $totalRoomsCount = Room::count();

    $occupiedRoomsCount = Transaction::where('status', 'active')
        ->whereDate('check_in', '<=', $today)
        ->whereDate('check_out', '>=', $today)
        ->distinct('room_id')
        ->count('room_id');

    $occupancyRate = $totalRoomsCount > 0
        ? round(($occupiedRoomsCount / $totalRoomsCount) * 100, 2)
        : 0;

    $stats = [
        'activeGuests' => $activeTransactions,
        'completedToday' => $completedTodayCount,
        'pendingPayments' => $pendingPaymentsCount,
        'urgentPayments' => $urgentPaymentsCount,

        'todayArrivals' => $todayArrivalsCount,
        'todayDepartures' => $todayDeparturesCount,

        'availableRooms' => $availableRoomsCount,
        'occupiedRooms' => $occupiedRoomsCount,
        'totalRooms' => $totalRoomsCount,
        'occupancyRate' => $occupancyRate,

        'dateFilter' => $dateFilter,
        'statusFilter' => $statusFilter,
    ];

    return view('dashboard.index', compact('transactions', 'stats'));
}

    /**
     * Calculer le solde d'une transaction
     */
    private function calculateBalance(Transaction $transaction)
    {
        try {
            // Prix total de la transaction
            $totalPrice = $transaction->total_price ?? 0;

            // Vérifier si getTotalPrice existe
            if (method_exists($transaction, 'getTotalPrice')) {
                $calculatedPrice = $transaction->getTotalPrice();
                if ($calculatedPrice > 0) {
                    $totalPrice = $calculatedPrice;
                }
            }

            Log::debug("Transaction #{$transaction->id} - Total price: {$totalPrice}");

            // Total des paiements complétés
            $totalPayment = 0;

            if ($transaction->payments && $transaction->payments->count() > 0) {
                $totalPayment = $transaction->payments->sum('amount');
                Log::debug("Transaction #{$transaction->id} - Total payment: {$totalPayment}");
            }

            $balance = $totalPrice - $totalPayment;
            Log::debug("Transaction #{$transaction->id} - Balance: {$balance}");

            return $balance;

        } catch (\Exception $e) {
            Log::error("Error calculating balance for transaction #{$transaction->id}: ".$e->getMessage());

            return 0;
        }
    }

    /**
     * API pour les données du dashboard
     */
    public function getDashboardData(Request $request)
    {
        try {
            $date = $request->get('date', date('Y-m-d'));
            $selectedDate = Carbon::parse($date);

            // Transactions du jour
            $transactions = Transaction::with(['customer', 'room.type', 'payments' => function ($q) {
                $q->where('status', 'completed');
            }])
                ->where(function ($query) use ($selectedDate) {
                    $query->whereDate('check_in', '<=', $selectedDate)
                        ->whereDate('check_out', '>=', $selectedDate);
                })
                ->whereNotIn('status', ['cancelled', 'no_show'])
                ->orderBy('check_in', 'asc')
                ->get()
                ->map(function ($transaction) {
                    $balance = $this->calculateBalance($transaction);

                    return [
                        'id' => $transaction->id,
                        'customer_name' => $transaction->customer->name ?? 'N/A',
                        'customer_phone' => $transaction->customer->phone ?? 'N/A',
                        'room_number' => $transaction->room->number ?? 'N/A',
                        'room_type' => $transaction->room->type->name ?? 'Standard',
                        'check_in' => $transaction->check_in->format('d/m/Y'),
                        'check_out' => $transaction->check_out->format('d/m/Y'),
                        'is_checking_out_today' => Carbon::parse($transaction->check_out)->isToday(),
                        'is_new_arrival' => Carbon::parse($transaction->check_in)->isToday(),
                        'total_price' => $transaction->total_price,
                        'total_payment' => $transaction->payments->sum('amount'),
                        'balance' => $balance,
                        'balance_formatted' => number_format($balance, 0, ',', ' ').' CFA',
                        'is_paid' => $balance <= 0,
                        'payment_url' => route('transaction.payment.create', $transaction),
                        'edit_url' => route('transaction.edit', $transaction),
                    ];
                });

            // Statistiques
            $stats = [
                'total_guests' => $transactions->count(),
                'pending_payments' => $transactions->where('balance', '>', 0)->count(),
                'arrivals_today' => $transactions->where('is_new_arrival', true)->count(),
                'departures_today' => $transactions->where('is_checking_out_today', true)->count(),
                'date' => $selectedDate->format('d/m/Y'),
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'transactions' => $transactions,
                    'stats' => $stats,
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Dashboard API error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error fetching dashboard data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Méthode pour debug
     */
    public function debug(Request $request)
    {
        $date = $request->get('date', date('Y-m-d'));
        $selectedDate = Carbon::parse($date);

        $data = [
            'debug_info' => [
                'current_date' => date('Y-m-d H:i:s'),
                'selected_date' => $selectedDate->format('Y-m-d'),
                'timezone' => config('app.timezone'),
            ],
            'database_counts' => [
                'transactions_total' => Transaction::count(),
                'transactions_active' => Transaction::where('status', 'active')->count(),
                'transactions_reservation' => Transaction::where('status', 'reservation')->count(),
                'payments_total' => Payment::count(),
                'customers_total' => Customer::count(),
                'rooms_total' => Room::count(),
                'rooms_available' => Room::where('room_status_id', 1)->count(),
            ],
            'today_transactions' => Transaction::whereDate('check_in', $selectedDate)
                ->orWhereDate('check_out', $selectedDate)
                ->get()
                ->map(function ($t) {
                    return [
                        'id' => $t->id,
                        'customer' => $t->customer->name ?? 'N/A',
                        'status' => $t->status,
                        'check_in' => $t->check_in,
                        'check_out' => $t->check_out,
                        'total_price' => $t->total_price,
                        'payments_count' => $t->payments()->count(),
                        'payments_total' => $t->payments()->sum('amount'),
                    ];
                }),
        ];

        return response()->json($data);
    }

    /**
     * Mettre à jour les statistiques en temps réel
     */
    public function updateStats()
    {
        try {
            $today = Carbon::today();

            // Calcul rapide des statistiques
            $stats = [
                'activeGuests' => Transaction::where('status', 'active')
                    ->where('check_in', '<=', Carbon::now())
                    ->where('check_out', '>=', Carbon::now())
                    ->count(),

                'todayArrivals' => Transaction::whereDate('check_in', $today)
                    ->whereIn('status', ['reservation', 'active'])
                    ->count(),

                'todayDepartures' => Transaction::whereDate('check_out', $today)
                    ->where('status', 'active')
                    ->count(),

                'availableRooms' => Room::where('room_status_id', 1)->count(),

                'updated_at' => now()->format('H:i:s'),
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Dashboard pour les réservations (check-in)
     */
    public function checkinDashboard(Request $request)
    {
        $today = Carbon::today();
        $tomorrow = $today->copy()->addDay();
        $dayAfterTomorrow = $today->copy()->addDays(2);

        // Réservations à venir (aujourd'hui, demain, après-demain)
        $upcomingReservations = Transaction::with(['customer', 'room.type', 'room.roomStatus'])
            ->where('status', 'reservation')
            ->whereDate('check_in', '>=', $today)
            ->whereDate('check_in', '<=', $dayAfterTomorrow)
            ->orderBy('check_in')
            ->get()
            ->groupBy(function ($transaction) {
                return Carbon::parse($transaction->check_in)->format('Y-m-d');
            });

        // Clients actuellement dans l'hôtel
        $activeGuests = Transaction::with(['customer', 'room.type', 'payments'])
            ->where('status', 'active')
            ->orderBy('check_in')
            ->get();

        // Départs du jour
        $todayDepartures = Transaction::with(['customer', 'room.type'])
            ->where('status', 'active')
            ->whereDate('check_out', $today)
            ->orderBy('check_out')
            ->get();

        // Statistiques
        $stats = [
            'arrivals_today' => $upcomingReservations->get($today->format('Y-m-d'), collect())->count(),
            'arrivals_tomorrow' => $upcomingReservations->get($tomorrow->format('Y-m-d'), collect())->count(),
            'departures_today' => $todayDepartures->count(),
            'currently_checked_in' => $activeGuests->count(),
            'available_rooms' => Room::where('room_status_id', 1)->count(),
            'occupancy_rate' => $this->calculateOccupancyRate(),
        ];

        return view('checkin.dashboard', compact(
            'upcomingReservations',
            'activeGuests',
            'todayDepartures',
            'stats',
            'today'
        ));
    }

    /**
     * Calculer le taux d'occupation
     */
    private function calculateOccupancyRate()
    {
        $totalRooms = Room::count();
        $occupiedRooms = Transaction::where('status', 'active')
            ->where('check_in', '<=', Carbon::now())
            ->where('check_out', '>=', Carbon::now())
            ->distinct('room_id')
            ->count('room_id');

        return $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 2) : 0;
    }

    /**
     * Calculer l'occupation par type de chambre
     */
    private function getOccupancyByType()
    {
        $roomTypes = \App\Models\Type::with('rooms')->get();
        $occupancyByType = [];
        $now = Carbon::now();
        
        foreach ($roomTypes as $type) {
            $totalRooms = $type->rooms->count();
            if ($totalRooms == 0) continue;
            
            $occupiedRooms = Transaction::where('status', 'active')
                ->whereHas('room', function ($q) use ($type) {
                    $q->where('type_id', $type->id);
                })
                ->where('check_in', '<=', $now)
                ->where('check_out', '>=', $now)
                ->distinct('room_id')
                ->count('room_id');
            
            $percentage = round(($occupiedRooms / $totalRooms) * 100, 2);
            
            $occupancyByType[] = [
                'type' => $type->name,
                'total' => $totalRooms,
                'occupied' => $occupiedRooms,
                'percentage' => $percentage,
            ];
        }
        
        return $occupancyByType;
    }
}
