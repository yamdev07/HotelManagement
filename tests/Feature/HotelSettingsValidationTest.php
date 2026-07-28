<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\User;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Issue #184 : la personnalisation de l'établissement n'avait aucune validation.
 */
class HotelSettingsValidationTest extends TestCase
{
    use RefreshDatabase;

    private function ownerOf(): array
    {
        $hotel = Hotel::create([
            'name' => 'Hotel Perso',
            'slug' => Str::slug('Hotel Perso '.Str::random(4)),
            'is_active' => true,
            'onboarding_completed_at' => now(),
            'subscription_ends_at' => now()->addMonth(),
        ]);
        app(TenantManager::class)->setHotelId($hotel->id);
        $admin = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);
        $hotel->update(['owner_user_id' => $admin->id]);

        return [$hotel, $admin];
    }

    public function test_settings_reject_garbage_name_bad_phone_and_bad_url(): void
    {
        [, $admin] = $this->ownerOf();

        app(TenantManager::class)->forget();
        $this->actingAs($admin)->put(route('hotel.settings.update'), [
            'name' => '***',                 // charabia
            'contact_phone' => 'appelez-moi',          // lettres
            'contact_email' => 'pas-un-email',         // invalide
            'socials' => ['facebook' => 'coucou'], // pas une URL
        ])->assertSessionHasErrors(['name', 'contact_phone', 'contact_email', 'socials.facebook']);
    }

    public function test_settings_accept_valid_values(): void
    {
        [$hotel, $admin] = $this->ownerOf();

        app(TenantManager::class)->forget();
        $this->actingAs($admin)->put(route('hotel.settings.update'), [
            'name' => 'Hôtel du Lac',
            'contact_phone' => '+229 01 02 03 04',
            'contact_email' => 'contact@hotel.test',
            'primary_color' => '#2e8540',
            'socials' => ['facebook' => 'https://facebook.com/hotel'],
        ])->assertSessionHasNoErrors();

        $this->assertEquals('Hôtel du Lac', $hotel->fresh()->name);
    }
}
