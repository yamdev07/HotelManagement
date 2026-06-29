<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class HotelSiteSettingsTest extends TestCase
{
    use RefreshDatabase;

    private function makeHotel(array $attrs = []): Hotel
    {
        return Hotel::create(array_merge([
            'name'                    => 'Hotel Test',
            'slug'                    => Str::slug('Hotel Test '.Str::random(4)),
            'is_active'               => true,
            'onboarding_completed_at' => now(),
        ], $attrs));
    }

    public function test_disabled_sections_are_not_rendered_on_public_site(): void
    {
        $hotel = $this->makeHotel([
            'slug'            => 'hotel-toggle',
            'show_rooms'      => false,
            'show_restaurant' => false,
            'show_services'   => true,
            'show_contact'    => false,
        ]);

        $response = $this->get('/h/hotel-toggle');

        $response->assertOk();
        $response->assertSee('Nos services');       // activée
        $response->assertDontSee('Nos chambres');    // désactivée
        $response->assertDontSee('Notre restaurant');// désactivée
        $response->assertDontSee('Nous contacter');  // désactivée
    }

    public function test_admin_can_define_custom_services_and_socials(): void
    {
        $hotel = $this->makeHotel();
        $admin = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);

        $this->actingAs($admin)->put('/mon-etablissement', [
            'name'     => $hotel->name,
            'services' => [
                ['icon' => 'fa-spa', 'title' => 'Spa & Massage', 'description' => 'Détente absolue'],
                ['icon' => 'fa-water', 'title' => '', 'description' => 'ligne vide ignorée'],
            ],
            'socials'  => ['facebook' => 'https://facebook.com/monhotel', 'instagram' => ''],
            'about_title' => 'Notre maison',
        ])->assertRedirectToRoute('hotel.settings.edit');

        $hotel->refresh();
        $this->assertCount(1, $hotel->services); // la ligne vide est filtrée
        $this->assertEquals('Spa & Massage', $hotel->services[0]['title']);
        $this->assertEquals(['facebook' => 'https://facebook.com/monhotel'], $hotel->socials);
        $this->assertEquals('Notre maison', $hotel->about_title);
        $this->assertEquals('Spa & Massage', $hotel->siteServices()[0]['title']);
    }

    public function test_admin_can_update_site_content_and_toggles(): void
    {
        $hotel = $this->makeHotel();
        $admin = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);

        $response = $this->actingAs($admin)->put('/mon-etablissement', [
            'name'            => $hotel->name,
            'tagline'         => 'Le meilleur accueil',
            'description'     => 'Un établissement chaleureux au cœur de la ville.',
            'show_rooms'      => '1',
            'show_restaurant' => '0',
            'show_services'   => '1',
            'show_contact'    => '0',
        ]);

        $response->assertRedirectToRoute('hotel.settings.edit');
        $hotel->refresh();
        $this->assertEquals('Le meilleur accueil', $hotel->tagline);
        $this->assertStringContainsString('chaleureux', $hotel->description);
        $this->assertTrue($hotel->show_rooms);
        $this->assertFalse($hotel->show_restaurant);
        $this->assertFalse($hotel->show_contact);
    }
}
