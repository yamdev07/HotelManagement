<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role->isStaff();
    }

    public function view(User $user, Payment $payment): bool
    {
        if ($user->role->isStaff()) {
            return true;
        }

        return $user->customer?->id === optional($payment->transaction)->customer_id;
    }

    public function create(User $user): bool
    {
        return $user->role->canProcessPayments();
    }

    public function cancel(User $user, Payment $payment): bool
    {
        if (in_array($user->role, [UserRole::Super, UserRole::Admin])) {
            return true;
        }

        return $user->role->canProcessPayments() && $payment->status === 'pending';
    }

    public function refund(User $user): bool
    {
        return in_array($user->role, [UserRole::Super, UserRole::Admin]);
    }
}
