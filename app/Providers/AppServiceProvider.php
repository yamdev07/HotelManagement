<?php

namespace App\Providers;

use App\Repositories\Implementation\CustomerRepository;
use App\Repositories\Implementation\ImageRepository;
use App\Repositories\Implementation\PaymentRepository;
use App\Repositories\Implementation\ReservationRepository;
use App\Repositories\Implementation\RoomRepository;
use App\Repositories\Implementation\RoomStatusRepository;
use App\Repositories\Implementation\TransactionRepository;
use App\Repositories\Implementation\TypeRepository;
use App\Repositories\Implementation\UserRepository;
use App\Repositories\Interface\CustomerRepositoryInterface;
use App\Repositories\Interface\ImageRepositoryInterface;
use App\Repositories\Interface\PaymentRepositoryInterface;
use App\Repositories\Interface\ReservationRepositoryInterface;
use App\Repositories\Interface\RoomRepositoryInterface;
use App\Repositories\Interface\RoomStatusRepositoryInterface;
use App\Repositories\Interface\TransactionRepositoryInterface;
use App\Repositories\Interface\TypeRepositoryInterface;
use App\Repositories\Interface\UserRepositoryInterface;
use App\Services\CashierSessionService;
use App\Services\CheckInService;
use App\Services\DashboardService;
use App\Services\HousekeepingService;
use App\Services\PaymentService;
use App\Services\SessionActivityService;
use App\Services\TransactionService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Repositories
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(ImageRepositoryInterface::class, ImageRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
        $this->app->bind(ReservationRepositoryInterface::class, ReservationRepository::class);
        $this->app->bind(RoomRepositoryInterface::class, RoomRepository::class);
        $this->app->bind(RoomStatusRepositoryInterface::class, RoomStatusRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
        $this->app->bind(TypeRepositoryInterface::class, TypeRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        // Services
        $this->app->singleton(TransactionService::class);
        $this->app->singleton(PaymentService::class);
        $this->app->singleton(CheckInService::class);
        $this->app->singleton(HousekeepingService::class);
        $this->app->singleton(CashierSessionService::class);
        $this->app->singleton(DashboardService::class);
        $this->app->singleton(SessionActivityService::class);
    }

    public function boot(): void {}
}
