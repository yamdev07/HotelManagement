<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
{
    public function viewAny(User $user): bool
    {
        return UserRole::from($user->role)->isStaff();
    }

    public function view(User $user, Transaction $transaction): bool
    {
        if (UserRole::from($user->role)->isStaff()) {
            return true;
        }

        // Un client voit uniquement ses propres réservations
        return $user->customer?->id === $transaction->customer_id;
    }

    public function create(User $user): bool
    {
        return UserRole::from($user->role)->canManageReservations();
    }

    public function update(User $user, Transaction $transaction): bool
    {
        if (! UserRole::from($user->role)->canManageReservations()) {
            return false;
        }

        return ! in_array($transaction->status, \App\Enums\TransactionStatus::terminalValues());
    }

    public function updateStatus(User $user): bool
    {
        return UserRole::from($user->role)->canManageReservations();
    }

    public function cancel(User $user, Transaction $transaction): bool
    {
        if (! UserRole::from($user->role)->canManageReservations()) {
            return false;
        }

        return $transaction->canBeCancelled();
    }

    public function markAsNoShow(User $user, Transaction $transaction): bool
    {
        return UserRole::from($user->role)->canManageReservations()
            && $transaction->canBeNoShow();
    }

    public function restore(User $user, Transaction $transaction): bool
    {
        return in_array($user->role, [UserRole::Super->value, UserRole::Admin->value])
            && $transaction->canBeRestored();
    }

    public function delete(User $user): bool
    {
        return $user->role === UserRole::Super->value;
    }

    public function forceDelete(User $user): bool
    {
        return $user->role === UserRole::Super->value;
    }
}
