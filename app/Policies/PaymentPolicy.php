<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return UserRole::from($user->role)->isStaff();
    }

    public function view(User $user, Payment $payment): bool
    {
        if (UserRole::from($user->role)->isStaff()) {
            return true;
        }

        // Un client voit ses paiements via sa transaction
        return $user->customer?->id === optional($payment->transaction)->customer_id;
    }

    public function create(User $user): bool
    {
        return UserRole::from($user->role)->canProcessPayments();
    }

    public function cancel(User $user, Payment $payment): bool
    {
        $role = UserRole::from($user->role);

        if (in_array($user->role, [UserRole::Super->value, UserRole::Admin->value])) {
            return true;
        }

        // Receptionist / Cashier ne peut annuler que les paiements en attente
        if ($role->canProcessPayments() && $payment->status === 'pending') {
            return true;
        }

        return false;
    }

    public function refund(User $user): bool
    {
        return in_array($user->role, [UserRole::Super->value, UserRole::Admin->value]);
    }
}
