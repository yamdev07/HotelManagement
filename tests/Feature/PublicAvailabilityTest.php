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
 * Lot 1 (réservation en ligne) : la recherche de disponibilité publique sur la
 * vitrine d'un hôtel ne montre QUE les chambres de cet hôtel, réellement libres.
 */
class PublicAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    private function hotel(string $name): Hotel
    {
        return Hotel::create([
            'name'                    => $name,
            'slug'                    => Str::slug($name.' '.Str::random(4)),
            'is_active'               => true,
            'show_rooms'              => true,
            'onboarding_completed_at' => now(),
            'subscription_ends_at'    => now()->addMonth(),
        ]);
    }

    private function room(int $hotelId, string $number, int $capacity = 2): Room
    {
        app(TenantManager::class)->setHotelId($hotelId);
        $type = Type::firstOrCreate(['name' => 'Std'], ['capacity' => 2, 'information' => 'x']);

        return Room::create([
            'type_id'        => $type->id,
            'room_status_id' => RoomStatus::where('code', 'AVL')->value('id'),
            'number'         => $number,
            'capacity'       => $capacity,
            'price'          => 40000,
            'view'           => '',
        ]);
    }

    public function test_public_search_is_scoped_and_excludes_booked_rooms(): void
    {
        $a = $this->hotel('Hotel Public A');
        $b = $this->hotel('Hotel Public B');

        $roomFree   = $this->room($a->id, 'FREE101');
        $roomBooked = $this->room($a->id, 'BOOKED777');
        $this->room($b->id, 'OTHERHOTEL9'); // autre hôtel : ne doit jamais apparaître

        // Réserver roomBooked sur la période demandée
        app(TenantManager::class)->setHotelId($a->id);
        $agent    = User::factory()->create(['role' => 'Admin', 'hotel_id' => $a->id]);
        $customer = Customer::create(['name' => 'X', 'email' => 'x@x.test', 'phone' => '+229 01', 'gender' => 'Male', 'user_id' => $agent->id]);
        Transaction::create([
            'user_id' => $agent->id, 'customer_id' => $customer->id, 'room_id' => $roomBooked->id,
            'check_in' => now()->addDays(3)->format('Y-m-d'),
            'check_out' => now()->addDays(5)->format('Y-m-d'),
            'status' => 'reservation', 'person_count' => 1, 'total_price' => 80000,
        ]);

        app(TenantManager::class)->forget();

        $res = $this->get('/h/'.$a->slug.'/reserver?check_in='.now()->addDays(3)->format('Y-m-d')
            .'&check_out='.now()->addDays(5)->format('Y-m-d').'&guests=1');

        $res->assertOk();
        $res->assertSee('FREE101');        // chambre libre de cet hôtel
        $res->assertDontSee('BOOKED777');  // occupée sur la période -> exclue
        $res->assertDontSee('OTHERHOTEL9'); // autre hôtel -> jamais visible
    }

    public function test_public_search_rejects_past_dates(): void
    {
        $a = $this->hotel('Hotel Public C');
        $this->room($a->id, 'ROOMX');
        app(TenantManager::class)->forget();

        $res = $this->get('/h/'.$a->slug.'/reserver?check_in='.now()->subDays(2)->format('Y-m-d')
            .'&check_out='.now()->addDay()->format('Y-m-d').'&guests=1');

        $res->assertOk();
        $res->assertSee('passé'); // message d'erreur "ne peut pas être dans le passé"
    }
}
