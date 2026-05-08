<?php

namespace App\Providers;

use App\Models\Payment;
use App\Models\Room;
use App\Models\Transaction;
use App\Models\User;
use App\Policies\PaymentPolicy;
use App\Policies\RoomPolicy;
use App\Policies\TransactionPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Transaction::class => TransactionPolicy::class,
        Payment::class     => PaymentPolicy::class,
        Room::class        => RoomPolicy::class,
        User::class        => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
