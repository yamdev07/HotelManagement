<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Issue #199 : des noms bourrés d'emojis (« lionel sisso🎉🎉🎉 ») passaient par
 * l'inscription /register et s'affichaient ensuite dans l'app (message de
 * déconnexion). On refuse les emojis à l'entrée et on nettoie l'affichage.
 */
class EmojiNameTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_rejects_emoji_name(): void
    {
        $this->post('/register', [
            'name'                  => 'lionel sisso🎉🎉🎉',
            'email'                 => 'lionel@test.test',
            'password'              => 'MotDePasse1',
            'password_confirmation' => 'MotDePasse1',
        ])->assertSessionHasErrors('name');

        $this->assertDatabaseMissing('users', ['email' => 'lionel@test.test']);
    }

    public function test_logout_message_strips_emojis_from_legacy_name(): void
    {
        $hotel = Hotel::create([
            'name'                    => 'Hotel Bye',
            'slug'                    => Str::slug('Hotel Bye '.Str::random(4)),
            'is_active'               => true,
            'onboarding_completed_at' => now(),
            'subscription_ends_at'    => now()->addMonth(),
        ]);
        // Donnée héritée : nom avec emojis déjà en base
        $user = User::factory()->create([
            'role' => 'Admin', 'hotel_id' => $hotel->id, 'name' => 'lionel sisso🎉🎉🎉',
        ]);

        $res = $this->actingAs($user)->post('/logout');
        $res->assertRedirect();

        // Le message affiché ne contient plus l'emoji, juste le nom propre
        $this->assertEquals('Déconnexion réussie. Au revoir lionel sisso !', session('success'));
    }
}
