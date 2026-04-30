<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\PaymentException;
use App\Exceptions\ReservationException;
use App\Exceptions\TransactionException;
use PHPUnit\Framework\TestCase;

class ExceptionsTest extends TestCase
{
    public function test_transaction_exception_room_unavailable(): void
    {
        $e = TransactionException::roomUnavailable();
        $this->assertStringContainsString('disponible', $e->getMessage());
        $this->assertSame(422, $e->httpStatusCode());
    }

    public function test_transaction_exception_not_found_returns_404(): void
    {
        $e = TransactionException::notFound(42);
        $this->assertSame(404, $e->httpStatusCode());
        $this->assertStringContainsString('42', $e->getMessage());
    }

    public function test_payment_exception_no_active_session(): void
    {
        $e = PaymentException::noActiveSession();
        $this->assertStringContainsString('session', strtolower($e->getMessage()));
        $this->assertSame(422, $e->httpStatusCode());
    }

    public function test_payment_exception_amount_exceeds_balance(): void
    {
        $e = PaymentException::amountExceedsBalance(5000.0);
        $this->assertStringContainsString('5', $e->getMessage()); // montant présent
    }

    public function test_reservation_exception_wrong_day_for_checkin(): void
    {
        $e = ReservationException::wrongDayForCheckIn('25/12/2025');
        $this->assertStringContainsString('25/12/2025', $e->getMessage());
    }

    public function test_exception_to_array_has_required_keys(): void
    {
        $e = TransactionException::roomUnavailable();
        $arr = $e->toArray();

        $this->assertArrayHasKey('error', $arr);
        $this->assertArrayHasKey('message', $arr);
        $this->assertArrayHasKey('code', $arr);
    }
}
