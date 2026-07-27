<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PlanModuleTest extends TestCase
{
    use RefreshDatabase;

    private function adminOf(string $plan): User
    {
        $hotel = Hotel::create([
            'name' => 'Hotel '.$plan,
            'slug' => Str::slug('Hotel '.$plan.' '.Str::random(4)),
            'is_active' => true,
            'plan' => $plan,
            'room_limit' => config('plans.tiers.'.$plan.'.room_limit'),
            'onboarding_completed_at' => now(),
            'subscription_ends_at' => now()->addMonth(),
        ]);

        return User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);
    }

    public function test_module_matrix_matches_plans(): void
    {
        $starter = Hotel::make(['plan' => 'starter']);
        $pro = Hotel::make(['plan' => 'pro']);
        $business = Hotel::make(['plan' => 'business']);

        $this->assertFalse($starter->hasModule('restaurant'));
        $this->assertFalse($starter->hasModule('housekeeping'));
        $this->assertFalse($starter->hasModule('reports'));

        foreach (['restaurant', 'housekeeping', 'reports'] as $m) {
            $this->assertTrue($pro->hasModule($m), "pro should have $m");
            $this->assertTrue($business->hasModule($m), "business should have $m");
        }
    }

    public function test_starter_is_blocked_from_premium_modules(): void
    {
        $admin = $this->adminOf('starter');

        foreach (['/restaurant', '/housekeeping', '/reports'] as $url) {
            $this->actingAs($admin)->get($url)
                ->assertRedirect(route('billing.show'));
        }
    }

    public function test_pro_can_reach_reports(): void
    {
        $admin = $this->adminOf('pro');

        $res = $this->actingAs($admin)->get('/reports');
        // Autorisé : ne doit PAS être renvoyé vers la page d'abonnement.
        $this->assertFalse(
            $res->isRedirect(route('billing.show')),
            'Pro should not be redirected to billing for reports'
        );
    }
}
