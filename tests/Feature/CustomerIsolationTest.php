<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Hotel;
use App\Models\User;
use App\Repositories\Implementation\CustomerRepository;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tests\TestCase;

class CustomerIsolationTest extends TestCase
{
    use RefreshDatabase;

    private function hotel(): Hotel
    {
        return Hotel::create([
            'name'      => 'Hotel '.Str::random(5),
            'slug'      => Str::slug('Hotel '.Str::random(6)),
            'is_active' => true,
        ]);
    }

    private function makeCustomer(int $hotelId, string $email): Customer
    {
        app(TenantManager::class)->setHotelId($hotelId);

        $request = new Request([
            'name'      => 'Client Test',
            'email'     => $email,
            'address'   => 'Rue 1',
            'job'       => 'Ingénieur',
            'birthdate' => '1990-01-01',
            'gender'    => 'Male',
        ]);

        return CustomerRepository::store($request);
    }

    public function test_same_customer_email_allowed_in_two_hotels(): void
    {
        $a = $this->hotel();
        $b = $this->hotel();

        $c1 = $this->makeCustomer($a->id, 'client@partage.test');
        $c2 = $this->makeCustomer($b->id, 'client@partage.test');

        // Chaque hôtel a SA fiche client, chacune scopée à son hôtel
        $this->assertEquals($a->id, $c1->hotel_id);
        $this->assertEquals($b->id, $c2->hotel_id);
        $this->assertNotEquals($c1->id, $c2->id);

        // Un seul compte de connexion global (créé pour le 1er), pas de doublon
        $this->assertEquals(1, User::where('email', 'client@partage.test')->count());

        // Le 1er a un compte lié, le 2e réutilise la fiche sans compte global en double
        $this->assertNotNull($c1->user_id);
        $this->assertNull($c2->user_id);
        $this->assertEquals('client@partage.test', $c2->email);
    }
}
