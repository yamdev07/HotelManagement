<?php

namespace Tests\Feature;

use App\Mail\ReservationConfirmationMail;
use App\Models\Customer;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Transaction;
use App\Models\Type;
use App\Models\User;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Issue #170 : la même réservation était créée deux fois (retour arrière
 * puis re-validation dans l'assistant).
 * Issue #171 : le client ne recevait aucun email de confirmation.
 */
class ReservationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_no_duplicate_reservation_and_customer_receives_confirmation_email(): void
    {
        Mail::fake();

        $hotel = Hotel::create([
            'name' => 'Hotel Flux Resa',
            'slug' => Str::slug('Hotel Flux Resa '.Str::random(4)),
            'is_active' => true,
            'onboarding_completed_at' => now(),
            'subscription_ends_at' => now()->addMonth(),
        ]);
        $admin = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);

        app(TenantManager::class)->setHotelId($hotel->id);
        $type = Type::create(['name' => 'Standard', 'information' => 'Type standard']);
        $room = Room::create([
            'number' => '101', 'type_id' => $type->id, 'room_status_id' => 1,
            'price' => 20000, 'capacity' => 2, 'view' => '',
        ]);
        $customer = Customer::create(['name' => 'Jean Resa', 'email' => 'jean@resa.test', 'gender' => 'Male']);

        $payload = [
            'check_in' => now()->addDays(2)->toDateString(),
            'check_out' => now()->addDays(4)->toDateString(),
            'downPayment' => 0,
            'person_count' => 2,
            'payment_method' => 'cash',
        ];

        // 1er envoi : la réservation est créée et le client reçoit l'email
        $this->actingAs($admin)
            ->post(route('transaction.reservation.payDownPayment', [$customer, $room]), $payload)
            ->assertSessionHasNoErrors();

        $this->assertEquals(1, Transaction::where('customer_id', $customer->id)->count());
        Mail::assertSent(ReservationConfirmationMail::class, fn ($m) => $m->hasTo('jean@resa.test'));

        // 2e envoi identique (retour arrière puis re-validation) : PAS de doublon
        $this->actingAs($admin)
            ->post(route('transaction.reservation.payDownPayment', [$customer, $room]), $payload)
            ->assertRedirect(route('transaction.index'));

        $this->assertEquals(1, Transaction::where('customer_id', $customer->id)->count());
        // Et pas de second email
        Mail::assertSent(ReservationConfirmationMail::class, 1);
    }
}
