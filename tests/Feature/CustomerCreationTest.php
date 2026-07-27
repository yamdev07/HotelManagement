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

    public function test_customer_photo_added_during_reservation_is_displayed(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $hotel = Hotel::create([
            'name' => 'Hotel Photo Client',
            'slug' => Str::slug('Hotel Photo Client '.Str::random(4)),
            'is_active' => true,
            'onboarding_completed_at' => now(),
            'subscription_ends_at' => now()->addMonth(),
        ]);
        $admin = \App\Models\User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);

        $this->actingAs($admin)->post(route('transaction.reservation.storeCustomer'), [
            'name' => 'Client Photo',
            'email' => 'photo@client.test',
            'phone' => '+229 00 00 00 00',
            'gender' => 'Male',
            'avatar' => \Illuminate\Http\UploadedFile::fake()->image('client.jpg'),
        ])->assertSessionHasNoErrors()->assertRedirect();

        $c = Customer::withoutGlobalScopes()->where('email', 'photo@client.test')->first();
        $this->assertNotNull($c);
        $this->assertStringStartsWith('avatars/', $c->avatar);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($c->avatar);

        // Issue #173 : la photo doit être servie via /storage (et plus ignorée)
        $this->assertStringContainsString('storage/'.$c->avatar, $c->avatar_url);
    }

    public function test_reservation_dates_validated_with_clear_messages(): void
    {
        $hotel = Hotel::create([
            'name' => 'Hotel Dates Resa',
            'slug' => Str::slug('Hotel Dates Resa '.Str::random(4)),
            'is_active' => true,
            'onboarding_completed_at' => now(),
            'subscription_ends_at' => now()->addMonth(),
        ]);
        $admin = \App\Models\User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);

        app(TenantManager::class)->setHotelId($hotel->id);
        $customer = Customer::create(['name' => 'Client Dates', 'gender' => 'Male']);

        // Issue #174 : arrivée dans le passé -> message clair, pas de blocage silencieux
        $this->actingAs($admin)->get(route('transaction.reservation.chooseRoom', $customer).'?'.http_build_query([
            'count_person' => 2,
            'check_in' => now()->subDays(3)->toDateString(),
            'check_out' => now()->addDay()->toDateString(),
        ]))->assertSessionHasErrors(['check_in']);

        // Départ avant l'arrivée -> message clair
        $this->actingAs($admin)->get(route('transaction.reservation.chooseRoom', $customer).'?'.http_build_query([
            'count_person' => 2,
            'check_in' => now()->addDays(5)->toDateString(),
            'check_out' => now()->addDays(2)->toDateString(),
        ]))->assertSessionHasErrors(['check_out']);
    }

    public function test_absurd_birthdate_is_rejected(): void
    {
        $hotel = Hotel::create([
            'name' => 'Hotel Date',
            'slug' => Str::slug('Hotel Date '.Str::random(4)),
            'is_active' => true,
            'onboarding_completed_at' => now(),
            'subscription_ends_at' => now()->addMonth(),
        ]);
        $admin = \App\Models\User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);

        // Issue #161 : 01/01/0001 doit être refusé
        $this->actingAs($admin)->post(route('transaction.reservation.storeCustomer'), [
            'name' => 'Client Antique',
            'email' => 'antique@test.test',
            'phone' => '+229 00 00 00 00',
            'gender' => 'Male',
            'birthdate' => '0001-01-01',
        ])->assertSessionHasErrors('birthdate');

        // Date future refusée aussi
        $this->actingAs($admin)->post(route('transaction.reservation.storeCustomer'), [
            'name' => 'Client Futur',
            'email' => 'futur@test.test',
            'phone' => '+229 00 00 00 00',
            'gender' => 'Male',
            'birthdate' => now()->addYear()->toDateString(),
        ])->assertSessionHasErrors('birthdate');

        $this->assertDatabaseMissing('customers', ['email' => 'antique@test.test']);
        $this->assertDatabaseMissing('customers', ['email' => 'futur@test.test']);
    }

    public function test_customer_created_without_optional_fields_and_gender_other(): void
    {
        $hotel = Hotel::create([
            'name' => 'Hotel Client',
            'slug' => Str::slug('Hotel Client '.Str::random(4)),
            'is_active' => true,
        ]);

        app(TenantManager::class)->setHotelId($hotel->id);

        // Client "de passage" : pas d'adresse, ni métier, ni date de naissance, genre "Other"
        $customer = Customer::create([
            'name' => 'Client Passage',
            'email' => 'passage@test.test',
            'phone' => '+229 00 00 00 00',
            'gender' => 'Other',
            'address' => null,
            'job' => null,
            'birthdate' => null,
        ]);

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'gender' => 'Other',
            'hotel_id' => $hotel->id,
        ]);
        $this->assertNull($customer->fresh()->address);
        $this->assertNull($customer->fresh()->birthdate);
    }
}
