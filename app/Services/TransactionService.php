<?php

namespace App\Services;

use App\Enums\RoomStatus;
use App\Enums\TransactionStatus;
use App\Enums\UserRole;
use App\Exceptions\TransactionException;
use App\Models\History;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Transaction;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionService
{
    public function __construct(
        private readonly TransactionRepositoryInterface $transactionRepository
    ) {}

    /**
     * Modifier les dates / chambre d'une réservation.
     *
     * @throws TransactionException
     */
    public function update(Transaction $transaction, array $data): Transaction
    {
        if ($this->isTerminal($transaction)) {
            throw TransactionException::cannotModify('réservation terminée, annulée ou no show.');
        }

        $checkIn  = Carbon::parse($data['check_in_date'])->setTime(12, 0);
        $checkOut = Carbon::parse($data['check_out_date'])->setTime(12, 0);
        $roomId   = (int) $data['room_id'];

        if (! $this->isRoomAvailable($roomId, $checkIn, $checkOut, $transaction->id)) {
            throw TransactionException::roomUnavailable();
        }

        return DB::transaction(function () use ($transaction, $data, $checkIn, $checkOut, $roomId) {
            $before = $this->snapshot($transaction);

            $newRoom       = Room::findOrFail($roomId);
            $newNights     = $checkIn->diffInDays($checkOut);
            $newTotalPrice = $newRoom->price * $newNights;
            $changes       = $this->detectChanges($transaction, $roomId, $checkIn, $checkOut);

            $transaction->update([
                'room_id'     => $roomId,
                'check_in'    => $checkIn,
                'check_out'   => $checkOut,
                'total_price' => $newTotalPrice,
                'notes'       => $data['notes'] ?? $transaction->notes,
            ]);

            $transaction->refresh();

            History::create([
                'transaction_id' => $transaction->id,
                'user_id'        => Auth::id(),
                'action'         => 'update',
                'description'    => 'Modification: ' . implode(', ', $changes),
                'old_values'     => json_encode($before),
                'new_values'     => json_encode($this->snapshot($transaction)),
                'notes'          => $data['notes'] ?? null,
            ]);

            Log::info("Transaction #{$transaction->id} modifiée", [
                'by'      => Auth::id(),
                'changes' => $changes,
            ]);

            return $transaction;
        });
    }

    /**
     * Mettre à jour le statut d'une transaction avec toutes les règles métier.
     *
     * @throws TransactionException|\App\Exceptions\ReservationException
     */
    public function updateStatus(Transaction $transaction, string $newStatus, ?string $cancelReason = null): Transaction
    {
        $status = TransactionStatus::from($newStatus);

        $this->assertStatusTransitionAllowed($transaction, $status, $cancelReason);

        return DB::transaction(function () use ($transaction, $status, $cancelReason) {
            $updateData = ['status' => $status->value];

            match ($status) {
                TransactionStatus::Active    => $this->applyCheckIn($transaction, $updateData),
                TransactionStatus::Completed => $this->applyCheckOut($transaction, $updateData),
                TransactionStatus::Cancelled => $this->applyCancel($transaction, $updateData, $cancelReason),
                default                      => null,
            };

            $transaction->update($updateData);

            Log::info("Statut transaction #{$transaction->id} → {$status->value}", [
                'by' => Auth::id(),
            ]);

            return $transaction->refresh();
        });
    }

    /**
     * Annuler une réservation.
     *
     * @throws TransactionException
     */
    public function cancel(Transaction $transaction, string $reason, bool $force = false): Transaction
    {
        if (! $force && ! $transaction->canBeCancelled()) {
            $msg = $transaction->getCannotCancelReason() ?? 'Annulation non autorisée.';
            throw TransactionException::cannotCancel($msg);
        }

        return DB::transaction(function () use ($transaction, $reason) {
            $transaction->update([
                'status'        => TransactionStatus::Cancelled->value,
                'cancelled_at'  => now(),
                'cancelled_by'  => Auth::id(),
                'cancel_reason' => $reason,
            ]);

            $this->releaseRoomIfOccupied($transaction);
            $this->createRefundIfPaid($transaction, $reason);

            Log::info("Transaction #{$transaction->id} annulée", [
                'by'     => Auth::id(),
                'reason' => $reason,
            ]);

            return $transaction->refresh();
        });
    }

    /**
     * Marquer une transaction comme No Show.
     *
     * @throws TransactionException
     */
    public function markAsNoShow(Transaction $transaction, string $reason): Transaction
    {
        if (! $transaction->canBeNoShow()) {
            throw TransactionException::cannotModify('la réservation ne peut pas être marquée No Show.');
        }

        return DB::transaction(function () use ($transaction, $reason) {
            $transaction->update([
                'status'        => TransactionStatus::NoShow->value,
                'cancelled_by'  => Auth::id(),
                'cancel_reason' => $reason ?: 'Client non présenté',
                'cancelled_at'  => now(),
            ]);

            if ($transaction->room) {
                $transaction->room->update(['room_status_id' => RoomStatus::Available->value]);
            }

            Log::info("Transaction #{$transaction->id} marquée No Show", ['by' => Auth::id()]);

            return $transaction->refresh();
        });
    }

    /**
     * Restaurer une transaction annulée ou no_show.
     *
     * @throws TransactionException
     */
    public function restore(Transaction $transaction): Transaction
    {
        if (! $transaction->canBeRestored()) {
            throw TransactionException::cannotModify('seules les transactions annulées ou no show sont restaurables.');
        }

        return DB::transaction(function () use ($transaction) {
            $transaction->update([
                'status'        => TransactionStatus::Reservation->value,
                'cancelled_at'  => null,
                'cancelled_by'  => null,
                'cancel_reason' => null,
            ]);

            Log::info("Transaction #{$transaction->id} restaurée", ['by' => Auth::id()]);

            return $transaction->refresh();
        });
    }

    /**
     * Supprimer définitivement une transaction (Super uniquement).
     *
     * @throws TransactionException
     */
    public function destroy(Transaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {
            $snapshot = $this->snapshot($transaction);

            Payment::where('transaction_id', $transaction->id)->delete();
            $transaction->delete();

            $this->releaseRoomIfOccupied($transaction);

            Log::warning("Transaction #{$transaction->id} supprimée définitivement", [
                'by'       => Auth::id(),
                'snapshot' => $snapshot,
            ]);
        });
    }

    // -----------------------------------------------------------------------
    // Helpers privés
    // -----------------------------------------------------------------------

    private function assertStatusTransitionAllowed(
        Transaction $transaction,
        TransactionStatus $newStatus,
        ?string $cancelReason
    ): void {
        $now         = Carbon::now();
        $checkInDay  = Carbon::parse($transaction->check_in)->startOfDay();
        $checkOutDay = Carbon::parse($transaction->check_out)->startOfDay();

        if ($newStatus === TransactionStatus::Active) {
            if (! $now->isSameDay($checkInDay)) {
                throw \App\Exceptions\ReservationException::wrongDayForCheckIn(
                    $checkInDay->format('d/m/Y')
                );
            }
            $checkInTime = $checkInDay->copy()->setTime(12, 0);
            if ($now->lt($checkInTime)) {
                $diff    = $now->diffInMinutes($checkInTime, false);
                $hours   = (int) floor($diff / 60);
                $minutes = (int) ($diff % 60);
                throw \App\Exceptions\ReservationException::tooEarlyForCheckIn($hours, $minutes);
            }
        }

        if ($newStatus === TransactionStatus::Completed) {
            if (! $now->isSameDay($checkOutDay)) {
                throw \App\Exceptions\ReservationException::wrongDayForCheckOut(
                    $checkOutDay->format('d/m/Y')
                );
            }
            $checkOutDeadline = $checkOutDay->copy()->setTime(12, 0);
            $checkOutLargess  = $checkOutDay->copy()->setTime(14, 0);

            if ($now->gt($checkOutLargess) && ! $transaction->late_checkout) {
                throw \App\Exceptions\ReservationException::lateCheckoutGracePeriodExpired();
            }
            if ($now->lt($checkOutDeadline)) {
                throw TransactionException::cannotCheckOut('check-out possible à partir de 12h.');
            }
            if (! $transaction->isFullyPaid()) {
                $remaining = number_format($transaction->getRemainingPayment(), 0, ',', ' ');
                throw TransactionException::cannotCheckOut("solde restant : {$remaining} CFA.");
            }
        }

        if ($newStatus === TransactionStatus::Cancelled && empty($cancelReason)) {
            throw TransactionException::cannotCancel('une raison d\'annulation est obligatoire.');
        }

        if ($newStatus === TransactionStatus::Reservation) {
            if (Carbon::parse($transaction->check_in)->isPast()) {
                throw TransactionException::cannotModify(
                    'impossible de revenir à "Réservation" : la date d\'arrivée est passée.'
                );
            }
        }
    }

    private function applyCheckIn(Transaction $transaction, array &$updateData): void
    {
        $updateData['check_in_actual'] = now();

        if ($transaction->room) {
            $transaction->room->update(['room_status_id' => RoomStatus::Occupied->value]);
        }
    }

    private function applyCheckOut(Transaction $transaction, array &$updateData): void
    {
        $updateData['check_out_actual'] = now();

        if ($transaction->room) {
            $transaction->room->update([
                'room_status_id' => RoomStatus::Dirty->value,
                'needs_cleaning' => true,
            ]);
        }
    }

    private function applyCancel(Transaction $transaction, array &$updateData, ?string $reason): void
    {
        $updateData['cancelled_at']  = now();
        $updateData['cancelled_by']  = Auth::id();
        $updateData['cancel_reason'] = $reason;

        $this->releaseRoomIfOccupied($transaction);
        $this->createRefundIfPaid($transaction, $reason);
    }

    private function releaseRoomIfOccupied(Transaction $transaction): void
    {
        if ($transaction->room && $transaction->room->room_status_id === RoomStatus::Occupied->value) {
            $transaction->room->update(['room_status_id' => RoomStatus::Available->value]);
        }
    }

    private function createRefundIfPaid(Transaction $transaction, ?string $reason): void
    {
        $totalPaid = $transaction->getTotalPayment();

        if ($totalPaid <= 0) {
            return;
        }

        Payment::create([
            'transaction_id' => $transaction->id,
            'amount'         => -$totalPaid,
            'payment_method' => 'refund',
            'reference'      => 'REFUND-' . $transaction->id . '-' . time(),
            'status'         => 'completed',
            'notes'          => 'Remboursement annulation' . ($reason ? ": {$reason}" : ''),
            'created_by'     => Auth::id(),
        ]);
    }

    private function isRoomAvailable(int $roomId, Carbon $checkIn, Carbon $checkOut, ?int $excludeId = null): bool
    {
        return Transaction::isRoomAvailableForPeriod($roomId, $checkIn, $checkOut, $excludeId);
    }

    private function isTerminal(Transaction $transaction): bool
    {
        return in_array($transaction->status, TransactionStatus::terminalValues());
    }

    private function detectChanges(Transaction $transaction, int $newRoomId, Carbon $newCheckIn, Carbon $newCheckOut): array
    {
        $changes = [];

        if ($transaction->room_id !== $newRoomId) {
            $newRoom   = Room::find($newRoomId);
            $changes[] = "chambre: {$transaction->room->number} → {$newRoom->number}";
        }
        if ($transaction->check_in->format('Y-m-d') !== $newCheckIn->format('Y-m-d')) {
            $changes[] = "arrivée: {$transaction->check_in->format('d/m/Y')} → {$newCheckIn->format('d/m/Y')}";
        }
        if ($transaction->check_out->format('Y-m-d') !== $newCheckOut->format('Y-m-d')) {
            $changes[] = "départ: {$transaction->check_out->format('d/m/Y')} → {$newCheckOut->format('d/m/Y')}";
        }

        return $changes ?: ['aucun changement'];
    }

    private function snapshot(Transaction $transaction): array
    {
        return [
            'room_id'     => $transaction->room_id,
            'room_number' => optional($transaction->room)->number,
            'check_in'    => $transaction->check_in?->format('Y-m-d H:i:s'),
            'check_out'   => $transaction->check_out?->format('Y-m-d H:i:s'),
            'total_price' => $transaction->total_price,
            'status'      => $transaction->status,
            'notes'       => $transaction->notes,
        ];
    }
}
