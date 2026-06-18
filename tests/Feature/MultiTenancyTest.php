<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Hotel;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiTenancyTest extends TestCase
{
    use RefreshDatabase;

    private function tenant(): TenantManager
    {
        return app(TenantManager::class);
    }

    private function makeHotel(string $name): Hotel
    {
        return Hotel::create([
            'name'      => $name,
            'slug'      => \Illuminate\Support\Str::slug($name),
            'is_active' => true,
        ]);
    }

    public function test_hotel_id_is_filled_automatically_on_create(): void
    {
        $hotel = $this->makeHotel('Hotel A');
        $this->tenant()->setHotelId($hotel->id);

        $customer = Customer::factory()->create();

        $this->assertEquals($hotel->id, $customer->hotel_id);
    }

    public function test_data_is_isolated_between_hotels(): void
    {
        $a = $this->makeHotel('Hotel A');
        $b = $this->makeHotel('Hotel B');

        $this->tenant()->setHotelId($a->id);
        $customerA = Customer::factory()->create();

        $this->tenant()->setHotelId($b->id);
        $customerB = Customer::factory()->create();

        // Depuis l'hôtel B : on ne voit que le client de B
        $this->assertEquals(1, Customer::count());
        $this->assertTrue(Customer::first()->is($customerB));

        // Depuis l'hôtel A : on ne voit que le client de A
        $this->tenant()->setHotelId($a->id);
        $this->assertEquals(1, Customer::count());
        $this->assertTrue(Customer::first()->is($customerA));
    }

    public function test_without_hotel_scope_returns_all_hotels_data(): void
    {
        $a = $this->makeHotel('Hotel A');
        $b = $this->makeHotel('Hotel B');

        $this->tenant()->setHotelId($a->id);
        Customer::factory()->create();
        $this->tenant()->setHotelId($b->id);
        Customer::factory()->create();

        // Le scope filtre à 1, mais withoutHotelScope voit les 2
        $this->assertEquals(1, Customer::count());
        $this->assertEquals(2, Customer::withoutHotelScope()->count());
    }

    public function test_hotel_access_gating(): void
    {
        $active = $this->makeHotel('Actif');
        $this->assertTrue($active->hasActiveAccess());

        $suspended = $this->makeHotel('Suspendu');
        $suspended->update(['is_active' => false]);
        $this->assertFalse($suspended->fresh()->hasActiveAccess());

        $expired = $this->makeHotel('Expiré');
        $expired->update(['subscription_ends_at' => now()->subDay()]);
        $this->assertFalse($expired->fresh()->hasActiveAccess());

        $future = $this->makeHotel('Futur');
        $future->update(['subscription_ends_at' => now()->addMonth()]);
        $this->assertTrue($future->fresh()->hasActiveAccess());
    }
}
