<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\User;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Issue #191 : un propriétaire ne pouvait pas supprimer son propre établissement.
 * Ajout d'une clôture self-service réservée AU PROPRIÉTAIRE, avec confirmation.
 */
class HotelSelfDeletionTest extends TestCase
{
    use RefreshDatabase;

    private function hotelWithOwner(): array
    {
        $hotel = Hotel::create([
            'name' => 'Hotel Ferme',
            'slug' => Str::slug('Hotel Ferme '.Str::random(4)),
            'is_active' => true,
            'onboarding_completed_at' => now(),
            'subscription_ends_at' => now()->addMonth(),
        ]);
        app(TenantManager::class)->setHotelId($hotel->id);
        $owner = User::factory()->create([
            'role' => 'Admin',
            'hotel_id' => $hotel->id,
            'password' => Hash::make('secret123'),
        ]);
        $hotel->update(['owner_user_id' => $owner->id]);

        return [$hotel, $owner];
    }

    public function test_owner_can_delete_establishment_with_password_and_confirmation(): void
    {
        [$hotel, $owner] = $this->hotelWithOwner();
        $staff = User::factory()->create(['role' => 'Receptionist', 'hotel_id' => $hotel->id]);

        app(TenantManager::class)->forget();
        $this->actingAs($owner)->delete(route('hotel.account.destroy'), [
            'password' => 'secret123',
            'confirmation' => 'SUPPRIMER',
        ])->assertRedirect(route('landing'));

        $this->assertDatabaseMissing('hotels', ['id' => $hotel->id]);
        $this->assertDatabaseMissing('users', ['id' => $owner->id]);
        $this->assertDatabaseMissing('users', ['id' => $staff->id]);
        $this->assertGuest();
    }

    public function test_wrong_password_does_not_delete(): void
    {
        [$hotel, $owner] = $this->hotelWithOwner();

        app(TenantManager::class)->forget();
        $this->actingAs($owner)->delete(route('hotel.account.destroy'), [
            'password' => 'mauvais',
            'confirmation' => 'SUPPRIMER',
        ])->assertSessionHasErrors('password');

        $this->assertDatabaseHas('hotels', ['id' => $hotel->id]);
    }

    public function test_non_owner_cannot_delete_establishment(): void
    {
        [$hotel] = $this->hotelWithOwner();
        $manager = User::factory()->create([
            'role' => 'Manager',
            'hotel_id' => $hotel->id,
            'password' => Hash::make('secret123'),
        ]);

        app(TenantManager::class)->forget();
        $this->actingAs($manager)->delete(route('hotel.account.destroy'), [
            'password' => 'secret123',
            'confirmation' => 'SUPPRIMER',
        ])->assertForbidden();

        $this->assertDatabaseHas('hotels', ['id' => $hotel->id]);
    }
}
