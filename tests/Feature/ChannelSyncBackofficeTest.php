<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomCalendarFeed;
use App\Models\RoomStatus;
use App\Models\Type;
use App\Models\User;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Back-office « Synchronisation » : l'hôtelier voit le lien iCal de ses chambres,
 * ajoute/retire des calendriers OTA, sans pouvoir toucher un autre hôtel.
 */
class ChannelSyncBackofficeTest extends TestCase
{
    use RefreshDatabase;

    /** @return array{0:Hotel,1:User,2:Room} */
    private function hotelAdminRoom(string $name, string $number): array
    {
        $hotel = Hotel::create([
            'name' => $name, 'slug' => Str::slug($name.' '.Str::random(4)),
            'is_active' => true, 'onboarding_completed_at' => now(), 'subscription_ends_at' => now()->addMonth(),
        ]);
        app(TenantManager::class)->setHotelId($hotel->id);
        $admin = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);
        $hotel->update(['owner_user_id' => $admin->id]);
        $type = Type::firstOrCreate(['name' => 'Std'], ['capacity' => 2, 'information' => 'x']);
        $room = Room::create([
            'type_id' => $type->id, 'room_status_id' => RoomStatus::where('code', 'AVL')->value('id'),
            'number' => $number, 'capacity' => 2, 'price' => 30000, 'view' => '',
        ]);
        app(TenantManager::class)->forget();

        return [$hotel, $admin, $room];
    }

    public function test_admin_sees_export_link_and_can_add_then_remove_feed(): void
    {
        [$hotel, $admin, $room] = $this->hotelAdminRoom('Chan A', '701');

        $this->actingAs($admin)->get('/canaux')
            ->assertOk()
            ->assertSee('Chambre 701')
            ->assertSee('/calendar/'); // lien d'export présent

        // Ajout d'un calendrier OTA
        $this->actingAs($admin)->post('/canaux/chambre/'.$room->id.'/calendrier', [
            'source' => 'Booking.com',
            'url' => 'https://booking.example/ical/701.ics',
        ])->assertRedirect();

        $this->assertDatabaseHas('room_calendar_feeds', [
            'room_id' => $room->id, 'source' => 'Booking.com',
        ]);

        $feed = RoomCalendarFeed::where('room_id', $room->id)->first();
        $this->actingAs($admin)->delete('/canaux/calendrier/'.$feed->id)->assertRedirect();
        $this->assertDatabaseMissing('room_calendar_feeds', ['id' => $feed->id]);
    }

    public function test_admin_cannot_add_feed_to_another_hotel_room(): void
    {
        [$hotelA, $adminA] = $this->hotelAdminRoom('Chan Own', '801');
        [$hotelB, $adminB, $roomB] = $this->hotelAdminRoom('Chan Other', '802');

        // adminA tente d'ajouter un flux à la chambre de l'hôtel B => introuvable (scopé).
        $this->actingAs($adminA)->post('/canaux/chambre/'.$roomB->id.'/calendrier', [
            'source' => 'Airbnb',
            'url' => 'https://airbnb.example/ical/802.ics',
        ])->assertNotFound();

        $this->assertDatabaseMissing('room_calendar_feeds', ['room_id' => $roomB->id]);
    }
}
