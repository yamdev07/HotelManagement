<?php

namespace Tests\Unit\Services;

use App\Enums\TransactionStatus;
use App\Exceptions\TransactionException;
use App\Models\Transaction;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Services\TransactionService;
use Carbon\Carbon;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class TransactionServiceTest extends TestCase
{
    private TransactionService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $repo          = Mockery::mock(TransactionRepositoryInterface::class);
        $this->service = new TransactionService($repo);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // -----------------------------------------------------------------------
    // cancel
    // -----------------------------------------------------------------------

    public function test_cannot_cancel_already_cancelled_transaction(): void
    {
        $tx = $this->mockTransaction(TransactionStatus::Cancelled->value);
        $tx->shouldReceive('canBeCancelled')->andReturn(false);
        $tx->shouldReceive('getCannotCancelReason')->andReturn('Déjà annulée.');

        $this->expectException(TransactionException::class);
        $this->service->cancel($tx, 'raison');
    }

    public function test_cannot_cancel_active_transaction(): void
    {
        $tx = $this->mockTransaction(TransactionStatus::Active->value);
        $tx->shouldReceive('canBeCancelled')->andReturn(false);
        $tx->shouldReceive('getCannotCancelReason')->andReturn('Client actif.');

        $this->expectException(TransactionException::class);
        $this->service->cancel($tx, 'raison');
    }

    public function test_cannot_cancel_completed_transaction(): void
    {
        $tx = $this->mockTransaction(TransactionStatus::Completed->value);
        $tx->shouldReceive('canBeCancelled')->andReturn(false);
        $tx->shouldReceive('getCannotCancelReason')->andReturn('Séjour terminé.');

        $this->expectException(TransactionException::class);
        $this->service->cancel($tx, 'raison');
    }

    // -----------------------------------------------------------------------
    // markAsNoShow
    // -----------------------------------------------------------------------

    public function test_cannot_mark_no_show_a_future_reservation(): void
    {
        $tx = $this->mockTransaction(TransactionStatus::Reservation->value);
        $tx->shouldReceive('canBeNoShow')->andReturn(false);

        $this->expectException(TransactionException::class);
        $this->service->markAsNoShow($tx, 'absent');
    }

    public function test_cannot_mark_no_show_an_active_transaction(): void
    {
        $tx = $this->mockTransaction(TransactionStatus::Active->value);
        $tx->shouldReceive('canBeNoShow')->andReturn(false);

        $this->expectException(TransactionException::class);
        $this->service->markAsNoShow($tx, 'absent');
    }

    // -----------------------------------------------------------------------
    // restore
    // -----------------------------------------------------------------------

    public function test_cannot_restore_completed_transaction(): void
    {
        $tx = $this->mockTransaction(TransactionStatus::Completed->value);
        $tx->shouldReceive('canBeRestored')->andReturn(false);

        $this->expectException(TransactionException::class);
        $this->service->restore($tx);
    }

    public function test_cannot_restore_active_transaction(): void
    {
        $tx = $this->mockTransaction(TransactionStatus::Active->value);
        $tx->shouldReceive('canBeRestored')->andReturn(false);

        $this->expectException(TransactionException::class);
        $this->service->restore($tx);
    }

    // -----------------------------------------------------------------------
    // isTerminal (via reflection)
    // -----------------------------------------------------------------------

    public function test_is_terminal_returns_true_for_final_statuses(): void
    {
        $method = new \ReflectionMethod($this->service, 'isTerminal');
        $method->setAccessible(true);

        foreach ([TransactionStatus::Completed, TransactionStatus::Cancelled, TransactionStatus::NoShow] as $status) {
            $tx = $this->mockTransaction($status->value);
            $this->assertTrue($method->invoke($this->service, $tx), "Non-terminal pour {$status->value}");
        }
    }

    public function test_is_terminal_returns_false_for_non_final_statuses(): void
    {
        $method = new \ReflectionMethod($this->service, 'isTerminal');
        $method->setAccessible(true);

        foreach ([TransactionStatus::Reservation, TransactionStatus::Active] as $status) {
            $tx = $this->mockTransaction($status->value);
            $this->assertFalse($method->invoke($this->service, $tx), "Terminal pour {$status->value}");
        }
    }

    // -----------------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------------

    private function mockTransaction(string $status = 'reservation'): MockInterface
    {
        $tx = Mockery::mock(Transaction::class)->shouldIgnoreMissing();
        // Les méthodes canBe* du modèle lisent $this->status via getAttribute
        $tx->shouldReceive('getAttribute')->with('status')->andReturn($status)->byDefault();
        $tx->shouldReceive('__get')->with('status')->andReturn($status)->byDefault();
        $tx->shouldReceive('setAttribute')->withAnyArgs()->andReturnNull()->byDefault();

        return $tx;
    }
}
