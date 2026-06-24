<?php

namespace App\Mail;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Email envoyé au créateur d'un hôtel à l'inscription :
 * contient ses identifiants administrateur et le lien de connexion.
 */
class HotelCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Hotel $hotel,
        public User $admin,
        public string $plainPassword
    ) {}

    public function build()
    {
        return $this->subject('Vos accès administrateur — '.$this->hotel->name)
            ->view('emails.hotel-credentials')
            ->with([
                'hotelName' => $this->hotel->name,
                'email'     => $this->admin->email,
                'password'  => $this->plainPassword,
                'loginUrl'  => route('login.index'),
            ]);
    }
}
