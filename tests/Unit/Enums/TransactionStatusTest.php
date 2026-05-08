<?php

namespace Tests\Unit\Enums;

use App\Enums\TransactionStatus;
use PHPUnit\Framework\TestCase;

class TransactionStatusTest extends TestCase
{
    public function test_terminal_statuses_are_final(): void
    {
        $this->assertTrue(TransactionStatus::Completed->isFinal());
        $this->assertTrue(TransactionStatus::Cancelled->isFinal());
        $this->assertTrue(TransactionStatus::NoShow->isFinal());
    }

    public function test_non_terminal_statuses_are_not_final(): void
    {
        $this->assertFalse(TransactionStatus::Reservation->isFinal());
        $this->assertFalse(TransactionStatus::Active->isFinal());
        $this->assertFalse(TransactionStatus::PendingCheckout->isFinal());
    }

    public function test_only_reservation_can_be_cancelled(): void
    {
        $this->assertTrue(TransactionStatus::Reservation->canBeCancelled());
        $this->assertFalse(TransactionStatus::Active->canBeCancelled());
        $this->assertFalse(TransactionStatus::Completed->canBeCancelled());
    }

    public function test_only_reservation_can_be_checked_in(): void
    {
        $this->assertTrue(TransactionStatus::Reservation->canBeCheckedIn());
        $this->assertFalse(TransactionStatus::Active->canBeCheckedIn());
    }

    public function test_only_active_can_be_checked_out(): void
    {
        $this->assertTrue(TransactionStatus::Active->canBeCheckedOut());
        $this->assertFalse(TransactionStatus::Reservation->canBeCheckedOut());
        $this->assertFalse(TransactionStatus::Completed->canBeCheckedOut());
    }

    public function test_cancelled_and_noshow_can_be_restored(): void
    {
        $this->assertTrue(TransactionStatus::Cancelled->canBeRestoredTo());
        $this->assertTrue(TransactionStatus::NoShow->canBeRestoredTo());
        $this->assertFalse(TransactionStatus::Completed->canBeRestoredTo());
        $this->assertFalse(TransactionStatus::Active->canBeRestoredTo());
    }

    public function test_terminal_values_returns_strings(): void
    {
        $terminals = TransactionStatus::terminalValues();

        $this->assertContains('completed', $terminals);
        $this->assertContains('cancelled', $terminals);
        $this->assertContains('no_show', $terminals);
    }

    public function test_all_statuses_have_labels(): void
    {
        foreach (TransactionStatus::cases() as $status) {
            $this->assertNotEmpty($status->label(), "Statut {$status->value} sans label.");
        }
    }
}
