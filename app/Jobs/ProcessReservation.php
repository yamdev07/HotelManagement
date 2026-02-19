<?php

namespace App\Jobs;

use App\Models\Reservation;
use App\Mail\ReservationConfirmed;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProcessReservation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function handle()
    {
        // Exemple : envoyer email confirmation
        Mail::to($this->reservation->customer->email)
            ->send(new ReservationConfirmed($this->reservation));

        // Ici plus tard :
        // - générer PDF
        // - enregistrer facture
        // - notifier WhatsApp
    }
}
