<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HotelBrandingTest extends TestCase
{
    use RefreshDatabase;

    private function hotelWithAdmin(): array
    {
        $hotel = Hotel::create([
            'name'                    => 'Hotel Cactus',
            'slug'                    => 'hotel-cactus',
            'is_active'               => true,
            'onboarding_completed_at' => now(),
        ]);

        $admin = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);

        return [$hotel, $admin];
    }

    public function test_admin_can_open_branding_settings(): void
    {
        [$hotel, $admin] = $this->hotelWithAdmin();

        $response = $this->actingAs($admin)->get('/mon-etablissement');

        $response->assertOk();
        $response->assertSee('Couleurs de la marque');
    }

    public function test_admin_can_update_colors_and_info(): void
    {
        [$hotel, $admin] = $this->hotelWithAdmin();

        $response = $this->actingAs($admin)->put('/mon-etablissement', [
            'name'            => 'Hotel Cactus Premium',
            'primary_color'   => '#ff8800',
            'secondary_color' => '#222222',
            'contact_email'   => 'contact@cactus.test',
        ]);

        $response->assertRedirectToRoute('hotel.settings.edit');
        $hotel->refresh();
        $this->assertEquals('Hotel Cactus Premium', $hotel->name);
        $this->assertEquals('#ff8800', $hotel->primary_color);
        $this->assertEquals('contact@cactus.test', $hotel->contact_email);
    }

    public function test_admin_can_upload_logo(): void
    {
        Storage::fake('public');
        [$hotel, $admin] = $this->hotelWithAdmin();

        $response = $this->actingAs($admin)->put('/mon-etablissement', [
            'name' => $hotel->name,
            'logo' => UploadedFile::fake()->image('logo.png', 200, 200),
        ]);

        $response->assertRedirectToRoute('hotel.settings.edit');
        $hotel->refresh();
        $this->assertNotNull($hotel->logo);
        Storage::disk('public')->assertExists($hotel->logo);
    }

    public function test_invalid_color_is_rejected(): void
    {
        [$hotel, $admin] = $this->hotelWithAdmin();

        $response = $this->actingAs($admin)->put('/mon-etablissement', [
            'name'          => $hotel->name,
            'primary_color' => 'not-a-color',
        ]);

        $response->assertSessionHasErrors('primary_color');
    }
}
