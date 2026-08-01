<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Hotel;
use App\Models\Payment;
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
 * Dashboard revenus : réservé Admin/Direction, scopé à l'hôtel courant
 * (les encaissements d'un autre hôtel ne fuient jamais).
 */
class RevenueDashboardTest extends TestCase
{
    use RefreshDatabase;

    private function hotelWithPayment(string $name, int $amount): array
    {
        $hotel = Hotel::create([
            'name' => $name, 'slug' => Str::slug($name.' '.Str::random(4)),
            'country' => 'BJ', 'is_active' => true,
            'onboarding_completed_at' => now(), 'subscription_ends_at' => now()->addMonth(),
        ]);
        app(TenantManager::class)->setHotelId($hotel->id);
        $admin = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);
        $type = Type::firstOrCreate(['name' => 'Std'], ['capacity' => 2, 'information' => 'x']);
        $room = Room::create([
            'type_id' => $type->id, 'room_status_id' => RoomStatus::where('code', 'AVL')->value('id'),
            'number' => '101', 'capacity' => 2, 'price' => 30000, 'view' => '',
        ]);
        $customer = Customer::create(['name' => 'Cli', 'email' => Str::random(6).'@x.test', 'phone' => '+22990', 'gender' => 'Other', 'user_id' => $admin->id]);
        $tx = Transaction::create([
            'user_id' => $admin->id, 'customer_id' => $customer->id, 'room_id' => $room->id,
            'check_in' => now()->format('Y-m-d'), 'check_out' => now()->addDays(2)->format('Y-m-d'),
            'status' => 'active', 'person_count' => 1, 'total_price' => 60000,
        ]);
        Payment::create([
            'transaction_id' => $tx->id, 'user_id' => $admin->id, 'created_by' => $admin->id,
            'amount' => $amount, 'status' => 'completed', 'payment_method' => 'mobile_money',
            'payment_date' => now(), 'currency' => 'XOF',
        ]);
        app(TenantManager::class)->forget();

        return [$hotel, $admin];
    }

    public function test_admin_sees_own_revenue_not_other_hotel(): void
    {
        [$hotelA, $adminA] = $this->hotelWithPayment('Rev A', 50000);
        $this->hotelWithPayment('Rev B', 777777); // autre hôtel

        $res = $this->actingAs($adminA)->get('/revenus');
        $res->assertOk();
        $res->assertSee('50 000');          // revenu de son hôtel
        $res->assertDontSee('777 777');     // pas celui de l'autre hôtel
    }

    public function test_receptionist_cannot_access_revenue(): void
    {
        [$hotel] = $this->hotelWithPayment('Rev C', 10000);
        app(TenantManager::class)->setHotelId($hotel->id);
        $reception = User::factory()->create(['role' => 'Receptionist', 'hotel_id' => $hotel->id]);
        app(TenantManager::class)->forget();

        $res = $this->actingAs($reception)->get('/revenus');
        $this->assertNotEquals(200, $res->getStatusCode());
    }
}
