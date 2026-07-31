<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomBlock;
use App\Models\RoomCalendarFeed;
use App\Models\RoomStatus;
use App\Models\Type;
use App\Models\User;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Import iCal (sync entrante) : une réservation Booking/Airbnb importée bloque
 * la chambre sur la vitrine, et la disparition du flux libère la date.
 */
class IcalImportTest extends TestCase
{
    use RefreshDatabase;

    private Hotel $hotel;

    private Room $blocked;

    private Room $free;

    private RoomCalendarFeed $feed;

    private function setUpData(): void
    {
        $this->hotel = Hotel::create([
            'name' => 'OTA Hotel', 'slug' => Str::slug('OTA '.Str::random(4)),
            'is_active' => true, 'onboarding_completed_at' => now(), 'subscription_ends_at' => now()->addMonth(),
        ]);
        app(TenantManager::class)->setHotelId($this->hotel->id);
        $owner = User::factory()->create(['role' => 'Admin', 'hotel_id' => $this->hotel->id]);
        $this->hotel->update(['owner_user_id' => $owner->id]);
        $avl = RoomStatus::where('code', 'AVL')->value('id');
        $type = Type::firstOrCreate(['name' => 'Std'], ['capacity' => 2, 'information' => 'x']);

        $this->blocked = Room::create(['type_id' => $type->id, 'room_status_id' => $avl, 'number' => '601', 'capacity' => 2, 'price' => 30000, 'view' => '']);
        $this->free = Room::create(['type_id' => $type->id, 'room_status_id' => $avl, 'number' => '602', 'capacity' => 2, 'price' => 30000, 'view' => '']);

        $this->feed = RoomCalendarFeed::create([
            'hotel_id' => $this->hotel->id, 'room_id' => $this->blocked->id,
            'source' => 'Booking.com', 'url' => 'https://booking.example/ical/601.ics',
        ]);
    }

    private function icsWith(string $startYmd, string $endYmd): string
    {
        return implode("\r\n", [
            'BEGIN:VCALENDAR', 'VERSION:2.0',
            'BEGIN:VEVENT',
            'UID:booking-777@booking.com',
            'DTSTART;VALUE=DATE:'.$startYmd,
            'DTEND;VALUE=DATE:'.$endYmd,
            'SUMMARY:CLOSED - Not available',
            'END:VEVENT',
            'END:VCALENDAR', '',
        ]);
    }

    public function test_imported_ota_booking_blocks_room_on_public_availability(): void
    {
        $this->setUpData();
        $start = now()->addDays(9);
        $end = now()->addDays(11);
        Http::fake(['*' => Http::response($this->icsWith($start->format('Ymd'), $end->format('Ymd')), 200)]);

        app(TenantManager::class)->forget();
        $this->artisan('ical:sync')->assertExitCode(0);

        $this->assertDatabaseHas('room_blocks', [
            'room_id' => $this->blocked->id,
            'external_uid' => 'booking-777@booking.com',
            'source' => 'Booking.com',
        ]);

        // Recherche chevauchant la période bloquée : 601 masquée, 602 visible.
        $res = $this->get('/h/'.$this->hotel->slug.'/reserver?'.http_build_query([
            'check_in' => $start->format('Y-m-d'),
            'check_out' => $start->copy()->addDay()->format('Y-m-d'),
            'guests' => 1,
        ]));
        $res->assertOk();
        $res->assertSee('Chambre 602');
        $res->assertDontSee('Chambre 601');
    }

    public function test_disappearing_event_releases_the_block(): void
    {
        $this->setUpData();
        $start = now()->addDays(9);
        $end = now()->addDays(11);

        // 1er sync -> bloc créé ; 2e sync sur flux vide (résa OTA annulée) -> purgé.
        Http::fakeSequence()
            ->push($this->icsWith($start->format('Ymd'), $end->format('Ymd')), 200)
            ->push("BEGIN:VCALENDAR\r\nVERSION:2.0\r\nEND:VCALENDAR\r\n", 200);

        app(TenantManager::class)->forget();
        $this->artisan('ical:sync');
        $this->assertEquals(1, RoomBlock::where('feed_id', $this->feed->id)->count());

        $this->artisan('ical:sync');
        $this->assertEquals(0, RoomBlock::where('feed_id', $this->feed->id)->count());
    }
}
