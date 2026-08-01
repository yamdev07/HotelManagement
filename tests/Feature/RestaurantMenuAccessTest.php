<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\User;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Activer/désactiver un plat (toggle-status) est une action réservée au staff.
 * Un visiteur non authentifié ne doit pas pouvoir la déclencher.
 */
class RestaurantMenuAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_toggle_menu_status(): void
    {
        // Le middleware 'auth' s'exécute avant tout : le visiteur est redirigé vers login.
        $this->post('/restaurant/menus/1/toggle-status')
            ->assertRedirect(route('login'));
    }

    public function test_unauthorized_role_cannot_toggle_menu_status(): void
    {
        $hotel = Hotel::create([
            'name' => 'Resto Hotel', 'slug' => Str::slug('resto '.Str::random(4)),
            'is_active' => true, 'onboarding_completed_at' => now(), 'subscription_ends_at' => now()->addMonth(),
        ]);
        app(TenantManager::class)->setHotelId($hotel->id);
        $cashier = User::factory()->create(['role' => 'Cashier', 'hotel_id' => $hotel->id]);
        app(TenantManager::class)->forget();

        // Caissier : rôle non autorisé pour la gestion du menu -> refusé (pas 200).
        $res = $this->actingAs($cashier)->post('/restaurant/menus/1/toggle-status');
        $this->assertNotEquals(200, $res->getStatusCode());
    }
}
