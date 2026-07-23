<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Email envoyé à un membre du personnel à sa création :
 * contient ses identifiants de connexion.
 */
class StaffCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $staff,
        public string $plainPassword
    ) {}

    public function build()
    {
        $hotel = $this->staff->hotel;

        return $this->subject('Vos accès · ' . ($hotel->name ?? config('app.name')))
            ->view('emails.staff-credentials')
            ->text('emails.staff-credentials-text')
            ->with([
                'staffName' => $this->staff->name,
                'email'     => $this->staff->email,
                'password'  => $this->plainPassword,
                'role'      => $this->staff->role,
                'hotelName' => $hotel->name ?? 'Hôtel',
                'loginUrl'  => route('login.index'),
            ]);
    }
}
