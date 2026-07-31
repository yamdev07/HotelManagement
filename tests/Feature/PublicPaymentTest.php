<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomStatus;
use App\Models\Transaction;
use App\Models\Type;
use App\Models\User;
use App\Services\FedaPayService;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Mockery;
use Tests\TestCase;

/**
 * Lot 3 (paiement en ligne) : au retour de FedaPay, l'acompte est enregistré
 * comme paiement en ligne (méthode "fedapay", hors session de caisse) sur la
 * réservation, et la réservation reflète le montant payé.
 */
class PublicPaymentTest extends TestCase
{
    use RefreshDatabase;

    /** @return array{0:Hotel,1:Transaction} */
    private function hotelWithReservation(string $name): array
    {
        $hotel = Hotel::create([
            'name'                    => $name,
            'slug'                    => Str::slug($name.' '.Str::random(4)),
            'is_active'               => true,
            'currency'                => 'XOF',
            'onboarding_completed_at' => now(),
            'subscription_ends_at'    => now()->addMonth(),
        ]);
        app(TenantManager::class)->setHotelId($hotel->id);
        $owner = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);
        $hotel->update(['owner_user_id' => $owner->id]);

        $type = Type::firstOrCreate(['name' => 'Std'], ['capacity' => 2, 'information' => 'x']);
        $room = Room::create([
            'type_id' => $type->id, 'room_status_id' => RoomStatus::where('code', 'AVL')->value('id'),
            'number' => '201', 'capacity' => 2, 'price' => 50000, 'view' => '',
        ]);
        $customer = Customer::create([
            'name' => 'Awa Cliente', 'email' => 'awa@voyage.test', 'phone' => '+229 01',
            'gender' => 'Other', 'user_id' => $owner->id,
        ]);
        $tx = Transaction::create([
            'user_id' => $owner->id, 'customer_id' => $customer->id, 'room_id' => $room->id,
            'check_in' => now()->addDays(4)->format('Y-m-d'), 'check_out' => now()->addDays(6)->format('Y-m-d'),
            'status' => 'reservation', 'person_count' => 2, 'total_price' => 100000, // acompte 15 % = 15000
        ]);

        return [$hotel, $tx];
    }

    public function test_approved_return_records_online_deposit_payment(): void
    {
        [$hotel, $tx] = $this->hotelWithReservation('Pay Hotel A');
        app(TenantManager::class)->forget();

        $fake = Mockery::mock(FedaPayService::class);
        $fake->shouldReceive('isConfigured')->andReturn(true);
        $fake->shouldReceive('isApproved')->with(4242)->andReturn(true);
        $this->app->instance(FedaPayService::class, $fake);

        $this->get('/h/'.$hotel->slug.'/reservation/'.$tx->id.'/retour?id=4242')
            ->assertRedirect(route('public.hotel.booking.confirmed', [$hotel->slug, $tx->id]))
            ->assertSessionHas('payment_success');

        // Paiement en ligne enregistré : méthode fedapay, montant = acompte, sans caisse
        $this->assertDatabaseHas('payments', [
            'transaction_id'     => $tx->id,
            'amount'             => 15000,
            'payment_method'     => 'fedapay',
            'status'             => 'completed',
            'cashier_session_id' => null,
        ]);

        // La réservation reflète l'acompte payé
        $this->assertEquals(15000.0, (float) $tx->fresh()->total_payment);
    }

    public function test_declined_return_records_no_payment(): void
    {
        [$hotel, $tx] = $this->hotelWithReservation('Pay Hotel B');
        app(TenantManager::class)->forget();

        $fake = Mockery::mock(FedaPayService::class);
        $fake->shouldReceive('isConfigured')->andReturn(true);
        $fake->shouldReceive('isApproved')->with(9999)->andReturn(false);
        $this->app->instance(FedaPayService::class, $fake);

        $this->get('/h/'.$hotel->slug.'/reservation/'.$tx->id.'/retour?id=9999')
            ->assertRedirect(route('public.hotel.booking.confirmed', [$hotel->slug, $tx->id]))
            ->assertSessionHas('payment_error');

        $this->assertDatabaseMissing('payments', ['transaction_id' => $tx->id]);
        $this->assertEquals(0.0, (float) ($tx->fresh()->total_payment ?? 0));
    }
}
