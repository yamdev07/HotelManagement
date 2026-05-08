<?php

namespace App\Services;

use App\Enums\RoomStatus;
use App\Models\Room;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardService
{
    public function getStats(string $dateFilter, ?string $statusFilter): array
    {
        $today = Carbon::today();
        $now   = Carbon::now();

        $baseQuery = Transaction::with([
            'customer',
            'room.type',
            'room.roomStatus',
            'payments' => fn ($q) => $q->where('status', 'completed'),
        ]);

        $this->applyDateFilter($baseQuery, $dateFilter, $today);

        if ($statusFilter) {
            $baseQuery->where('status', $statusFilter);
        } else {
            $baseQuery->whereNotIn('status', ['cancelled', 'no_show']);
        }

        $transactions = (clone $baseQuery)
            ->whereIn('status', ['active'])
            ->whereDate('check_in', '<=', $today)
            ->whereDate('check_out', '>=', $today)
            ->orderBy('check_out')
            ->get();

        $pendingPaymentTransactions = $transactions->filter(fn ($t) => $this->calculateBalance($t) > 0);

        $totalRooms    = Room::count();
        $occupiedRooms = Transaction::where('status', 'active')
            ->whereDate('check_in', '<=', $today)
            ->whereDate('check_out', '>=', $today)
            ->distinct('room_id')->count('room_id');

        return [
            'activeGuests'     => $transactions->count(),
            'completedToday'   => $transactions->filter(fn ($t) => Carbon::parse($t->check_out)->isSameDay($today) && $this->calculateBalance($t) <= 0)->count(),
            'pendingPayments'  => $pendingPaymentTransactions->count(),
            'urgentPayments'   => $pendingPaymentTransactions->filter(function ($t) use ($now) {
                $hours = Carbon::parse($t->check_out)->diffInHours($now, false);
                return $hours <= 24 && $hours > 0;
            })->count(),
            'todayArrivals'    => Transaction::whereDate('check_in', $today)->whereIn('status', ['reservation', 'active'])->count(),
            'todayDepartures'  => Transaction::whereDate('check_out', $today)->where('status', 'active')->count(),
            'availableRooms'   => Room::where('room_status_id', RoomStatus::Available->value)->count(),
            'occupiedRooms'    => $occupiedRooms,
            'totalRooms'       => $totalRooms,
            'occupancyRate'    => $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 2) : 0,
            'dateFilter'       => $dateFilter,
            'statusFilter'     => $statusFilter,
        ];
    }

    public function getTransactions(string $dateFilter, ?string $statusFilter)
    {
        $today = Carbon::today();

        $query = Transaction::with([
            'customer',
            'room.type',
            'room.roomStatus',
            'payments' => fn ($q) => $q->where('status', 'completed'),
        ]);

        $this->applyDateFilter($query, $dateFilter, $today);

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        } else {
            $query->whereNotIn('status', ['cancelled', 'no_show']);
        }

        return $query
            ->whereIn('status', ['active'])
            ->whereDate('check_in', '<=', $today)
            ->whereDate('check_out', '>=', $today)
            ->orderBy('check_out')
            ->get();
    }

    public function getCheckinDashboardData(): array
    {
        $today            = Carbon::today();
        $tomorrow         = $today->copy()->addDay();
        $dayAfterTomorrow = $today->copy()->addDays(2);

        $upcomingReservations = Transaction::with(['customer', 'room.type', 'room.roomStatus'])
            ->where('status', 'reservation')
            ->whereDate('check_in', '>=', $today)
            ->whereDate('check_in', '<=', $dayAfterTomorrow)
            ->orderBy('check_in')
            ->get()
            ->groupBy(fn ($t) => Carbon::parse($t->check_in)->format('Y-m-d'));

        $activeGuests   = Transaction::with(['customer', 'room.type', 'payments'])->where('status', 'active')->orderBy('check_in')->get();
        $todayDepartures = Transaction::with(['customer', 'room.type'])->where('status', 'active')->whereDate('check_out', $today)->orderBy('check_out')->get();

        $totalRooms    = Room::count();
        $occupiedRooms = Transaction::where('status', 'active')
            ->where('check_in', '<=', now())->where('check_out', '>=', now())
            ->distinct('room_id')->count('room_id');

        $stats = [
            'arrivals_today'      => $upcomingReservations->get($today->format('Y-m-d'), collect())->count(),
            'arrivals_tomorrow'   => $upcomingReservations->get($tomorrow->format('Y-m-d'), collect())->count(),
            'departures_today'    => $todayDepartures->count(),
            'currently_checked_in' => $activeGuests->count(),
            'available_rooms'     => Room::where('room_status_id', RoomStatus::Available->value)->count(),
            'occupancy_rate'      => $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 2) : 0,
        ];

        return compact('upcomingReservations', 'activeGuests', 'todayDepartures', 'stats', 'today');
    }

    public function calculateBalance(Transaction $transaction): float
    {
        $totalPrice   = $transaction->total_price ?? 0;
        if (method_exists($transaction, 'getTotalPrice')) {
            $calc = $transaction->getTotalPrice();
            if ($calc > 0) {
                $totalPrice = $calc;
            }
        }
        $totalPayment = $transaction->payments?->sum('amount') ?? 0;
        return (float) ($totalPrice - $totalPayment);
    }

    private function applyDateFilter($query, string $dateFilter, Carbon $today): void
    {
        match ($dateFilter) {
            'today'     => $query->whereDate('check_in', '<=', $today)->whereDate('check_out', '>=', $today),
            'tomorrow'  => $query->whereDate('check_in', '<=', $today->copy()->addDay())->whereDate('check_out', '>=', $today->copy()->addDay()),
            'this_week' => $query->where(function ($q) use ($today) {
                $start = $today->copy()->startOfWeek();
                $end   = $today->copy()->endOfWeek();
                $q->whereBetween('check_in', [$start, $end])
                    ->orWhereBetween('check_out', [$start, $end])
                    ->orWhere(fn ($qq) => $qq->where('check_in', '<=', $start)->where('check_out', '>=', $end));
            }),
            default => null,
        };
    }
}
