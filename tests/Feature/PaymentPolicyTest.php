<?php

namespace Tests\Feature;

use App\Enums\PaymentStatus;
use App\Enums\UserRole;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_view_any_payment(): void
    {
        foreach (UserRole::staffRoles() as $role) {
            $user = User::factory()->create(['role' => $role->value]);
            $this->assertTrue($user->can('viewAny', Payment::class), "Failed for {$role->value}");
        }
    }

    public function test_customer_cannot_view_any_payment(): void
    {
        $user = User::factory()->create(['role' => UserRole::Customer->value]);
        $this->assertFalse($user->can('viewAny', Payment::class));
    }

    public function test_receptionist_and_cashier_can_create_payment(): void
    {
        foreach ([UserRole::Receptionist, UserRole::Cashier, UserRole::Admin, UserRole::Super] as $role) {
            $user = User::factory()->create(['role' => $role->value]);
            $this->assertTrue($user->can('create', Payment::class));
        }
    }

    public function test_housekeeping_cannot_create_payment(): void
    {
        $user = User::factory()->create(['role' => UserRole::Housekeeping->value]);
        $this->assertFalse($user->can('create', Payment::class));
    }

    public function test_only_admin_can_refund_payment(): void
    {
        $admin        = User::factory()->create(['role' => UserRole::Admin->value]);
        $receptionist = User::factory()->create(['role' => UserRole::Receptionist->value]);

        $this->assertTrue($admin->can('refund', Payment::class));
        $this->assertFalse($receptionist->can('refund', Payment::class));
    }
}
