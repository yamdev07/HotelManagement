<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->roleEnum === UserRole::Super ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->roleEnum->isStaff();
    }

    public function view(User $user, Transaction $transaction): bool
    {
        if ($user->roleEnum->isStaff()) {
            return true;
        }

        return $user->customer?->id === $transaction->customer_id;
    }

    public function create(User $user): bool
    {
        return $user->roleEnum->canManageReservations();
    }

    public function update(User $user, Transaction $transaction): bool
    {
        if (! $user->roleEnum->canManageReservations()) {
            return false;
        }

        return ! in_array($transaction->status, \App\Enums\TransactionStatus::terminalValues());
    }

    public function updateStatus(User $user): bool
    {
        return $user->roleEnum->canManageReservations();
    }

    public function cancel(User $user, Transaction $transaction): bool
    {
        if (! $user->roleEnum->canManageReservations()) {
            return false;
        }

        return $transaction->canBeCancelled();
    }

    public function markAsNoShow(User $user, Transaction $transaction): bool
    {
        return $user->roleEnum->canManageReservations() && $transaction->canBeNoShow();
    }

    public function restore(User $user, Transaction $transaction): bool
    {
        return in_array($user->roleEnum, [UserRole::Super, UserRole::Admin])
            && $transaction->canBeRestored();
    }

    public function delete(User $user): bool
    {
        return $user->roleEnum === UserRole::Super;
    }

    public function forceDelete(User $user): bool
    {
        return $user->roleEnum === UserRole::Super;
    }
}
