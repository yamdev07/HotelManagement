<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\PromoCode;
use App\Models\Room;
use App\Models\RoomStatus;
use App\Models\Transaction;
use App\Models\Type;
use App\Models\User;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Application d'un code promo lors d'une réservation en ligne (vitrine).
 * Le code est re-validé côté serveur ; la remise réduit le total.
 */
class PublicBookingPromoTest extends TestCase
{
    use RefreshDatabase;

    /** @return array{0:Hotel,1:Room} */
    private function hotelWithRoom(): array
    {
        $hotel = Hotel::create([
            'name' => 'Promo Vitrine', 'slug' => Str::slug('pv '.Str::random(4)),
            'is_active' => true, 'currency' => 'XOF',
            'onboarding_completed_at' => now(), 'subscription_ends_at' => now()->addMonth(),
        ]);
        app(TenantManager::class)->setHotelId($hotel->id);
        $owner = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);
        $hotel->update(['owner_user_id' => $owner->id]);
        $type = Type::firstOrCreate(['name' => 'Std'], ['capacity' => 2, 'information' => 'x']);
        $room = Room::create([
            'type_id' => $type->id, 'room_status_id' => RoomStatus::where('code', 'AVL')->value('id'),
            'number' => '111', 'capacity' => 3, 'price' => 50000, 'view' => '',
        ]);

        return [$hotel, $room];
    }

    private function payload(string $email, ?string $promo = null): array
    {
        return array_filter([
            'check_in'  => now()->addDays(4)->format('Y-m-d'),
            'check_out' => now()->addDays(6)->format('Y-m-d'), // 2 nuits => 100 000
            'guests'    => 2,
            'name'      => 'Awa Cliente',
            'email'     => $email,
            'phone'     => '+229 01 02 03 04',
            'promo_code' => $promo,
        ], fn ($v) => $v !== null);
    }

    public function test_valid_promo_reduces_total_and_counts_usage(): void
    {
        [$hotel, $room] = $this->hotelWithRoom();
        $promo = PromoCode::create(['hotel_id' => $hotel->id, 'code' => 'NOEL10', 'type' => 'percent', 'value' => 10, 'min_nights' => 1]);
        app(TenantManager::class)->forget();

        $this->post('/h/'.$hotel->slug.'/reserver/'.$room->id, $this->payload('awa@x.test', 'noel10'))
            ->assertRedirect();

        $tx = Transaction::where('room_id', $room->id)->first();
        $this->assertEquals(90000, (float) $tx->total_price);     // 100 000 − 10 %
        $this->assertEquals(10000, (float) $tx->discount_amount);
        $this->assertSame('NOEL10', $tx->promo_code);
        $this->assertEquals(1, $promo->fresh()->used_count);
    }

    public function test_invalid_promo_is_ignored(): void
    {
        [$hotel, $room] = $this->hotelWithRoom();
        // Exige 5 nuits alors que la résa est de 2 nuits.
        $promo = PromoCode::create(['hotel_id' => $hotel->id, 'code' => 'LONG5', 'type' => 'percent', 'value' => 20, 'min_nights' => 5]);
        app(TenantManager::class)->forget();

        $this->post('/h/'.$hotel->slug.'/reserver/'.$room->id, $this->payload('bob@x.test', 'LONG5'))
            ->assertRedirect();

        $tx = Transaction::where('room_id', $room->id)->first();
        $this->assertEquals(100000, (float) $tx->total_price); // plein tarif
        $this->assertEquals(0, (float) $tx->discount_amount);
        $this->assertNull($tx->promo_code);
        $this->assertEquals(0, $promo->fresh()->used_count);
    }
}
