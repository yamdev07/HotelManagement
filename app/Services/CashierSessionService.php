<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\CashierSession;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CashierSessionService
{
    public function getActiveSession(User $user): ?CashierSession
    {
        if (method_exists($user, 'activeCashierSession')) {
            return $user->activeCashierSession;
        }

        return CashierSession::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();
    }

    public function canUserStartSession(User $user, ?CashierSession $activeSession): bool
    {
        return ! $activeSession && ($user->roleEnum?->canProcessPayments() ?? false);
    }

    public function isAdmin(User $user): bool
    {
        return in_array($user->roleEnum, [UserRole::Admin, UserRole::Super]);
    }

    public function getTodayStats(User $user): array
    {
        $today         = Carbon::today();
        $activeSession = $this->getActiveSession($user);

        try {
            return [
                'totalBookings'     => Transaction::where('user_id', $user->id)->whereDate('created_at', $today)->count(),
                'checkins'          => Transaction::where('checked_in_by', $user->id)->whereDate('actual_check_in', $today)->where('status', 'active')->count(),
                'checkouts'         => Transaction::where('checked_out_by', $user->id)->whereDate('actual_check_out', $today)->where('status', 'completed')->count(),
                'completedPayments' => Payment::where('user_id', $user->id)->whereDate('created_at', $today)->where('status', Payment::STATUS_COMPLETED)->count(),
                'revenue'           => $activeSession?->current_balance ?? 0,
                'pendingPayments'   => Payment::where('status', Payment::STATUS_PENDING)->count(),
            ];
        } catch (\Throwable $e) {
            Log::error('getTodayStats: '.$e->getMessage());
            return ['totalBookings' => 0, 'checkins' => 0, 'checkouts' => 0, 'completedPayments' => 0, 'revenue' => 0, 'pendingPayments' => 0];
        }
    }

    public function getPendingPayments(User $user)
    {
        $query = Payment::where('status', Payment::STATUS_PENDING)
            ->with(['transaction.booking.customer', 'transaction.booking.room'])
            ->orderByDesc('created_at')
            ->limit(5);

        if (! $this->isAdmin($user)) {
            $query->where('user_id', $user->id);
        }

        return $query->get();
    }

    public function getRecentSessions(User $user)
    {
        $query = CashierSession::with('user')->orderByDesc('created_at')->limit(5);

        if (! $this->isAdmin($user)) {
            $query->where('user_id', $user->id);
        }

        return $query->get();
    }

    public function getAllActiveSessions()
    {
        return CashierSession::with('user')->where('status', 'active')->get();
    }

    public function formatDuration(CashierSession $session): string
    {
        if (! $session->end_time) {
            return 'En cours';
        }

        $minutes          = $session->start_time->diffInMinutes($session->end_time);
        $hours            = (int) floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        return $hours > 0 ? "{$hours}h {$remainingMinutes}min" : "{$remainingMinutes} min";
    }

    public function getReceptionistStats(int $userId): array
    {
        return [
            'totalSessions'       => CashierSession::where('user_id', $userId)->count(),
            'activeSessions'      => CashierSession::where('user_id', $userId)->where('status', 'active')->count(),
            'totalRevenue'        => Payment::where('user_id', $userId)->where('status', Payment::STATUS_COMPLETED)->sum('amount') ?? 0,
            'avgSessionDuration'  => $this->calculateAverageDuration($userId),
        ];
    }

    public function calculateAverageDuration(int $userId): float
    {
        $sessions = CashierSession::where('user_id', $userId)
            ->where('status', 'closed')
            ->whereNotNull('end_time')
            ->get();

        if ($sessions->isEmpty()) {
            return 0;
        }

        $totalMinutes = $sessions->sum(fn ($s) => $s->start_time->diffInMinutes($s->end_time));

        return round($totalMinutes / $sessions->count(), 1);
    }

    public function determineShiftType(Carbon $now): string
    {
        $hour = (int) $now->format('H');

        return match (true) {
            $hour >= 5  && $hour < 12 => 'morning',
            $hour >= 12 && $hour < 17 => 'afternoon',
            $hour >= 17 && $hour < 22 => 'evening',
            default                   => 'night',
        };
    }

    public function closeSession(CashierSession $session, User $user, float $physicalBalance, ?string $closingNotes): CashierSession
    {
        return DB::transaction(function () use ($session, $user, $physicalBalance, $closingNotes) {
            $completedPayments = Payment::where('cashier_session_id', $session->id)->where('status', Payment::STATUS_COMPLETED)->sum('amount') ?? 0;
            $refundedPayments  = Payment::where('cashier_session_id', $session->id)->where('status', Payment::STATUS_REFUNDED)->sum('amount') ?? 0;
            $theoreticalBalance = $session->initial_balance + $completedPayments - $refundedPayments;
            $difference        = $physicalBalance - $theoreticalBalance;
            $endTime           = Carbon::now();

            $notes = ($session->notes ? $session->notes."\n" : '')
                ."Clôturée le {$endTime->format('d/m/Y H:i')} par {$user->name}";

            $session->update([
                'final_balance'       => $physicalBalance,
                'theoretical_balance' => $theoreticalBalance,
                'balance_difference'  => $difference,
                'end_time'            => $endTime,
                'status'              => 'closed',
                'closing_notes'       => $closingNotes,
                'notes'               => $notes,
            ]);

            if (abs($difference) > 0.01) {
                Payment::create([
                    'user_id'            => $user->id,
                    'created_by'         => $user->id,
                    'transaction_id'     => null,
                    'cashier_session_id' => $session->id,
                    'amount'             => abs($difference),
                    'payment_method'     => 'cash',
                    'status'             => Payment::STATUS_COMPLETED,
                    'description'        => $difference > 0 ? 'Excédent à la clôture' : 'Manquant à la clôture',
                    'reference'          => 'ADJ-'.$session->id.'-'.time(),
                ]);
            }

            return $session->fresh();
        });
    }
}
