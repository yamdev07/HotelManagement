<?php

namespace App\Services;

use App\Enums\RoomStatus;
use App\Enums\TransactionStatus;
use App\Exceptions\ReservationException;
use App\Exceptions\TransactionException;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckInService
{
    /**
     * Effectuer le check-in d'un client.
     *
     * @throws TransactionException|ReservationException
     */
    public function checkIn(Transaction $transaction, array $data = []): Transaction
    {
        if (! $transaction->canBeCheckedIn()) {
            throw TransactionException::cannotCheckIn(
                "statut actuel : {$transaction->status_label}."
            );
        }

        $this->assertCheckInTime($transaction);

        return DB::transaction(function () use ($transaction, $data) {
            $updateData = [
                'status'           => TransactionStatus::Active->value,
                'actual_check_in'  => now(),
                'special_requests' => $data['special_requests'] ?? $transaction->special_requests,
                'id_type'          => $data['id_type'] ?? $transaction->id_type,
                'id_number'        => $data['id_number'] ?? $transaction->id_number,
                'nationality'      => $data['nationality'] ?? $transaction->nationality,
                'person_count'     => $data['person_count'] ?? $transaction->person_count ?? 1,
            ];

            if (! empty($data['new_room_id']) && (int) $data['new_room_id'] !== $transaction->room_id) {
                $updateData['room_id'] = (int) $data['new_room_id'];
            }

            $transaction->update($updateData);
            $transaction->refresh();

            if ($transaction->room) {
                $transaction->room->update(['room_status_id' => RoomStatus::Occupied->value]);
            }

            activity()
                ->causedBy(Auth::user())
                ->performedOn($transaction)
                ->withProperties([
                    'check_in_time' => now()->toDateTimeString(),
                    'room'          => optional($transaction->room)->number,
                    'person_count'  => $transaction->person_count,
                ])
                ->log('a effectué le check-in');

            Log::info("Check-in transaction #{$transaction->id}", [
                'by'   => Auth::id(),
                'room' => optional($transaction->room)->number,
            ]);

            return $transaction;
        });
    }

    /**
     * Effectuer le check-out d'un client.
     *
     * @throws TransactionException|ReservationException
     */
    public function checkOut(Transaction $transaction): Transaction
    {
        if (! $transaction->isActive()) {
            throw TransactionException::cannotCheckOut(
                "le client n'est pas enregistré dans l'hôtel."
            );
        }

        if (! $transaction->isFullyPaid()) {
            $remaining = number_format($transaction->getRemainingPayment(), 0, ',', ' ');
            throw TransactionException::cannotCheckOut("solde restant : {$remaining} CFA.");
        }

        $this->assertCheckOutTime($transaction);

        return DB::transaction(function () use ($transaction) {
            $transaction->update([
                'status'           => TransactionStatus::Completed->value,
                'actual_check_out' => now(),
            ]);

            if ($transaction->room) {
                $transaction->room->update([
                    'room_status_id' => RoomStatus::Dirty->value,
                    'needs_cleaning' => true,
                ]);
            }

            activity()
                ->causedBy(Auth::user())
                ->performedOn($transaction)
                ->withProperties([
                    'check_out_time' => now()->toDateTimeString(),
                    'total_paid'     => $transaction->getTotalPayment(),
                ])
                ->log('a effectué le check-out');

            Log::info("Check-out transaction #{$transaction->id}", [
                'by'   => Auth::id(),
                'room' => optional($transaction->room)->number,
            ]);

            return $transaction->refresh();
        });
    }

    // -----------------------------------------------------------------------
    // Helpers privés
    // -----------------------------------------------------------------------

    private function assertCheckInTime(Transaction $transaction): void
    {
        $now        = Carbon::now();
        $checkInDay = Carbon::parse($transaction->check_in)->startOfDay();

        if (! $now->isSameDay($checkInDay)) {
            throw ReservationException::wrongDayForCheckIn($checkInDay->format('d/m/Y'));
        }

        $checkInTime = $checkInDay->copy()->setTime(12, 0);

        if ($now->lt($checkInTime)) {
            $diff    = (int) $now->diffInMinutes($checkInTime, false);
            $hours   = (int) floor($diff / 60);
            $minutes = (int) ($diff % 60);
            throw ReservationException::tooEarlyForCheckIn($hours, $minutes);
        }
    }

    private function assertCheckOutTime(Transaction $transaction): void
    {
        $now         = Carbon::now();
        $checkOutDay = Carbon::parse($transaction->check_out)->startOfDay();

        if (! $now->isSameDay($checkOutDay)) {
            throw ReservationException::wrongDayForCheckOut($checkOutDay->format('d/m/Y'));
        }

        $deadline = $checkOutDay->copy()->setTime(12, 0);
        $largess  = $checkOutDay->copy()->setTime(14, 0);

        if ($now->lt($deadline)) {
            throw TransactionException::cannotCheckOut('check-out possible à partir de 12h.');
        }

        if ($now->gt($largess) && ! $transaction->late_checkout) {
            throw ReservationException::lateCheckoutGracePeriodExpired();
        }
    }
}
