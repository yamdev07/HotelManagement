<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\PromoCode;
use App\Models\User;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Back-office codes promo : création, contrôle d'accès, isolation inter-hôtel.
 */
class PromoCodeBackofficeTest extends TestCase
{
    use RefreshDatabase;

    /** @return array{0:Hotel,1:User} */
    private function hotelAdmin(string $name): array
    {
        $hotel = Hotel::create([
            'name' => $name, 'slug' => Str::slug($name.' '.Str::random(4)),
            'is_active' => true, 'onboarding_completed_at' => now(), 'subscription_ends_at' => now()->addMonth(),
        ]);
        app(TenantManager::class)->setHotelId($hotel->id);
        $admin = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);
        app(TenantManager::class)->forget();

        return [$hotel, $admin];
    }

    public function test_admin_creates_promo_code(): void
    {
        [$hotel, $admin] = $this->hotelAdmin('Promo A');

        $this->actingAs($admin)->post('/promos', [
            'code' => 'bienvenue10', 'type' => 'percent', 'value' => 10, 'min_nights' => 1,
        ])->assertRedirect();

        $this->assertDatabaseHas('promo_codes', [
            'hotel_id' => $hotel->id, 'code' => 'BIENVENUE10', 'type' => 'percent',
        ]);
    }

    public function test_percent_over_100_is_rejected(): void
    {
        [$hotel, $admin] = $this->hotelAdmin('Promo B');

        $this->actingAs($admin)->post('/promos', [
            'code' => 'TROP', 'type' => 'percent', 'value' => 150,
        ])->assertSessionHasErrors('value');
    }

    public function test_admin_cannot_delete_other_hotel_code(): void
    {
        [$hotelA, $adminA] = $this->hotelAdmin('Promo Own');
        [$hotelB] = $this->hotelAdmin('Promo Other');
        app(TenantManager::class)->setHotelId($hotelB->id);
        $codeB = PromoCode::create(['hotel_id' => $hotelB->id, 'code' => 'OTHER', 'type' => 'percent', 'value' => 5, 'min_nights' => 1]);
        app(TenantManager::class)->forget();

        $this->actingAs($adminA)->delete('/promos/'.$codeB->id)->assertNotFound();
        $this->assertDatabaseHas('promo_codes', ['id' => $codeB->id]);
    }

    public function test_receptionist_cannot_access_promos(): void
    {
        [$hotel] = $this->hotelAdmin('Promo C');
        app(TenantManager::class)->setHotelId($hotel->id);
        $rec = User::factory()->create(['role' => 'Receptionist', 'hotel_id' => $hotel->id]);
        app(TenantManager::class)->forget();

        $res = $this->actingAs($rec)->get('/promos');
        $this->assertNotEquals(200, $res->getStatusCode());
    }
}
