<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Hotel;
use App\Models\User;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Issue #189 : dans l'assistant de réservation, revenir en arrière pour corriger
 * une donnée du client (nom/email) et finir créait un DEUXIÈME client. On doit
 * éditer le même client, pas en créer un doublon.
 */
class ReservationCustomerNoDuplicateTest extends TestCase
{
    use RefreshDatabase;

    public function test_editing_customer_on_step_back_updates_instead_of_duplicating(): void
    {
        $hotel = Hotel::create([
            'name' => 'Hotel Dup',
            'slug' => Str::slug('Hotel Dup '.Str::random(4)),
            'is_active' => true,
            'onboarding_completed_at' => now(),
            'subscription_ends_at' => now()->addMonth(),
        ]);
        app(TenantManager::class)->setHotelId($hotel->id);
        $agent = User::factory()->create(['role' => 'Receptionist', 'hotel_id' => $hotel->id]);

        // Étape 1 : création initiale du client
        $this->actingAs($agent)->post(route('transaction.reservation.storeCustomer'), [
            'name' => 'Jean',
            'email' => 'jean@client.test',
            'phone' => '+229 01 02 03 04',
            'gender' => 'Male',
        ])->assertRedirect();

        $customer = Customer::where('email', 'jean@client.test')->first();
        $this->assertNotNull($customer);
        $this->assertEquals(1, Customer::count());

        // Retour en arrière : on corrige le nom (et l'email) en transmettant customer_id
        $this->actingAs($agent)->post(route('transaction.reservation.storeCustomer'), [
            'customer_id' => $customer->id,
            'name' => 'Jean Dupont',
            'email' => 'jean.dupont@client.test',
            'phone' => '+229 01 02 03 04',
            'gender' => 'Male',
        ])->assertRedirect();

        // Toujours UN SEUL client, mis à jour (pas de doublon).
        $this->assertEquals(1, Customer::count());
        $this->assertEquals('Jean Dupont', $customer->fresh()->name);
        $this->assertEquals('jean.dupont@client.test', $customer->fresh()->email);
    }
}
