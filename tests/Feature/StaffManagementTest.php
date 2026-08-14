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

    /* ───────────────── Rôle Direction (Manager) ───────────────── */

    public function test_admin_can_create_a_direction_manager(): void
    {
        [$hotel, $admin] = $this->hotelAdmin('Hotel Dir A');

        $this->actingAs($admin)->post(route('staff.store'), [
            'name'     => 'Directrice',
            'email'    => 'dir@staff.test',
            'role'     => 'Manager',
            'password' => 'MotDePasse1',
        ])->assertRedirect();

        $manager = User::where('email', 'dir@staff.test')->first();
        $this->assertNotNull($manager);
        $this->assertEquals('Manager', $manager->role);
        $this->assertEquals($hotel->id, $manager->hotel_id);
    }

    public function test_manager_cannot_create_another_manager(): void
    {
        [$hotel, $admin] = $this->hotelAdmin('Hotel Dir B');
        $manager = User::factory()->create(['role' => 'Manager', 'hotel_id' => $hotel->id]);

        // Un Manager ne peut créer QUE de l'opérationnel, pas un autre Manager.
        $this->actingAs($manager)->post(route('staff.store'), [
            'name'     => 'Autre Dir',
            'email'    => 'autredir@staff.test',
            'role'     => 'Manager',
            'password' => 'MotDePasse1',
        ])->assertSessionHasErrors('role');
        $this->assertDatabaseMissing('users', ['email' => 'autredir@staff.test']);

        // …mais il crée bien un réceptionniste.
        $this->actingAs($manager)->post(route('staff.store'), [
            'name'     => 'Recep OK',
            'email'    => 'recepok@staff.test',
            'role'     => 'Receptionist',
            'password' => 'MotDePasse1',
        ])->assertRedirect();
        $this->assertDatabaseHas('users', ['email' => 'recepok@staff.test', 'role' => 'Receptionist']);
    }

    public function test_manager_can_access_personnel_but_not_billing(): void
    {
        [$hotel] = $this->hotelAdmin('Hotel Dir C');
        $manager = User::factory()->create(['role' => 'Manager', 'hotel_id' => $hotel->id]);

        // Accès à la gestion du personnel (comme un Admin)
        $this->actingAs($manager)->get(route('staff.index'))->assertOk();

        // …mais PAS à la facturation/abonnement
        $this->actingAs($manager)->get(route('billing.show'))->assertForbidden();
    }

    public function test_manager_cannot_delete_or_touch_another_manager(): void
    {
        [$hotel] = $this->hotelAdmin('Hotel Dir D');
        $manager      = User::factory()->create(['role' => 'Manager', 'hotel_id' => $hotel->id]);
        $otherManager = User::factory()->create(['role' => 'Manager', 'hotel_id' => $hotel->id]);

        $this->actingAs($manager)->delete(route('staff.destroy', $otherManager))->assertForbidden();
        $this->assertDatabaseHas('users', ['id' => $otherManager->id]);
    }
}
