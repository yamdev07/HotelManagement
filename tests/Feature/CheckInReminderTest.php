<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomStatus;
use App\Models\Transaction;
use App\Models\Type;
use App\Models\User;
use App\Services\WhatsAppService;
use App\Support\TenantManager;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Mockery;
use Tests\TestCase;

/**
 * La commande whatsapp:reminders n'envoie qu'aux arrivées du lendemain
 * (pas aujourd'hui, pas les réservations annulées).
 */
class CheckInReminderTest extends TestCase
{
    use RefreshDatabase;

    private function reservation(Hotel $hotel, Room $room, User $owner, string $checkIn, string $status): Transaction
    {
        $customer = Customer::create([
            'name' => 'Cli '.Str::random(3), 'email' => Str::random(6).'@x.test',
            'phone' => '+229 90 11 22 33', 'gender' => 'Other', 'user_id' => $owner->id,
        ]);

        return Transaction::create([
            'user_id' => $owner->id, 'customer_id' => $customer->id, 'room_id' => $room->id,
            'check_in' => $checkIn, 'check_out' => Carbon::parse($checkIn)->addDays(2)->format('Y-m-d'),
            'status' => $status, 'person_count' => 1, 'total_price' => 40000,
        ]);
    }

    public function test_only_tomorrow_reservations_are_reminded(): void
    {
        $hotel = Hotel::create([
            'name' => 'Rem Hotel', 'slug' => Str::slug('Rem '.Str::random(4)),
            'is_active' => true, 'currency' => 'XOF',
            'onboarding_completed_at' => now(), 'subscription_ends_at' => now()->addMonth(),
        ]);
        app(TenantManager::class)->setHotelId($hotel->id);
        $owner = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);
        $hotel->update(['owner_user_id' => $owner->id]);
        $type = Type::firstOrCreate(['name' => 'Std'], ['capacity' => 2, 'information' => 'x']);
        $room = Room::create([
            'type_id' => $type->id, 'room_status_id' => RoomStatus::where('code', 'AVL')->value('id'),
            'number' => '401', 'capacity' => 3, 'price' => 40000, 'view' => '',
        ]);

        $tomorrow = now()->addDay()->format('Y-m-d');
        $this->reservation($hotel, $room, $owner, $tomorrow, 'reservation');          // ✅ éligible
        $this->reservation($hotel, $room, $owner, now()->format('Y-m-d'), 'reservation'); // ❌ aujourd'hui
        $this->reservation($hotel, $room, $owner, $tomorrow, 'cancelled');            // ❌ annulée

        app(TenantManager::class)->forget();

        $wa = Mockery::mock(WhatsAppService::class);
        $wa->shouldReceive('isConfigured')->andReturn(true);
        $wa->shouldReceive('sendText')->once()->andReturn(true); // une seule arrivée demain
        $this->app->instance(WhatsAppService::class, $wa);

        $this->artisan('whatsapp:reminders')->assertExitCode(0);
    }
}
