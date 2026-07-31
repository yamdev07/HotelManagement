<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\User;
use App\Services\FedaPayService;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Mockery;
use Tests\TestCase;

/**
 * Changement de formule en cours d'abonnement :
 *  - montée en gamme : immédiate, avoir au prorata des jours restants déduit
 *  - descente en gamme : programmée en fin de cycle, sans paiement
 *  - renouvellement / réactivation : plein tarif
 */
class BillingPlanChangeTest extends TestCase
{
    use RefreshDatabase;

    /** @return array{0:Hotel,1:User} */
    private function hotelOn(string $plan, bool $active = true): array
    {
        $hotel = Hotel::create([
            'name' => 'Bill '.Str::random(4), 'slug' => Str::slug('bill '.Str::random(5)),
            'country' => 'BJ', 'is_active' => true, 'plan' => $plan,
            'onboarding_completed_at' => now(),
            'subscription_ends_at' => $active ? now()->addDays(30) : now()->subDays(2),
        ]);
        app(TenantManager::class)->setHotelId($hotel->id);
        $admin = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);
        $hotel->update(['owner_user_id' => $admin->id]);
        app(TenantManager::class)->forget();

        return [$hotel, $admin];
    }

    private function fakeFedapay(): void
    {
        $mock = Mockery::mock(FedaPayService::class);
        $mock->shouldReceive('isConfigured')->andReturn(true);
        $mock->shouldReceive('createCheckout')->andReturn(['transaction_id' => 99, 'url' => 'https://pay.test/go']);
        $this->app->instance(FedaPayService::class, $mock);
    }

    public function test_downgrade_is_scheduled_without_payment(): void
    {
        [$hotel, $admin] = $this->hotelOn('business');

        $this->actingAs($admin)->post('/abonnement/payer', ['plan' => 'starter', 'months' => 1])
            ->assertRedirect(route('billing.show'));

        $hotel->refresh();
        $this->assertSame('starter', $hotel->pending_plan);      // programmé
        $this->assertSame('business', $hotel->plan);             // formule actuelle conservée
        $this->assertNotNull($hotel->pending_plan_effective_at);
        $this->assertDatabaseCount('subscriptions', 0);          // aucun paiement/abonnement créé
    }

    public function test_scheduled_change_can_be_cancelled(): void
    {
        [$hotel, $admin] = $this->hotelOn('business');
        $hotel->scheduleDowngrade('starter');

        $this->actingAs($admin)->post('/abonnement/annuler-changement')
            ->assertRedirect(route('billing.show'));

        $this->assertNull($hotel->refresh()->pending_plan);
    }

    public function test_upgrade_deducts_prorated_credit(): void
    {
        [$hotel, $admin] = $this->hotelOn('starter'); // 30 jours restants => avoir ≈ 25 000
        $this->fakeFedapay();

        $this->actingAs($admin)->post('/abonnement/payer', ['plan' => 'pro', 'months' => 1])
            ->assertRedirect('https://pay.test/go');

        $pending = session('billing.pending');
        // pro (45 000) − avoir (25 000) = 20 000, nouveau cycle immédiat.
        $this->assertSame(20000, $pending['amount']);
        $this->assertTrue($pending['reset']);
    }

    public function test_reactivation_charges_full_price(): void
    {
        [$hotel, $admin] = $this->hotelOn('starter', active: false); // expiré => pas d'avoir
        $this->fakeFedapay();

        $this->actingAs($admin)->post('/abonnement/payer', ['plan' => 'pro', 'months' => 1])
            ->assertRedirect('https://pay.test/go');

        $pending = session('billing.pending');
        $this->assertSame(45000, $pending['amount']);
        $this->assertFalse($pending['reset']);
    }

    public function test_pending_downgrade_applies_when_due(): void
    {
        [$hotel, $admin] = $this->hotelOn('business');
        $hotel->forceFill([
            'pending_plan' => 'starter',
            'pending_plan_effective_at' => now()->subDay(),
        ])->save();

        $hotel->applyDuePendingPlan();

        $hotel->refresh();
        $this->assertSame('starter', $hotel->plan);
        $this->assertNull($hotel->pending_plan);
        $this->assertSame(config('plans.tiers.starter.room_limit'), $hotel->room_limit);
    }
}
