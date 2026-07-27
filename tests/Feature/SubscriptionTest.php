<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    private function superAdmin(): User
    {
        return User::factory()->create(['role' => 'Super', 'hotel_id' => null]);
    }

    private function makeHotel(array $attrs = []): Hotel
    {
        return Hotel::create(array_merge([
            'name' => 'Hotel Sub',
            'slug' => Str::slug('Hotel Sub '.Str::random(4)),
            'is_active' => true,
            'plan' => 'pro',
            'onboarding_completed_at' => now(),
            'subscription_ends_at' => now()->addMonth(),
        ], $attrs));
    }

    public function test_registration_records_a_trial_subscription(): void
    {
        Mail::fake();

        $this->post('/inscription', [
            'company_name' => 'Hotel Essai',
            'plan' => 'pro',
            'admin_name' => 'X',
            'admin_email' => 'x@essai.test',
        ]);

        $hotel = Hotel::where('name', 'Hotel Essai')->first();
        $this->assertCount(1, $hotel->subscriptions);
        $sub = $hotel->subscriptions->first();
        $this->assertEquals('trial', $sub->status);
        $this->assertEquals(0.0, (float) $sub->amount);
        $this->assertFalse($sub->is_renewal);
    }

    public function test_renew_extends_subscription_and_records_renewal(): void
    {
        $hotel = $this->makeHotel();
        $oldEnd = $hotel->subscription_ends_at->copy();

        $this->actingAs($this->superAdmin())
            ->post("/platform/hotels/{$hotel->id}/renew", ['months' => 2])
            ->assertRedirect(route('platform.hotels.show', $hotel));

        $hotel->refresh();

        // Prolongé de 2 mois à partir de l'ancienne fin
        $this->assertEquals($oldEnd->copy()->addMonths(2)->toDateString(), $hotel->subscription_ends_at->toDateString());
        $this->assertTrue($hotel->is_active);

        // Un renouvellement enregistré, montant = prix mensuel * 2
        $renewal = Subscription::where('hotel_id', $hotel->id)->where('is_renewal', true)->first();
        $this->assertNotNull($renewal);
        $this->assertEquals($hotel->monthlyPrice() * 2, (float) $renewal->amount);
        $this->assertTrue($hotel->hasRenewed());
    }

    public function test_expired_hotel_is_reactivated_by_renewal(): void
    {
        $hotel = $this->makeHotel(['subscription_ends_at' => now()->subDays(5)]);
        $this->assertFalse($hotel->hasActiveAccess());

        $this->actingAs($this->superAdmin())
            ->post("/platform/hotels/{$hotel->id}/renew", ['months' => 1]);

        $this->assertTrue($hotel->fresh()->hasActiveAccess());
    }

    public function test_hotel_detail_page_shows_subscription_history(): void
    {
        $hotel = $this->makeHotel();
        $hotel->recordSubscription(['status' => 'trial', 'amount' => 0]);

        $this->actingAs($this->superAdmin())
            ->get(route('platform.hotels.show', $hotel))
            ->assertOk()
            ->assertSee('Historique des abonnements')
            ->assertSee('Total encaissé');
    }
}
