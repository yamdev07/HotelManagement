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
 * Export iCal : le flux d'une chambre contient ses réservations actives
 * (dates journée-entière, DTEND exclusif) et exclut les annulées.
 */
class IcalExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_room_feed_lists_active_reservations_and_hides_cancelled(): void
    {
        $hotel = Hotel::create([
            'name' => 'Ical Hotel', 'slug' => Str::slug('Ical '.Str::random(4)),
            'is_active' => true, 'onboarding_completed_at' => now(), 'subscription_ends_at' => now()->addMonth(),
        ]);
        app(TenantManager::class)->setHotelId($hotel->id);
        $owner = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);
        $type = Type::firstOrCreate(['name' => 'Std'], ['capacity' => 2, 'information' => 'x']);
        $room = Room::create([
            'type_id' => $type->id, 'room_status_id' => RoomStatus::where('code', 'AVL')->value('id'),
            'number' => '501', 'capacity' => 2, 'price' => 30000, 'view' => '',
        ]);
        $customer = Customer::create(['name' => 'Cli', 'email' => 'c@x.test', 'phone' => '+22990', 'gender' => 'Other', 'user_id' => $owner->id]);

        $ci = now()->addDays(3); $co = now()->addDays(5);
        Transaction::create([
            'user_id' => $owner->id, 'customer_id' => $customer->id, 'room_id' => $room->id,
            'check_in' => $ci->format('Y-m-d'), 'check_out' => $co->format('Y-m-d'),
            'status' => 'reservation', 'person_count' => 1, 'total_price' => 60000,
        ]);
        Transaction::create([
            'user_id' => $owner->id, 'customer_id' => $customer->id, 'room_id' => $room->id,
            'check_in' => now()->addDays(10)->format('Y-m-d'), 'check_out' => now()->addDays(12)->format('Y-m-d'),
            'status' => 'cancelled', 'person_count' => 1, 'total_price' => 60000,
        ]);

        $token = $room->icalToken();
        app(TenantManager::class)->forget();

        $res = $this->get('/calendar/'.$token.'.ics');
        $res->assertOk();
        $res->assertHeader('Content-Type', 'text/calendar; charset=utf-8');

        $body = $res->getContent();
        $this->assertStringContainsString('BEGIN:VCALENDAR', $body);
        $this->assertStringContainsString('DTSTART;VALUE=DATE:'.$ci->format('Ymd'), $body);
        $this->assertStringContainsString('DTEND;VALUE=DATE:'.$co->format('Ymd'), $body);
        // Une seule réservation active exportée (l'annulée est masquée).
        $this->assertEquals(1, substr_count($body, 'BEGIN:VEVENT'));
    }

    public function test_unknown_token_returns_404(): void
    {
        $this->get('/calendar/inexistant.ics')->assertNotFound();
    }
}
