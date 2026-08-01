<?php

namespace Tests\Feature;

use App\Enums\RoomStatus;
use App\Models\CashierSession;
use App\Models\Customer;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Transaction;
use App\Models\Type;
use App\Models\User;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Processus caisse & encaissement :
 * ouverture de session, refus de paiement sans caisse, encaissement,
 * clôture de caisse.
 */
class CashierPaymentTest extends TestCase
{
    use RefreshDatabase;

    /** @return array{0:User,1:Transaction,2:Hotel} */
    private function makeTx(float $total = 100000): array
    {
        $hotel = Hotel::create([
            'name' => 'Pay '.Str::random(4), 'slug' => Str::slug('pay '.Str::random(6)),
            'is_active' => true, 'onboarding_completed_at' => now(), 'subscription_ends_at' => now()->addMonth(),
        ]);
        app(TenantManager::class)->setHotelId($hotel->id);

        $admin = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);
        $hotel->update(['owner_user_id' => $admin->id]);

        $type = Type::firstOrCreate(['name' => 'Std'], ['capacity' => 2, 'information' => 'x']);
        $room = Room::create([
            'type_id' => $type->id, 'room_status_id' => RoomStatus::Available->value,
            'number' => (string) random_int(100, 999), 'capacity' => 2, 'price' => 50000, 'view' => '',
        ]);
        $customer = Customer::create([
            'name' => 'Cli', 'email' => Str::random(6).'@x.test', 'phone' => '+22990',
            'gender' => 'Other', 'user_id' => $admin->id,
        ]);
        $tx = Transaction::create([
            'user_id' => $admin->id, 'customer_id' => $customer->id, 'room_id' => $room->id,
            'check_in' => now()->format('Y-m-d'), 'check_out' => now()->addDays(2)->format('Y-m-d'),
            'status' => 'active', 'person_count' => 1, 'total_price' => $total,
        ]);

        app(TenantManager::class)->forget();

        return [$admin, $tx, $hotel];
    }

    private function openSession(User $user, Hotel $hotel): CashierSession
    {
        app(TenantManager::class)->setHotelId($hotel->id);
        $session = CashierSession::create([
            'user_id' => $user->id, 'initial_balance' => 0, 'current_balance' => 0,
            'start_time' => now(), 'status' => 'active', 'shift_type' => 'morning',
        ]);
        app(TenantManager::class)->forget();

        return $session;
    }

    public function test_open_session_creates_active_session(): void
    {
        [$admin, , $hotel] = $this->makeTx();

        $this->actingAs($admin)->post(route('cashier.sessions.store'), ['notes' => 'Ouverture test'])
            ->assertRedirect();

        app(TenantManager::class)->setHotelId($hotel->id);
        $this->assertDatabaseHas('cashier_sessions', [
            'user_id' => $admin->id, 'status' => 'active',
        ]);
        app(TenantManager::class)->forget();
    }

    public function test_payment_refused_without_open_session(): void
    {
        [$admin, $tx] = $this->makeTx();

        $this->actingAs($admin)->post(route('transaction.payment.store', $tx), [
            'amount' => 50000, 'payment_method' => 'cash',
        ])->assertRedirect();

        $this->assertDatabaseMissing('payments', ['transaction_id' => $tx->id]);
    }

    public function test_payment_recorded_and_marks_fully_paid(): void
    {
        [$admin, $tx, $hotel] = $this->makeTx(100000);
        $session = $this->openSession($admin, $hotel);

        $this->actingAs($admin)->post(route('transaction.payment.store', $tx), [
            'amount' => 100000, 'payment_method' => 'cash',
        ])->assertSessionHasNoErrors()->assertRedirect();

        $this->assertDatabaseHas('payments', [
            'transaction_id' => $tx->id, 'amount' => 100000, 'cashier_session_id' => $session->id,
        ]);
        $this->assertTrue($tx->fresh()->isFullyPaid());
        // Le cash alimente le solde de caisse.
        $this->assertEquals(100000, (float) $session->fresh()->current_balance);
    }

    public function test_close_session_marks_closed(): void
    {
        [$admin, , $hotel] = $this->makeTx();
        $session = $this->openSession($admin, $hotel);

        $this->actingAs($admin)->delete(route('cashier.sessions.destroy', $session), [
            'final_balance' => 0, 'closing_notes' => 'Clôture test',
        ])->assertRedirect();

        $this->assertSame('closed', $session->fresh()->status);
    }
}
