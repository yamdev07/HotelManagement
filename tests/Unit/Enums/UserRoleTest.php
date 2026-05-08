<?php

namespace Tests\Unit\Enums;

use App\Enums\UserRole;
use PHPUnit\Framework\TestCase;

class UserRoleTest extends TestCase
{
    public function test_super_bypasses_staff_check(): void
    {
        $role = UserRole::Super;

        $this->assertTrue($role->isStaff());
        $this->assertTrue($role->canManageReservations());
        $this->assertTrue($role->canProcessPayments());
        $this->assertTrue($role->canManageRooms());
        $this->assertTrue($role->canManageUsers());
    }

    public function test_customer_cannot_manage_anything(): void
    {
        $role = UserRole::Customer;

        $this->assertFalse($role->isStaff());
        $this->assertFalse($role->canManageReservations());
        $this->assertFalse($role->canProcessPayments());
        $this->assertFalse($role->canManageRooms());
        $this->assertFalse($role->canManageUsers());
    }

    public function test_receptionist_can_manage_reservations_and_payments(): void
    {
        $role = UserRole::Receptionist;

        $this->assertTrue($role->isStaff());
        $this->assertTrue($role->canManageReservations());
        $this->assertTrue($role->canProcessPayments());
        $this->assertFalse($role->canManageRooms());
        $this->assertFalse($role->canManageUsers());
    }

    public function test_cashier_can_process_payments_only(): void
    {
        $role = UserRole::Cashier;

        $this->assertTrue($role->isStaff());
        $this->assertFalse($role->canManageReservations());
        $this->assertTrue($role->canProcessPayments());
        $this->assertFalse($role->canManageRooms());
    }

    public function test_housekeeping_is_staff_with_no_management(): void
    {
        $role = UserRole::Housekeeping;

        $this->assertTrue($role->isStaff());
        $this->assertFalse($role->canManageReservations());
        $this->assertFalse($role->canProcessPayments());
        $this->assertFalse($role->canManageRooms());
    }

    public function test_labels_are_defined_for_all_roles(): void
    {
        foreach (UserRole::cases() as $role) {
            $this->assertNotEmpty($role->label(), "Rôle {$role->value} n'a pas de label.");
        }
    }

    public function test_staff_values_returns_correct_values(): void
    {
        $values = UserRole::staffValues();

        $this->assertContains('Super', $values);
        $this->assertContains('Admin', $values);
        $this->assertContains('Receptionist', $values);
        $this->assertContains('Cashier', $values);
        $this->assertContains('Housekeeping', $values);
        $this->assertNotContains('Customer', $values);
    }

    public function test_tryFrom_returns_null_for_unknown_value(): void
    {
        $this->assertNull(UserRole::tryFrom('Ghost'));
    }
}
