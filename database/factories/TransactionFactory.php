<?php

namespace Database\Factories;

use App\Enums\TransactionStatus;
use App\Models\Customer;
use App\Models\Room;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'user_id'      => User::factory(),
            'customer_id'  => Customer::factory(),
            'room_id'      => Room::factory(),
            'check_in'     => now()->addDays(1)->format('Y-m-d'),
            'check_out'    => now()->addDays(3)->format('Y-m-d'),
            'status'       => TransactionStatus::Reservation->value,
            'person_count' => 1,
            'total_price'  => 50000,
        ];
    }

    public function reservation(): static
    {
        return $this->state(['status' => TransactionStatus::Reservation->value]);
    }

    public function active(): static
    {
        return $this->state(['status' => TransactionStatus::Active->value]);
    }

    public function cancelled(): static
    {
        return $this->state(['status' => TransactionStatus::Cancelled->value]);
    }
}
