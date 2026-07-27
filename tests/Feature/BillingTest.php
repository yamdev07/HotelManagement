<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

class BillingTest extends TestCase
{
    use RefreshDatabase;

    private function hotelWithAdmin(array $attrs = []): array
    {
        $hotel = Hotel::create(array_merge([
            'name' => 'Hotel Billing',
            'slug' => Str::slug('Hotel Billing '.Str::random(4)),
            'country' => 'BJ',
            'currency' => 'XOF',
            'is_active' => true,
            'plan' => 'pro',
            'room_limit' => 20,
            'onboarding_completed_at' => now(),
            'subscription_ends_at' => now()->addDays(3),
        ], $attrs));

        $admin = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);

        return [$hotel, $admin];
    }

    private function fakeFedaPay(string $status = 'approved'): void
    {
        config(['services.fedapay.secret' => 'sk_test_x', 'services.fedapay.env' => 'sandbox']);

        Http::fake([
            '*/transactions/999/token' => Http::response(['token' => 'tok', 'url' => 'https://sandbox-checkout.fedapay.com/tok'], 200),
            '*/transactions/999' => Http::response(['v1/transaction' => ['id' => 999, 'status' => $status]], 200),
            '*/transactions' => Http::response(['v1/transaction' => ['id' => 999, 'status' => 'pending']], 200),
        ]);
    }

    public function test_admin_can_view_billing_page(): void
    {
        [$hotel, $admin] = $this->hotelWithAdmin();

        $this->actingAs($admin)->get(route('billing.show'))
            ->assertOk()
            ->assertSee('Mon abonnement');
    }

    public function test_checkout_creates_transaction_and_redirects_to_fedapay(): void
    {
        $this->fakeFedaPay();
        [$hotel, $admin] = $this->hotelWithAdmin();

        $this->actingAs($admin)
            ->post(route('billing.checkout'), ['plan' => 'pro', 'months' => 1])
            ->assertRedirect('https://sandbox-checkout.fedapay.com/tok');

        $this->assertEquals(999, session('billing.pending.transaction_id'));
        $this->assertEquals('pro', session('billing.pending.plan'));
    }

    public function test_approved_callback_extends_subscription_and_records_renewal(): void
    {
        $this->fakeFedaPay('approved');
        [$hotel, $admin] = $this->hotelWithAdmin();
        $oldEnd = $hotel->subscription_ends_at->copy();

        $this->actingAs($admin)->post(route('billing.checkout'), ['plan' => 'business', 'months' => 3]);
        $this->actingAs($admin)->get(route('billing.callback'))
            ->assertRedirect(route('billing.show'));

        $hotel->refresh();
        $this->assertEquals($oldEnd->copy()->addMonths(3)->toDateString(), $hotel->subscription_ends_at->toDateString());
        $this->assertEquals('business', $hotel->plan);

        $renewal = Subscription::where('hotel_id', $hotel->id)->where('is_renewal', true)->first();
        $this->assertNotNull($renewal);
        $this->assertEquals(Hotel::priceFor('business', 'BJ') * 3, (float) $renewal->amount);
    }

    public function test_declined_callback_does_not_extend_subscription(): void
    {
        $this->fakeFedaPay('declined');
        [$hotel, $admin] = $this->hotelWithAdmin();
        $oldEnd = $hotel->subscription_ends_at->copy();

        $this->actingAs($admin)->post(route('billing.checkout'), ['plan' => 'pro', 'months' => 1]);
        $this->actingAs($admin)->get(route('billing.callback'))
            ->assertRedirect(route('billing.show'))
            ->assertSessionHas('error');

        $this->assertEquals($oldEnd->toDateString(), $hotel->fresh()->subscription_ends_at->toDateString());
        $this->assertFalse(Subscription::where('hotel_id', $hotel->id)->where('is_renewal', true)->exists());
    }

    public function test_admin_suspended_hotel_cannot_checkout(): void
    {
        $this->fakeFedaPay();
        [$hotel, $admin] = $this->hotelWithAdmin(['is_active' => false, 'suspension_reason' => 'Impayé']);

        $this->actingAs($admin)->post(route('billing.checkout'), ['plan' => 'pro', 'months' => 1])
            ->assertSessionHas('error');

        $this->assertFalse(Subscription::where('hotel_id', $hotel->id)->where('is_renewal', true)->exists());
    }
}
