<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Email de réinitialisation de mot de passe personnalisé :
 * en français, aux couleurs de checkinHub (comme l'email d'identifiants).
 */
class ResetPasswordNotification extends BaseResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        $url = route('password.reset', ['token' => $this->token]).'?email='.urlencode($notifiable->getEmailForPasswordReset());
        $expire = config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60);

        return (new MailMessage)
            ->subject('Réinitialisation de votre mot de passe · '.config('app.name', 'checkinHub'))
            ->view(['emails.reset-password', 'emails.reset-password-text'], [
                'resetUrl' => $url,
                'email' => $notifiable->getEmailForPasswordReset(),
                'expire' => $expire,
            ]);
    }
}
