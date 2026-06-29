<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class OnboardingTest extends TestCase
{
    use RefreshDatabase;

    private function makeHotel(bool $onboarded): Hotel
    {
        return Hotel::create([
            'name'                    => 'Hotel Onb',
            'slug'                    => Str::slug('Hotel Onb '.Str::random(4)),
            'is_active'               => true,
            'onboarding_completed_at' => $onboarded ? now() : null,
        ]);
    }

    public function test_admin_of_unconfigured_hotel_is_forced_to_onboarding(): void
    {
        $hotel = $this->makeHotel(onboarded: false);
        $admin = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);

        $this->actingAs($admin)->get('/')->assertRedirect(route('onboarding.show'));
    }

    public function test_onboarding_applies_settings_and_marks_completed(): void
    {
        $hotel = $this->makeHotel(onboarded: false);
        $admin = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);

        $response = $this->actingAs($admin)->post('/bienvenue', [
            'name'            => 'Hotel Personnalisé',
            'tagline'         => 'Le meilleur accueil',
            'primary_color'   => '#1e6b2e',
            'secondary_color' => '#0f3d1a',
        ]);

        $response->assertRedirect('/home');
        $hotel->refresh();
        $this->assertEquals('Hotel Personnalisé', $hotel->name);
        $this->assertEquals('Le meilleur accueil', $hotel->tagline);
        $this->assertEquals('#1e6b2e', $hotel->primary_color);
        $this->assertNotNull($hotel->onboarding_completed_at);
        $this->assertFalse($hotel->needsOnboarding());
    }

    public function test_configured_hotel_admin_is_not_redirected_to_onboarding(): void
    {
        $hotel = $this->makeHotel(onboarded: true);
        $admin = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);

        // L'accès à l'accueil ne doit PAS rediriger vers l'onboarding
        $response = $this->actingAs($admin)->get('/');
        if ($response->isRedirect()) {
            $this->assertNotEquals(route('onboarding.show'), $response->headers->get('Location'));
        } else {
            $response->assertOk();
        }
    }
}
