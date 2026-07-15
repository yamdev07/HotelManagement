<?php

namespace App\Http\Controllers;

use App\Enums\RoomStatus;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Transaction;
use App\Services\DashboardService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboard) {}

    public function index(Request $request)
    {
        $dateFilter   = $request->get('date_filter', 'today');
        $statusFilter = $request->get('status');

        $transactions = $this->dashboard->getTransactions($dateFilter, $statusFilter);
        $stats        = $this->dashboard->getStats($dateFilter, $statusFilter);

        return view('dashboard.index', compact('transactions', 'stats'));
    }

    public function getDashboardData(Request $request)
    {
        try {
            $date            = $request->get('date', date('Y-m-d'));
            $selectedDate    = Carbon::parse($date);

            $transactions = Transaction::with(['customer', 'room.type', 'payments' => fn ($q) => $q->where('status', 'completed')])
                ->where(function ($q) use ($selectedDate) {
                    $q->whereDate('check_in', '<=', $selectedDate)->whereDate('check_out', '>=', $selectedDate);
                })
                ->whereNotIn('status', ['cancelled', 'no_show'])
                ->orderBy('check_in')
                ->get()
                ->map(function ($t) {
                    $balance = $this->dashboard->calculateBalance($t);
                    return [
                        'id'                    => $t->id,
                        'customer_name'         => $t->customer->name ?? 'N/A',
                        'customer_phone'        => $t->customer->phone ?? 'N/A',
                        'room_number'           => $t->room->number ?? 'N/A',
                        'room_type'             => $t->room->type->name ?? 'Standard',
                        'check_in'              => $t->check_in->format('d/m/Y'),
                        'check_out'             => $t->check_out->format('d/m/Y'),
                        'is_checking_out_today' => Carbon::parse($t->check_out)->isToday(),
                        'is_new_arrival'        => Carbon::parse($t->check_in)->isToday(),
                        'total_price'           => $t->getTotalPrice(),
                        'total_payment'         => $t->getTotalPayment(),
                        'balance'               => $balance,
                        'balance_formatted'     => number_format($balance, 0, ',', ' ').' CFA',
                        'is_paid'               => $t->isFullyPaid(),
                        'payment_url'           => url("/transaction/{$t->id}/payment/create"),
                        'edit_url'              => url("/transaction/{$t->id}/edit"),
                        'invoice_url'           => url("/transaction/{$t->id}/invoice"),
                        'show_url'              => url("/transaction/{$t->id}"),
                    ];
                });

            $stats = [
                'total_guests'      => $transactions->count(),
                'pending_payments'  => $transactions->where('balance', '>', 0)->count(),
                'arrivals_today'    => $transactions->where('is_new_arrival', true)->count(),
                'departures_today'  => $transactions->where('is_checking_out_today', true)->count(),
                'date'              => $selectedDate->format('d/m/Y'),
            ];

            return response()->json(['success' => true, 'data' => compact('transactions', 'stats')]);
        } catch (\Throwable $e) {
            Log::error('Dashboard API error: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error fetching dashboard data'], 500);
        }
    }

    public function checkinDashboard()
    {
        $data = $this->dashboard->getCheckinDashboardData();
        return view('checkin.dashboard', $data);
    }

    public function updateStats()
    {
        try {
            $today = Carbon::today();
            $now   = Carbon::now();

            return response()->json([
                'success' => true,
                'stats'   => [
                    'activeGuests'    => Transaction::where('status', 'active')->where('check_in', '<=', $now)->where('check_out', '>=', $now)->count(),
                    'todayArrivals'   => Transaction::whereDate('check_in', $today)->whereIn('status', ['reservation', 'active'])->count(),
                    'todayDepartures' => Transaction::whereDate('check_out', $today)->where('status', 'active')->count(),
                    'availableRooms'  => Room::where('room_status_id', RoomStatus::Available->value)->count(),
                    'updated_at'      => now()->format('H:i:s'),
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function debug(Request $request)
    {
        $date         = $request->get('date', date('Y-m-d'));
        $selectedDate = Carbon::parse($date);

        return response()->json([
            'debug_info'       => [
                'current_date'  => date('Y-m-d H:i:s'),
                'selected_date' => $selectedDate->format('Y-m-d'),
                'timezone'      => config('app.timezone'),
            ],
            'database_counts'  => [
                'transactions_total'       => Transaction::count(),
                'transactions_active'      => Transaction::where('status', 'active')->count(),
                'transactions_reservation' => Transaction::where('status', 'reservation')->count(),
                'payments_total'           => Payment::count(),
                'customers_total'          => Customer::count(),
                'rooms_total'              => Room::count(),
                'rooms_available'          => Room::where('room_status_id', RoomStatus::Available->value)->count(),
            ],
            'today_transactions' => Transaction::whereDate('check_in', $selectedDate)
                ->orWhereDate('check_out', $selectedDate)
                ->get()
                ->map(fn ($t) => [
                    'id'             => $t->id,
                    'customer'       => $t->customer->name ?? 'N/A',
                    'status'         => $t->status,
                    'check_in'       => $t->check_in,
                    'check_out'      => $t->check_out,
                    'total_price'    => $t->total_price,
                    'payments_count' => $t->payments()->count(),
                    'payments_total' => $t->payments()->sum('amount'),
                ]),
        ]);
    }
}
