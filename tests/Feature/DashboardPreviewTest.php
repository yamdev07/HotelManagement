<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class DashboardPreviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_new_dashboard_preview(): void
    {
        $hotel = Hotel::create([
            'name'                    => 'Hotel Preview',
            'slug'                    => Str::slug('Hotel Preview '.Str::random(4)),
            'is_active'               => true,
            'onboarding_completed_at' => now(),
            'subscription_ends_at'    => now()->addMonth(),
        ]);
        $admin = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);

        $this->actingAs($admin)
            ->get(route('dashboard.preview'))
            ->assertOk()
            ->assertSee('Taux d\'occupation')
            ->assertSee('Réservations du jour')
            ->assertSee('Housekeeping');
    }

    public function test_receptionist_cannot_open_preview(): void
    {
        $hotel = Hotel::create([
            'name'                    => 'Hotel Preview 2',
            'slug'                    => Str::slug('Hotel Preview2 '.Str::random(4)),
            'is_active'               => true,
            'onboarding_completed_at' => now(),
            'subscription_ends_at'    => now()->addMonth(),
        ]);
        $recept = User::factory()->create(['role' => 'Receptionist', 'hotel_id' => $hotel->id]);

        // Route réservée Super/Admin : le réceptionniste est redirigé (pas d'accès)
        $res = $this->actingAs($recept)->get(route('dashboard.preview'));
        $this->assertContains($res->status(), [302, 403]);
        $res->assertDontSee('Taux d\'occupation');
    }
}
