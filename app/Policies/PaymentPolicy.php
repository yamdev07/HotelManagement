<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->roleEnum->isStaff();
    }

    public function view(User $user, Payment $payment): bool
    {
        if ($user->roleEnum->isStaff()) {
            return true;
        }

        return $user->customer?->id === optional($payment->transaction)->customer_id;
    }

    public function create(User $user): bool
    {
        return $user->roleEnum->canProcessPayments();
    }

    public function cancel(User $user, Payment $payment): bool
    {
        if (in_array($user->roleEnum, [UserRole::Super, UserRole::Admin])) {
            return true;
        }

        return $user->roleEnum->canProcessPayments() && $payment->status === 'pending';
    }

    public function refund(User $user): bool
    {
        return in_array($user->roleEnum, [UserRole::Super, UserRole::Admin]);
    }
}
