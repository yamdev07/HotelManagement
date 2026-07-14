<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
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

        Notification::assertSentTo($user, ResetPasswordNotification::class);
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

    public function test_reset_email_is_branded_in_french(): void
    {
        $user = User::factory()->create(['email' => 'brand@test.test']);

        $mail = (new ResetPasswordNotification('tok123'))->toMail($user);
        $this->assertStringContainsString('Réinitialisation', $mail->subject);
        $this->assertStringContainsString(config('app.name'), $mail->subject);
        $this->assertStringContainsString('tok123', $mail->viewData['resetUrl']);

        // Les vues se rendent sans variable manquante et sont en français
        $html = view('emails.reset-password', ['resetUrl' => 'http://x/reset', 'email' => 'a@b.c', 'expire' => 60])->render();
        $this->assertStringContainsString('Réinitialiser mon mot de passe', $html);
        $text = view('emails.reset-password-text', ['resetUrl' => 'http://x/reset', 'expire' => 60])->render();
        $this->assertStringContainsString('Réinitialisation du mot de passe', $text);
    }

    public function test_unknown_email_does_not_reveal_and_fails_gracefully(): void
    {
        Notification::fake();

        $this->post('/forgot-password', ['email' => 'inconnu@test.test'])
            ->assertSessionHasErrors('email');

        Notification::assertNothingSent();
    }
}
