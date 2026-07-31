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
 * Lot 2 (réservation en ligne) : récap + création de la réservation depuis la
 * vitrine (statut "reservation", scopée à l'hôtel), et anti-double-réservation.
 */
class PublicBookingTest extends TestCase
{
    use RefreshDatabase;

    private function hotelWithRoom(string $name): array
    {
        $hotel = Hotel::create([
            'name'                    => $name,
            'slug'                    => Str::slug($name.' '.Str::random(4)),
            'is_active'               => true,
            'show_rooms'              => true,
            'onboarding_completed_at' => now(),
            'subscription_ends_at'    => now()->addMonth(),
        ]);
        app(TenantManager::class)->setHotelId($hotel->id);
        $owner = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);
        $hotel->update(['owner_user_id' => $owner->id]);
        $type = Type::firstOrCreate(['name' => 'Std'], ['capacity' => 2, 'information' => 'x']);
        $room = Room::create([
            'type_id' => $type->id, 'room_status_id' => RoomStatus::where('code', 'AVL')->value('id'),
            'number' => '101', 'capacity' => 3, 'price' => 50000, 'view' => '',
        ]);

        return [$hotel, $room];
    }

    public function test_online_booking_creates_a_reservation_scoped_to_hotel(): void
    {
        [$hotel, $room] = $this->hotelWithRoom('Hotel Book A');
        app(TenantManager::class)->forget();

        $res = $this->post('/h/'.$hotel->slug.'/reserver/'.$room->id, [
            'check_in'  => now()->addDays(4)->format('Y-m-d'),
            'check_out' => now()->addDays(6)->format('Y-m-d'),
            'guests'    => 2,
            'name'      => 'Awa Cliente',
            'email'     => 'awa@voyage.test',
            'phone'     => '+229 01 02 03 04',
        ]);

        $res->assertRedirect();
        $this->assertDatabaseHas('customers', ['email' => 'awa@voyage.test']);
        $this->assertDatabaseHas('transactions', [
            'room_id'   => $room->id,
            'hotel_id'  => $hotel->id,
            'status'    => 'reservation',
            'person_count' => 2,
            'total_price'  => 100000, // 50000 × 2 nuits
        ]);

        // La page de confirmation s'affiche
        $tx = Transaction::where('room_id', $room->id)->first();
        $this->get('/h/'.$hotel->slug.'/reservation/'.$tx->id.'/confirmee')
            ->assertOk()->assertSee('RES-');
    }

    public function test_double_booking_is_prevented(): void
    {
        [$hotel, $room] = $this->hotelWithRoom('Hotel Book B');
        $owner = User::where('hotel_id', $hotel->id)->first();
        $customer = Customer::create(['name' => 'Déjà', 'email' => 'deja@x.test', 'phone' => '+229 01', 'gender' => 'Male', 'user_id' => $owner->id]);
        Transaction::create([
            'user_id' => $owner->id, 'customer_id' => $customer->id, 'room_id' => $room->id,
            'check_in' => now()->addDays(4)->format('Y-m-d'), 'check_out' => now()->addDays(6)->format('Y-m-d'),
            'status' => 'reservation', 'person_count' => 1, 'total_price' => 100000,
        ]);
        app(TenantManager::class)->forget();

        // Tentative de réserver la MÊME chambre sur une période qui chevauche
        $this->post('/h/'.$hotel->slug.'/reserver/'.$room->id, [
            'check_in'  => now()->addDays(5)->format('Y-m-d'),
            'check_out' => now()->addDays(7)->format('Y-m-d'),
            'guests'    => 1,
            'name'      => 'Second Client',
            'email'     => 'second@x.test',
            'phone'     => '+229 09 08 07 06',
        ])->assertSessionHas('booking_error');

        // Aucune 2ᵉ réservation créée
        $this->assertEquals(1, Transaction::where('room_id', $room->id)->count());
        $this->assertDatabaseMissing('customers', ['email' => 'second@x.test']);
    }
}
