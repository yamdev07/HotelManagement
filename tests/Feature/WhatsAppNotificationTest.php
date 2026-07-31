<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomStatus;
use App\Models\Type;
use App\Models\User;
use App\Services\WhatsAppService;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Mockery;
use Tests\TestCase;

/**
 * Le tunnel de réservation déclenche les notifications WhatsApp (client + hôtelier)
 * quand le canal est configuré, et reste totalement silencieux sinon.
 */
class WhatsAppNotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @return array{0:Hotel,1:Room} */
    private function hotelWithRoom(string $name): array
    {
        $hotel = Hotel::create([
            'name'                    => $name,
            'slug'                    => Str::slug($name.' '.Str::random(4)),
            'is_active'               => true,
            'currency'                => 'XOF',
            'contact_phone'           => '+229 21 00 00 00',
            'onboarding_completed_at' => now(),
            'subscription_ends_at'    => now()->addMonth(),
        ]);
        app(TenantManager::class)->setHotelId($hotel->id);
        $owner = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);
        $hotel->update(['owner_user_id' => $owner->id]);
        $type = Type::firstOrCreate(['name' => 'Std'], ['capacity' => 2, 'information' => 'x']);
        $room = Room::create([
            'type_id' => $type->id, 'room_status_id' => RoomStatus::where('code', 'AVL')->value('id'),
            'number' => '301', 'capacity' => 3, 'price' => 40000, 'view' => '',
        ]);

        return [$hotel, $room];
    }

    private function bookingPayload(): array
    {
        return [
            'check_in'  => now()->addDays(3)->format('Y-m-d'),
            'check_out' => now()->addDays(5)->format('Y-m-d'),
            'guests'    => 2,
            'name'      => 'Awa Cliente',
            'email'     => 'awa@voyage.test',
            'phone'     => '+229 90 11 22 33',
        ];
    }

    public function test_booking_sends_whatsapp_to_guest_and_owner_when_configured(): void
    {
        [$hotel, $room] = $this->hotelWithRoom('WA Hotel A');
        app(TenantManager::class)->forget();

        $wa = Mockery::mock(WhatsAppService::class);
        $wa->shouldReceive('isConfigured')->andReturn(true);
        // 1 message au client + 1 à l'hôtelier
        $wa->shouldReceive('sendText')->twice()->andReturn(true);
        $this->app->instance(WhatsAppService::class, $wa);

        $this->post('/h/'.$hotel->slug.'/reserver/'.$room->id, $this->bookingPayload())
            ->assertRedirect();
    }

    public function test_no_whatsapp_sent_when_not_configured(): void
    {
        [$hotel, $room] = $this->hotelWithRoom('WA Hotel B');
        app(TenantManager::class)->forget();

        $wa = Mockery::mock(WhatsAppService::class);
        $wa->shouldReceive('isConfigured')->andReturn(false);
        $wa->shouldReceive('sendText')->never();
        $this->app->instance(WhatsAppService::class, $wa);

        $this->post('/h/'.$hotel->slug.'/reserver/'.$room->id, $this->bookingPayload())
            ->assertRedirect();
    }
}
