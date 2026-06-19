<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\User;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PlatformHttpTest extends TestCase
{
    use RefreshDatabase;

    private function makeHotel(string $name, array $attrs = []): Hotel
    {
        return Hotel::create(array_merge([
            'name'      => $name,
            'slug'      => \Illuminate\Support\Str::slug($name),
            'is_active' => true,
        ], $attrs));
    }

    private function superAdmin(): User
    {
        return User::factory()->create([
            'role'     => 'Super',
            'hotel_id' => null,
        ]);
    }

    private function hotelAdmin(Hotel $hotel): User
    {
        return User::factory()->create([
            'role'     => 'Admin',
            'hotel_id' => $hotel->id,
        ]);
    }

    public function test_super_admin_can_open_platform_dashboard(): void
    {
        $this->makeHotel('Hotel Cactus');

        $response = $this->actingAs($this->superAdmin())->get('/platform/hotels');

        $response->assertOk();
        $response->assertSee('Hotel Cactus');
    }

    public function test_super_admin_can_create_hotel_with_its_admin(): void
    {
        $response = $this->actingAs($this->superAdmin())->post('/platform/hotels', [
            'name'           => 'Hotel Ibis',
            'currency'       => 'CFA',
            'admin_name'     => 'Admin Ibis',
            'admin_email'    => 'admin@ibis.test',
            'admin_password' => 'secret123',
        ]);

        $response->assertRedirectToRoute('platform.hotels.index');
        $this->assertDatabaseHas('hotels', ['name' => 'Hotel Ibis']);
        $this->assertDatabaseHas('users', ['email' => 'admin@ibis.test', 'role' => 'Admin']);

        // L'admin créé est bien rattaché au nouvel hôtel
        $hotel = Hotel::where('name', 'Hotel Ibis')->first();
        $admin = User::where('email', 'admin@ibis.test')->first();
        $this->assertEquals($hotel->id, $admin->hotel_id);
        $this->assertEquals($admin->id, $hotel->owner_user_id);
    }

    public function test_non_super_cannot_access_platform(): void
    {
        $hotel = $this->makeHotel('Hotel X');

        $response = $this->actingAs($this->hotelAdmin($hotel))->get('/platform/hotels');

        $response->assertRedirect(); // CheckRole refuse (redirect back)
        $this->assertFalse($response->isOk());
    }

    public function test_suspended_hotel_user_is_redirected(): void
    {
        $hotel = $this->makeHotel('Hotel Suspendu', ['is_active' => false]);

        $response = $this->actingAs($this->hotelAdmin($hotel))->get('/');

        $response->assertRedirect(route('hotel.suspended'));
    }

    public function test_expired_subscription_user_is_redirected(): void
    {
        $hotel = $this->makeHotel('Hotel Expiré', ['subscription_ends_at' => now()->subDay()]);

        $response = $this->actingAs($this->hotelAdmin($hotel))->get('/');

        $response->assertRedirect(route('hotel.suspended'));
    }

    public function test_toggle_suspends_and_reactivates_hotel(): void
    {
        $hotel = $this->makeHotel('Hotel Toggle');
        $super = $this->superAdmin();

        $this->actingAs($super)->patch("/platform/hotels/{$hotel->id}/toggle");
        $this->assertFalse($hotel->fresh()->is_active);

        $this->actingAs($super)->patch("/platform/hotels/{$hotel->id}/toggle");
        $this->assertTrue($hotel->fresh()->is_active);
    }
}
