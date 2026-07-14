<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_sends_reset_link(): void
    {
        Notification::fake();
        $user = User::factory()->create(['email' => 'reset@test.test']);

        $this->post('/forgot-password', ['email' => 'reset@test.test'])
            ->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_user_can_reset_password_with_valid_token(): void
    {
        $user = User::factory()->create(['email' => 'reset2@test.test']);
        $token = Password::createToken($user);

        $this->post('/reset-password', [
            'token'                 => $token,
            'email'                 => 'reset2@test.test',
            'password'              => 'nouveauMotDePasse1',
            'password_confirmation' => 'nouveauMotDePasse1',
        ])->assertRedirect(route('login.index'));

        $this->assertTrue(Hash::check('nouveauMotDePasse1', $user->fresh()->password));
    }

    public function test_unknown_email_does_not_reveal_and_fails_gracefully(): void
    {
        Notification::fake();

        $this->post('/forgot-password', ['email' => 'inconnu@test.test'])
            ->assertSessionHasErrors('email');

        Notification::assertNothingSent();
    }
}
