<?php

namespace Tests\Unit\Services;

use App\Enums\PaymentStatus;
use App\Enums\RoomStatus;
use App\Exceptions\PaymentException;
use App\Models\CashierSession;
use App\Models\Customer;
use App\Models\Hotel;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Transaction;
use App\Models\Type;
use App\Models\User;
use App\Services\PaymentService;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Fiabilité financière de PaymentService : encaissement partiel/complet,
 * refus de dépassement du solde (recalcul sous verrou), et gardes d'annulation
 * (autorisation, double annulation, paiement déjà annulé).
 *
 * Couvre les scénarios de la revue de code autour de store() et cancel().
 */
class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    private Hotel $hotel;

    private User $admin;

    private PaymentService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(PaymentService::class);
    }

    /** Crée un hôtel + admin + transaction de 100 000, tenant actif. */
    private function makeTx(float $total = 100000): Transaction
    {
        $this->hotel = Hotel::create([
            'name' => 'Pay '.Str::random(4), 'slug' => Str::slug('pay '.Str::random(6)),
            'is_active' => true, 'onboarding_completed_at' => now(), 'subscription_ends_at' => now()->addMonth(),
        ]);
        app(TenantManager::class)->setHotelId($this->hotel->id);

        $this->admin = User::factory()->create(['role' => 'Admin', 'hotel_id' => $this->hotel->id]);
        $this->hotel->update(['owner_user_id' => $this->admin->id]);

        $type = Type::firstOrCreate(['name' => 'Std'], ['capacity' => 2, 'information' => 'x']);
        $room = Room::create([
            'type_id' => $type->id, 'room_status_id' => RoomStatus::Available->value,
            'number' => (string) random_int(100, 999), 'capacity' => 2, 'price' => 50000, 'view' => '',
        ]);
        $customer = Customer::create([
            'name' => 'Cli', 'email' => Str::random(6).'@x.test', 'phone' => '+22990',
            'gender' => 'Other', 'user_id' => $this->admin->id,
        ]);

        return Transaction::create([
            'user_id' => $this->admin->id, 'customer_id' => $customer->id, 'room_id' => $room->id,
            'check_in' => now()->format('Y-m-d'), 'check_out' => now()->addDays(2)->format('Y-m-d'),
            'status' => 'active', 'person_count' => 1, 'total_price' => $total,
        ]);
    }

    /** Session de caisse active pour l'utilisateur donné. */
    private function openSession(User $user): CashierSession
    {
        return CashierSession::create([
            'user_id' => $user->id, 'initial_balance' => 0, 'current_balance' => 0,
            'start_time' => now(), 'status' => 'active', 'shift_type' => 'morning',
        ]);
    }

    // --- store() : solde ---------------------------------------------------

    public function test_partial_payment_keeps_exact_remaining_balance(): void
    {
        $tx = $this->makeTx(100000);
        $this->openSession($this->admin);
        $this->actingAs($this->admin);

        $this->service->store($tx, ['amount' => 40000, 'payment_method' => 'cash']);

        $tx->refresh();
        $this->assertEqualsWithDelta(60000, $tx->getRemainingPayment(), 0.01);
        $this->assertFalse($tx->isFullyPaid());
    }

    public function test_full_payment_marks_transaction_fully_paid(): void
    {
        $tx = $this->makeTx(100000);
        $this->openSession($this->admin);
        $this->actingAs($this->admin);

        $this->service->store($tx, ['amount' => 100000, 'payment_method' => 'cash']);

        $tx->refresh();
        $this->assertEqualsWithDelta(0, $tx->getRemainingPayment(), 0.01);
        $this->assertTrue($tx->isFullyPaid());
    }

    public function test_payment_exceeding_balance_is_rejected(): void
    {
        $tx = $this->makeTx(100000);
        $this->openSession($this->admin);
        $this->actingAs($this->admin);

        $this->expectException(PaymentException::class);

        $this->service->store($tx, ['amount' => 150000, 'payment_method' => 'cash']);
    }

    /**
     * Garde anti double encaissement : le solde est recalculé sous verrou
     * À L'INTÉRIEUR de la transaction. Un second encaissement ne peut donc pas
     * dépasser le solde réellement restant après le premier.
     */
    public function test_second_payment_cannot_exceed_remaining_after_first(): void
    {
        $tx = $this->makeTx(100000);
        $this->openSession($this->admin);
        $this->actingAs($this->admin);

        // Premier encaissement : 60 000, reste 40 000.
        $this->service->store($tx, ['amount' => 60000, 'payment_method' => 'cash']);

        // Second encaissement de 60 000 : doit être refusé (reste recalculé = 40 000).
        $this->expectException(PaymentException::class);
        $this->service->store($tx, ['amount' => 60000, 'payment_method' => 'cash']);
    }

    public function test_two_valid_payments_never_exceed_total_due(): void
    {
        $tx = $this->makeTx(100000);
        $this->openSession($this->admin);
        $this->actingAs($this->admin);

        $this->service->store($tx, ['amount' => 60000, 'payment_method' => 'cash']);
        $this->service->store($tx, ['amount' => 40000, 'payment_method' => 'cash']);

        $tx->refresh();
        $this->assertEqualsWithDelta(100000, $tx->getTotalPayment(), 0.01);
        $this->assertEqualsWithDelta(0, $tx->getRemainingPayment(), 0.01);
    }

    // --- cancel() : gardes -------------------------------------------------

    private function completedPayment(Transaction $tx): Payment
    {
        $this->openSession($this->admin);
        $this->actingAs($this->admin);

        return $this->service->store($tx, ['amount' => 50000, 'payment_method' => 'cash']);
    }

    public function test_non_admin_cannot_cancel_completed_payment(): void
    {
        $tx = $this->makeTx(100000);
        $payment = $this->completedPayment($tx);

        $receptionist = User::factory()->create(['role' => 'Receptionist', 'hotel_id' => $this->hotel->id]);
        $this->actingAs($receptionist);

        $this->expectException(PaymentException::class);
        $this->service->cancel($payment, 'test');
    }

    public function test_super_can_cancel_completed_payment_and_restore_balance(): void
    {
        $tx = $this->makeTx(100000);
        $payment = $this->completedPayment($tx);

        $this->assertEqualsWithDelta(50000, $tx->refresh()->getTotalPayment(), 0.01);

        $super = User::factory()->create(['role' => 'Super', 'hotel_id' => $this->hotel->id]);
        $this->actingAs($super);

        $cancelled = $this->service->cancel($payment, 'erreur de saisie');

        $this->assertSame(PaymentStatus::Cancelled->value, $cancelled->status);
        // Le paiement annulé ne compte plus : le solde payé revient à 0.
        $this->assertEqualsWithDelta(0, $tx->refresh()->getTotalPayment(), 0.01);
    }

    public function test_already_cancelled_payment_cannot_be_cancelled_again(): void
    {
        $tx = $this->makeTx(100000);
        $payment = $this->completedPayment($tx);

        $super = User::factory()->create(['role' => 'Super', 'hotel_id' => $this->hotel->id]);
        $this->actingAs($super);

        $this->service->cancel($payment, 'première annulation');
        $paidAfterFirstCancel = $tx->refresh()->getTotalPayment();

        // Seconde annulation : refusée, et aucun nouvel impact financier.
        try {
            $this->service->cancel($payment->refresh(), 'seconde annulation');
            $this->fail('Une seconde annulation aurait dû lever PaymentException.');
        } catch (PaymentException $e) {
            // attendu
        }

        $this->assertEqualsWithDelta($paidAfterFirstCancel, $tx->refresh()->getTotalPayment(), 0.01);
    }
}
