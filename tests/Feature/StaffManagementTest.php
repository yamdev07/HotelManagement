<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Issue #180 : l'hôtelier crée ses comptes personnel (rôles limités),
 * strictement cloisonnés à son établissement.
 */
class StaffManagementTest extends TestCase
{
    use RefreshDatabase;

    private function hotelAdmin(string $name): array
    {
        $hotel = Hotel::create([
            'name' => $name,
            'slug' => Str::slug($name.' '.Str::random(4)),
            'is_active' => true,
            'onboarding_completed_at' => now(),
            'subscription_ends_at' => now()->addMonth(),
        ]);
        $admin = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);

        return [$hotel, $admin];
    }

    public function test_admin_creates_staff_scoped_to_his_hotel(): void
    {
        [$hotel, $admin] = $this->hotelAdmin('Hotel Staff A');

        $this->actingAs($admin)->post(route('staff.store'), [
            'name' => 'Awa Réception',
            'email' => 'awa@staff.test',
            'role' => 'Receptionist',
            'password' => 'MotDePasse1',
        ])->assertRedirect();

        $staff = User::where('email', 'awa@staff.test')->first();
        $this->assertNotNull($staff);
        $this->assertEquals($hotel->id, $staff->hotel_id);
        $this->assertEquals('Receptionist', $staff->role);
    }

    public function test_admin_cannot_create_an_admin_or_super(): void
    {
        [, $admin] = $this->hotelAdmin('Hotel Staff B');

        $this->actingAs($admin)->post(route('staff.store'), [
            'name' => 'Pirate',
            'email' => 'pirate@staff.test',
            'role' => 'Admin',
            'password' => 'MotDePasse1',
        ])->assertSessionHasErrors('role');

        $this->assertDatabaseMissing('users', ['email' => 'pirate@staff.test']);
    }

    public function test_weak_password_is_rejected(): void
    {
        [, $admin] = $this->hotelAdmin('Hotel Staff C');

        $this->actingAs($admin)->post(route('staff.store'), [
            'name' => 'Faible',
            'email' => 'faible@staff.test',
            'role' => 'Housekeeping',
            'password' => 'abc',
        ])->assertSessionHasErrors('password');
    }

    public function test_admin_cannot_delete_staff_of_another_hotel(): void
    {
        [, $adminA] = $this->hotelAdmin('Hotel Staff D');
        [$hotelB] = $this->hotelAdmin('Hotel Staff E');
        $staffB = User::factory()->create(['role' => 'Receptionist', 'hotel_id' => $hotelB->id]);

        $this->actingAs($adminA)->delete(route('staff.destroy', $staffB))->assertForbidden();
        $this->assertDatabaseHas('users', ['id' => $staffB->id]);
    }
}
