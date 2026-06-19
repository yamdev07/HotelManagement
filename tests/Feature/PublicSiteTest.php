<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomStatus;
use App\Models\Type;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        RoomStatus::firstOrCreate(
            ['id' => Room::STATUS_AVAILABLE],
            ['name' => 'Disponible', 'code' => 'AVL', 'information' => 'Disponible']
        );
    }

    private function makeHotelWithRoom(string $name, string $roomName, array $attrs = []): Hotel
    {
        $hotel = Hotel::create(array_merge([
            'name'      => $name,
            'slug'      => Str::slug($name),
            'is_active' => true,
        ], $attrs));

        app(TenantManager::class)->withHotel($hotel->id, function () use ($roomName) {
            $type = Type::create(['name' => 'Standard', 'information' => 'Type standard']);
            $room = new Room();
            $room->number = '101';
            $room->name = $roomName;
            $room->type_id = $type->id;
            $room->room_status_id = Room::STATUS_AVAILABLE;
            $room->price = 25000;
            $room->capacity = 2;
            $room->view = 'Jardin';
            $room->save();
        });

        app(TenantManager::class)->forget();

        return $hotel;
    }

    public function test_public_site_is_accessible_by_slug(): void
    {
        $this->makeHotelWithRoom('Hotel Soleil', 'Chambre Soleil', ['tagline' => 'Votre confort']);

        $response = $this->get('/h/hotel-soleil');

        $response->assertOk();
        $response->assertSee('Hotel Soleil');
        $response->assertSee('Votre confort');
    }

    public function test_public_site_shows_only_its_own_rooms(): void
    {
        $this->makeHotelWithRoom('Hotel A', 'Chambre Alpha');
        $this->makeHotelWithRoom('Hotel B', 'Chambre Beta');

        $response = $this->get('/h/hotel-a');

        $response->assertOk();
        $response->assertSee('Chambre Alpha');
        $response->assertDontSee('Chambre Beta');
    }

    public function test_suspended_hotel_site_is_unavailable(): void
    {
        $this->makeHotelWithRoom('Hotel Ferme', 'Chambre', ['is_active' => false]);

        $response = $this->get('/h/hotel-ferme');

        $response->assertStatus(503);
        $response->assertSee('indisponible');
    }

    public function test_unknown_slug_returns_404(): void
    {
        $this->get('/h/inconnu')->assertNotFound();
    }
}
