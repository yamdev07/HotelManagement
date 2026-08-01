<?php

namespace Tests\Feature;

use App\Enums\RoomStatus;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Type;
use App\Models\User;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Processus ménage : cycle d'une chambre sale -> en nettoyage -> disponible.
 * Confirme aussi que le statut « Dirty » (id 6) est bien exploitable après
 * un check-out (le bug de clé étrangère est corrigé).
 */
class HousekeepingCleaningTest extends TestCase
{
    use RefreshDatabase;

    /** @return array{0:User,1:Room,2:Hotel} */
    private function dirtyRoom(): array
    {
        $hotel = Hotel::create([
            'name' => 'Clean '.Str::random(4), 'slug' => Str::slug('clean '.Str::random(6)),
            'is_active' => true, 'plan' => 'pro', // le module housekeeping est réservé aux offres pro/business
            'room_limit' => config('plans.tiers.pro.room_limit'),
            'onboarding_completed_at' => now(), 'subscription_ends_at' => now()->addMonth(),
        ]);
        app(TenantManager::class)->setHotelId($hotel->id);

        $admin = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);
        $hotel->update(['owner_user_id' => $admin->id]);

        $type = Type::firstOrCreate(['name' => 'Std'], ['capacity' => 2, 'information' => 'x']);
        $room = Room::create([
            'type_id' => $type->id, 'room_status_id' => RoomStatus::Dirty->value, // sale après départ
            'number' => (string) random_int(100, 999), 'capacity' => 2, 'price' => 50000, 'view' => '',
        ]);

        app(TenantManager::class)->forget();

        return [$admin, $room, $hotel];
    }

    public function test_dirty_room_can_exist(): void
    {
        // Vérifie directement que le statut Dirty (id 6) est valide en base.
        [$admin, $room] = $this->dirtyRoom();

        $this->assertEquals(RoomStatus::Dirty->value, (int) $room->fresh()->room_status_id);
    }

    public function test_cleaning_cycle_dirty_to_available(): void
    {
        [$admin, $room] = $this->dirtyRoom();

        // Démarrer le nettoyage -> En nettoyage
        $this->actingAs($admin)
            ->post(route('housekeeping.start-cleaning', $room))
            ->assertRedirect();
        $this->assertEquals(RoomStatus::Cleaning->value, (int) $room->fresh()->room_status_id);

        // Terminer le nettoyage -> Disponible (chambre non occupée)
        $this->actingAs($admin)
            ->post(route('housekeeping.finish-cleaning', $room))
            ->assertRedirect();
        $this->assertEquals(RoomStatus::Available->value, (int) $room->fresh()->room_status_id);
    }
}
