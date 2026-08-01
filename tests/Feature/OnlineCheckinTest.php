<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Hotel;
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
 * Pré-check-in en ligne : le voyageur complète ses infos via un lien à jeton,
 * ce qui met à jour sa fiche et marque la réservation comme pré-enregistrée.
 */
class OnlineCheckinTest extends TestCase
{
    use RefreshDatabase;

    private function reservation(): Transaction
    {
        $hotel = Hotel::create([
            'name' => 'Checkin Hotel', 'slug' => Str::slug('ci '.Str::random(4)),
            'is_active' => true, 'onboarding_completed_at' => now(), 'subscription_ends_at' => now()->addMonth(),
        ]);
        app(TenantManager::class)->setHotelId($hotel->id);
        $owner = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);
        $type = Type::firstOrCreate(['name' => 'Std'], ['capacity' => 2, 'information' => 'x']);
        $room = Room::create(['type_id' => $type->id, 'room_status_id' => RoomStatus::where('code', 'AVL')->value('id'), 'number' => '210', 'capacity' => 2, 'price' => 40000, 'view' => '']);
        $customer = Customer::create(['name' => 'Awa', 'email' => 'awa@x.test', 'phone' => '+229 01', 'gender' => 'Other', 'user_id' => $owner->id]);
        $tx = Transaction::create([
            'user_id' => $owner->id, 'customer_id' => $customer->id, 'room_id' => $room->id,
            'check_in' => now()->addDays(3)->format('Y-m-d'), 'check_out' => now()->addDays(5)->format('Y-m-d'),
            'status' => 'reservation', 'person_count' => 2, 'total_price' => 80000,
        ]);
        $tx->checkinToken(); // génère le jeton
        app(TenantManager::class)->forget();

        return $tx->fresh();
    }

    public function test_guest_can_open_and_submit_precheckin(): void
    {
        $tx = $this->reservation();

        $this->get('/pre-checkin/'.$tx->checkin_token)->assertOk()->assertSee('enregistrement', false);

        $this->post('/pre-checkin/'.$tx->checkin_token, [
            'name' => 'Awa Cliente', 'phone' => '+229 90 11 22 33', 'email' => 'awa@voyage.test',
            'address' => 'Cotonou', 'id_type' => 'CNI', 'id_number' => 'AB123456', 'arrival_time' => '18h30',
        ])->assertRedirect(route('public.checkin.show', $tx->checkin_token));

        $tx->refresh();
        $this->assertNotNull($tx->pre_checkin_completed_at);
        $this->assertTrue($tx->preCheckinDone());
        $this->assertSame('CNI', $tx->pre_checkin['id_type']);
        $this->assertSame('AB123456', $tx->pre_checkin['id_number']);
        // La fiche client est mise à jour
        $this->assertSame('Awa Cliente', $tx->customer->fresh()->name);
        $this->assertSame('Cotonou', $tx->customer->fresh()->address);
    }

    public function test_unknown_token_returns_404(): void
    {
        $this->get('/pre-checkin/inexistant')->assertNotFound();
    }

    public function test_missing_id_is_rejected(): void
    {
        $tx = $this->reservation();

        $this->post('/pre-checkin/'.$tx->checkin_token, [
            'name' => 'Awa Cliente', 'phone' => '+229 90 11 22 33', 'id_type' => 'CNI', // id_number manquant
        ])->assertSessionHasErrors('id_number');
    }
}
