<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Hotel;
use App\Models\Payment;
use App\Enums\RoomStatus;
use App\Models\Room;
use App\Models\Transaction;
use App\Models\Type;
use App\Models\User;
use App\Support\TenantManager;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Processus métier du séjour de bout en bout :
 * arrivée (check-in), prolongement, late checkout, early checkout,
 * départ (check-out), annulation.
 *
 * Le temps est figé à 13h00 aujourd'hui : le check-in est autorisé (>= 12h)
 * et le check-out l'est aussi (fenêtre 12h–14h).
 */
class StayLifecycleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow(Carbon::today()->setTime(13, 0));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    /**
     * Crée un hôtel + admin + chambre + réservation, puis oublie le tenant.
     *
     * @return array{0:User,1:Transaction}
     */
    private function stay(array $overrides = [], bool $paid = false): array
    {
        $hotel = Hotel::create([
            'name' => 'Life '.Str::random(4), 'slug' => Str::slug('life '.Str::random(6)),
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

        $tx = Transaction::create(array_merge([
            'user_id' => $admin->id, 'customer_id' => $customer->id, 'room_id' => $room->id,
            'check_in' => Carbon::today()->format('Y-m-d'),
            'check_out' => Carbon::today()->addDays(2)->format('Y-m-d'),
            'status' => 'reservation', 'person_count' => 1, 'total_price' => 100000,
        ], $overrides));

        if ($paid) {
            Payment::create([
                'transaction_id' => $tx->id, 'user_id' => $admin->id, 'created_by' => $admin->id,
                'amount' => $tx->total_price, 'status' => Payment::STATUS_COMPLETED,
                'payment_method' => 'cash', 'payment_date' => now(),
                'reference' => 'PAY-'.$tx->id,
            ]);
        }

        app(TenantManager::class)->forget();

        return [$admin, $tx, $hotel];
    }

    public function test_checkin_marks_arrival(): void
    {
        [$admin, $tx] = $this->stay(['status' => 'reservation', 'check_in' => Carbon::today()->format('Y-m-d')]);

        $this->actingAs($admin)
            ->post(route('transaction.mark-arrived', $tx))
            ->assertSessionHasNoErrors()->assertRedirect();

        $this->assertSame('active', $tx->fresh()->status);
    }

    public function test_extend_pushes_checkout_and_price(): void
    {
        [$admin, $tx] = $this->stay(['status' => 'active']);

        $this->actingAs($admin)->post(route('transaction.extend.process', $tx), [
            'new_check_out' => Carbon::today()->addDays(4)->format('Y-m-d'),
            'additional_nights' => 2,
        ])->assertSessionHasNoErrors()->assertRedirect();

        $fresh = $tx->fresh();
        $this->assertSame(Carbon::today()->addDays(4)->format('Y-m-d'), $fresh->check_out->format('Y-m-d'));
        $this->assertEquals(200000, (float) $fresh->total_price); // 100000 + 2 nuits * 50000
    }

    public function test_late_checkout_is_recorded_and_billed(): void
    {
        [$admin, $tx] = $this->stay(['status' => 'active']);

        $this->actingAs($admin)->post(route('transaction.late-checkout', $tx), [
            'expected_checkout_time' => '15:00',
            'late_checkout_fee' => 5000,
            'payment_method' => 'cash',
        ])->assertSessionHasNoErrors()->assertRedirect();

        $fresh = $tx->fresh();
        $this->assertTrue((bool) $fresh->late_checkout);
        $this->assertEquals(5000, (float) $fresh->late_checkout_fee);
        $this->assertDatabaseHas('payments', ['transaction_id' => $tx->id, 'amount' => 5000]);
    }

    public function test_early_checkout_completes_stay(): void
    {
        [$admin, $tx] = $this->stay(['status' => 'active']);

        $this->actingAs($admin)->post(route('transaction.early-checkout', $tx), [
            'refund_policy' => 'none',
            'payment_method' => 'cash',
        ])->assertSessionHasNoErrors()->assertRedirect();

        $fresh = $tx->fresh();
        $this->assertSame('completed', $fresh->status);
        $this->assertTrue((bool) $fresh->early_checkout);
    }

    public function test_checkout_departure_completes_when_fully_paid(): void
    {
        [$admin, $tx] = $this->stay(
            ['status' => 'active', 'check_out' => Carbon::today()->format('Y-m-d')],
            paid: true
        );

        $this->actingAs($admin)
            ->post(route('transaction.mark-departed', $tx))
            ->assertSessionHasNoErrors()->assertRedirect();

        $this->assertSame('completed', $tx->fresh()->status);
    }

    public function test_cancel_requires_reason_and_cancels(): void
    {
        // En pratique seul le Super peut annuler : Admin (CheckAdminRestriction)
        // et Réceptionniste (CheckReceptionistRestriction) sont bloqués, pour la
        // traçabilité des annulations.
        [$admin, $tx, $hotel] = $this->stay(['status' => 'reservation', 'check_in' => Carbon::tomorrow()->format('Y-m-d')]);
        app(TenantManager::class)->setHotelId($hotel->id);
        $super = User::factory()->create(['role' => 'Super', 'hotel_id' => $hotel->id]);
        app(TenantManager::class)->forget();

        // Sans motif -> refusé (validation)
        $this->actingAs($super)
            ->delete(route('transaction.cancel', $tx))
            ->assertSessionHasErrors('cancel_reason');
        $this->assertSame('reservation', $tx->fresh()->status);

        // Avec motif -> annulé
        $this->actingAs($super)->delete(route('transaction.cancel', $tx), [
            'cancel_reason' => 'Client empêché',
        ])->assertSessionHasNoErrors()->assertRedirect();

        $this->assertSame('cancelled', $tx->fresh()->status);
    }

    public function test_admin_and_receptionist_cannot_cancel_by_design(): void
    {
        [$admin, $tx, $hotel] = $this->stay(['status' => 'reservation', 'check_in' => Carbon::tomorrow()->format('Y-m-d')]);
        app(TenantManager::class)->setHotelId($hotel->id);
        $reception = User::factory()->create(['role' => 'Receptionist', 'hotel_id' => $hotel->id]);
        app(TenantManager::class)->forget();

        foreach ([$admin, $reception] as $user) {
            $this->actingAs($user)->delete(route('transaction.cancel', $tx), [
                'cancel_reason' => 'Test',
            ])->assertRedirect();
            $this->assertSame('reservation', $tx->fresh()->status);
        }
    }
}
