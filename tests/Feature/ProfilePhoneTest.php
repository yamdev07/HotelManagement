<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Issue #145 : le numéro de téléphone saisi dans le profil n'était pas enregistré
 * (colonne 'phone' absente de users + non fillable).
 */
class ProfilePhoneTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_rejects_emoji_name_and_invalid_phone(): void
    {
        $hotel = Hotel::create([
            'name'                    => 'Hotel Profil',
            'slug'                    => Str::slug('Hotel Profil '.Str::random(4)),
            'is_active'               => true,
            'onboarding_completed_at' => now(),
            'subscription_ends_at'    => now()->addMonth(),
        ]);
        $user = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id, 'email' => 'prof@valid.test']);

        // Issue #197 : nom charabia/emoji refusé
        $this->actingAs($user)->post(route('profile.update.info'), [
            'name'  => '👗)(*&^%',
            'email' => 'prof@valid.test',
        ])->assertSessionHasErrors('name');

        // Téléphone invalide (lettres) refusé
        $this->actingAs($user)->post(route('profile.update.info'), [
            'name'  => 'Jean Valide',
            'email' => 'prof@valid.test',
            'phone' => 'abcXYZ!!',
        ])->assertSessionHasErrors('phone');

        // Valeurs correctes : OK
        $this->actingAs($user)->post(route('profile.update.info'), [
            'name'  => 'Jean Valide',
            'email' => 'prof@valid.test',
            'phone' => '+229 01 02 03 04',
        ])->assertSessionHasNoErrors();
    }

    public function test_user_can_save_phone_number_in_profile(): void
    {
        $hotel = Hotel::create([
            'name'                    => 'Hotel Phone',
            'slug'                    => Str::slug('Hotel Phone '.Str::random(4)),
            'is_active'               => true,
            'onboarding_completed_at' => now(),
            'subscription_ends_at'    => now()->addMonth(),
        ]);
        $user = User::factory()->create([
            'role'     => 'Admin',
            'hotel_id' => $hotel->id,
            'email'    => 'admin@phone.test',
        ]);

        $this->actingAs($user)->post(route('profile.update.info'), [
            'name'  => 'Patron',
            'email' => 'admin@phone.test',
            'phone' => '+229 91 23 45 67',
        ])->assertRedirect();

        $this->assertEquals('+229 91 23 45 67', $user->fresh()->phone);
    }

    public function test_signup_stores_admin_phone_on_the_user(): void
    {
        \Illuminate\Support\Facades\Mail::fake();

        $this->post('/inscription', [
            'company_name'  => 'Hotel Tel',
            'plan'          => 'starter',
            'contact_phone' => '+229 90 00 00 00',
            'admin_name'    => 'Boss',
            'admin_email'   => 'boss@tel.test',
        ]);

        $user = User::where('email', 'boss@tel.test')->first();
        $this->assertNotNull($user);
        $this->assertEquals('+229 90 00 00 00', $user->phone);
    }
}
