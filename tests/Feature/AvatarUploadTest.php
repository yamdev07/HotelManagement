<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Issue #143 : le téléversement de la photo de profil échouait (chemin mal
 * enregistré → photo jamais affichée, et écriture peu fiable en prod).
 */
class AvatarUploadTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $hotel = Hotel::create([
            'name' => 'Hotel Avatar',
            'slug' => Str::slug('Hotel Avatar '.Str::random(4)),
            'is_active' => true,
            'onboarding_completed_at' => now(),
            'subscription_ends_at' => now()->addMonth(),
        ]);

        return User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);
    }

    public function test_user_can_upload_profile_avatar_and_it_is_displayable(): void
    {
        Storage::fake('public');
        $user = $this->admin();

        $this->actingAs($user)
            ->post(route('profile.update.avatar'), [
                'avatar' => UploadedFile::fake()->image('moi.jpg'),
            ])
            ->assertRedirect();

        $user->refresh();

        // Stocké sur le disque public
        $this->assertStringStartsWith('avatars/', $user->avatar);
        Storage::disk('public')->assertExists($user->avatar);

        // Et affichable via une URL /storage (plus de double préfixe)
        $this->assertStringContainsString('storage/'.$user->avatar, $user->getAvatar());
    }
}
