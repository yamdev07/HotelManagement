<?php

namespace Tests\Feature;

use App\Enums\TransactionStatus;
use App\Enums\UserRole;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionPolicyTest extends TestCase
{
    use RefreshDatabase;

    // -----------------------------------------------------------------------
    // viewAny
    // -----------------------------------------------------------------------

    public function test_staff_can_view_any_transaction(): void
    {
        foreach ([UserRole::Super, UserRole::Admin, UserRole::Receptionist, UserRole::Cashier, UserRole::Housekeeping] as $role) {
            $user = User::factory()->create(['role' => $role->value]);
            $this->assertTrue($user->can('viewAny', Transaction::class), "Failed for role {$role->value}");
        }
    }

    public function test_customer_cannot_view_any_transaction(): void
    {
        $user = User::factory()->create(['role' => UserRole::Customer->value]);
        $this->assertFalse($user->can('viewAny', Transaction::class));
    }

    // -----------------------------------------------------------------------
    // create
    // -----------------------------------------------------------------------

    public function test_admin_and_receptionist_can_create_transaction(): void
    {
        foreach ([UserRole::Super, UserRole::Admin, UserRole::Receptionist] as $role) {
            $user = User::factory()->create(['role' => $role->value]);
            $this->assertTrue($user->can('create', Transaction::class));
        }
    }

    public function test_cashier_cannot_create_transaction(): void
    {
        $user = User::factory()->create(['role' => UserRole::Cashier->value]);
        $this->assertFalse($user->can('create', Transaction::class));
    }

    // -----------------------------------------------------------------------
    // delete
    // -----------------------------------------------------------------------

    public function test_only_super_can_delete_transaction(): void
    {
        $super = User::factory()->create(['role' => UserRole::Super->value]);
        $admin = User::factory()->create(['role' => UserRole::Admin->value]);
        $tx    = Transaction::factory()->make();

        $this->assertTrue($super->can('delete', Transaction::class));
        $this->assertFalse($admin->can('delete', Transaction::class));
    }

    // -----------------------------------------------------------------------
    // cancel
    // -----------------------------------------------------------------------

    public function test_receptionist_can_cancel_a_reservation(): void
    {
        $user        = User::factory()->create(['role' => UserRole::Receptionist->value]);
        $transaction = Transaction::factory()->create([
            'status'   => TransactionStatus::Reservation->value,
            'check_in' => now()->addDays(3),
            'check_out' => now()->addDays(5),
        ]);

        $this->assertTrue($user->can('cancel', $transaction));
    }

    public function test_receptionist_cannot_cancel_active_transaction(): void
    {
        $user        = User::factory()->create(['role' => UserRole::Receptionist->value]);
        $transaction = Transaction::factory()->create([
            'status'   => TransactionStatus::Active->value,
            'check_in' => now()->subDay(),
            'check_out' => now()->addDays(2),
        ]);

        $this->assertFalse($user->can('cancel', $transaction));
    }

    // -----------------------------------------------------------------------
    // restore
    // -----------------------------------------------------------------------

    public function test_admin_can_restore_cancelled_transaction(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin->value]);
        $tx    = Transaction::factory()->create([
            'status'   => TransactionStatus::Cancelled->value,
            'check_in' => now()->addDays(1),
            'check_out' => now()->addDays(3),
        ]);

        $this->assertTrue($admin->can('restore', $tx));
    }

    public function test_receptionist_cannot_restore_transaction(): void
    {
        $user = User::factory()->create(['role' => UserRole::Receptionist->value]);
        $tx   = Transaction::factory()->create([
            'status'   => TransactionStatus::Cancelled->value,
            'check_in' => now()->addDays(1),
            'check_out' => now()->addDays(3),
        ]);

        $this->assertFalse($user->can('restore', $tx));
    }
}
