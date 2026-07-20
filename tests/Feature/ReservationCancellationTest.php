<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomStatus;
use App\Models\Transaction;
use App\Models\Type;
use App\Models\User;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Issue #187 : l'annulation d'une réservation n'exigeait aucun motif.
 * Le motif est désormais obligatoire (traçabilité).
 *
 * NB : par règle métier existante (CheckAdminRestriction), seul le Super peut
 * annuler une réservation — c'est donc lui qu'on utilise ici.
 */
class ReservationCancellationTest extends TestCase
{
    use RefreshDatabase;

    private function makeReservation(): array
    {
        $hotel = Hotel::create([
            'name'                    => 'Hotel Annul',
            'slug'                    => Str::slug('Hotel Annul '.Str::random(4)),
            'is_active'               => true,
            'onboarding_completed_at' => now(),
            'subscription_ends_at'    => now()->addMonth(),
        ]);
        app(TenantManager::class)->setHotelId($hotel->id);

        $admin    = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);
        $customer = Customer::create([
            'name' => 'Client Test', 'email' => 'c@annul.test', 'phone' => '+229 01 02 03 04',
            'gender' => 'Male', 'user_id' => $admin->id,
        ]);
        $type = Type::create(['name' => 'Std', 'capacity' => 2, 'information' => 'x']);
        $room = Room::create([
            'type_id' => $type->id, 'room_status_id' => RoomStatus::where('code', 'AVL')->value('id'),
            'number' => '101', 'capacity' => 2, 'price' => 100, 'view' => '',
        ]);
        $tx = Transaction::create([
            'user_id' => $admin->id, 'customer_id' => $customer->id, 'room_id' => $room->id,
            'check_in' => now()->addDays(5)->format('Y-m-d'),
            'check_out' => now()->addDays(7)->format('Y-m-d'),
            'status' => 'reservation', 'person_count' => 1, 'total_price' => 50000,
        ]);

        $super = User::factory()->create(['role' => 'Super', 'hotel_id' => null]);

        return [$super, $tx];
    }

    public function test_cancellation_requires_a_reason(): void
    {
        [$super, $tx] = $this->makeReservation();

        app(TenantManager::class)->forget();
        $this->actingAs($super)
            ->delete(route('transaction.cancel', $tx), [])
            ->assertSessionHasErrors('cancel_reason');

        $this->assertNotEquals('cancelled', $tx->fresh()->status);
    }

    public function test_cancellation_succeeds_with_a_reason(): void
    {
        [$super, $tx] = $this->makeReservation();

        app(TenantManager::class)->forget();
        $this->actingAs($super)
            ->delete(route('transaction.cancel', $tx), ['cancel_reason' => 'Client indisponible'])
            ->assertSessionHasNoErrors();

        $this->assertEquals('cancelled', $tx->fresh()->status);
    }
}
