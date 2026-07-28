<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomStatus;
use App\Models\Type;
use App\Repositories\Implementation\ReservationRepository;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Issue #193 : « le système annonce des chambres disponibles mais la liste
 * de recherche est vide ». Cause : le compteur et la liste utilisaient des
 * filtres différents (la liste exigeait un statut codé en dur [1,3]).
 */
class ReservationRoomSearchTest extends TestCase
{
    use RefreshDatabase;

    private function makeHotel(): Hotel
    {
        $hotel = Hotel::create([
            'name' => 'Hotel '.Str::random(5),
            'slug' => Str::slug('Hotel '.Str::random(6)),
            'is_active' => true,
            'onboarding_completed_at' => now(),
            'subscription_ends_at' => now()->addMonth(),
        ]);
        app(TenantManager::class)->setHotelId($hotel->id);

        return $hotel;
    }

    private function makeRoom(string $number, string $statusCode, int $capacity = 2): Room
    {
        $type = Type::firstOrCreate(
            ['name' => 'Std'],
            ['capacity' => 2, 'information' => 'x']
        );
        $statusId = RoomStatus::where('code', $statusCode)->value('id');

        return Room::create([
            'type_id' => $type->id,
            'room_status_id' => $statusId,
            'number' => $number,
            'capacity' => $capacity,
            'price' => 100,
            'view' => '',
        ]);
    }

    public function test_count_matches_list_and_maintenance_is_excluded(): void
    {
        $this->makeHotel();

        $available = $this->makeRoom('101', 'AVL'); // disponible
        $occupiedNow = $this->makeRoom('102', 'OCC'); // occupée AUJOURD'HUI mais libre pour d'autres dates
        $maintenance = $this->makeRoom('103', 'MNT'); // hors service → jamais réservable

        $repo = new ReservationRepository;
        $request = new Request(['count_person' => 1]);
        $occupied = collect(); // aucun conflit de dates

        $list = $repo->getUnocuppiedroom($request, $occupied);
        $count = $repo->countUnocuppiedroom($request, $occupied);

        // Le compteur reflète EXACTEMENT la liste (plus de décalage).
        $this->assertEquals($count, $list->total());

        // La chambre dispo et la chambre occupée-mais-libre-pour-ces-dates sont là.
        $this->assertEquals(2, $count);
        $ids = $list->pluck('id')->all();
        $this->assertContains($available->id, $ids);
        $this->assertContains($occupiedNow->id, $ids);

        // La chambre en maintenance est exclue.
        $this->assertNotContains($maintenance->id, $ids);
    }
}
