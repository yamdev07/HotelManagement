<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Hotel;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Issue #146 : la création d'un client pour une réservation plantait quand
 * l'adresse / le métier / la date de naissance étaient vides (colonnes NOT NULL),
 * ou quand le genre "Other" était choisi (absent de l'enum).
 */
class CustomerCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_absurd_birthdate_is_rejected(): void
    {
        $hotel = Hotel::create([
            'name'                    => 'Hotel Date',
            'slug'                    => Str::slug('Hotel Date '.Str::random(4)),
            'is_active'               => true,
            'onboarding_completed_at' => now(),
            'subscription_ends_at'    => now()->addMonth(),
        ]);
        $admin = \App\Models\User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);

        // Issue #161 : 01/01/0001 doit être refusé
        $this->actingAs($admin)->post(route('transaction.reservation.storeCustomer'), [
            'name'      => 'Client Antique',
            'email'     => 'antique@test.test',
            'phone'     => '+229 00 00 00 00',
            'gender'    => 'Male',
            'birthdate' => '0001-01-01',
        ])->assertSessionHasErrors('birthdate');

        // Date future refusée aussi
        $this->actingAs($admin)->post(route('transaction.reservation.storeCustomer'), [
            'name'      => 'Client Futur',
            'email'     => 'futur@test.test',
            'phone'     => '+229 00 00 00 00',
            'gender'    => 'Male',
            'birthdate' => now()->addYear()->toDateString(),
        ])->assertSessionHasErrors('birthdate');

        $this->assertDatabaseMissing('customers', ['email' => 'antique@test.test']);
        $this->assertDatabaseMissing('customers', ['email' => 'futur@test.test']);
    }

    public function test_customer_created_without_optional_fields_and_gender_other(): void
    {
        $hotel = Hotel::create([
            'name'      => 'Hotel Client',
            'slug'      => Str::slug('Hotel Client '.Str::random(4)),
            'is_active' => true,
        ]);

        app(TenantManager::class)->setHotelId($hotel->id);

        // Client "de passage" : pas d'adresse, ni métier, ni date de naissance, genre "Other"
        $customer = Customer::create([
            'name'      => 'Client Passage',
            'email'     => 'passage@test.test',
            'phone'     => '+229 00 00 00 00',
            'gender'    => 'Other',
            'address'   => null,
            'job'       => null,
            'birthdate' => null,
        ]);

        $this->assertDatabaseHas('customers', [
            'id'       => $customer->id,
            'gender'   => 'Other',
            'hotel_id' => $hotel->id,
        ]);
        $this->assertNull($customer->fresh()->address);
        $this->assertNull($customer->fresh()->birthdate);
    }
}
