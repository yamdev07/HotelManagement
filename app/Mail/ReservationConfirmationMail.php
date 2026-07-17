<?php

namespace App\Mail;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Confirmation de réservation envoyée au CLIENT (issue #171),
 * aux couleurs de l'hôtel concerné.
 */
class ReservationConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Transaction $transaction) {}

    public function build()
    {
        $t = $this->transaction->loadMissing(['customer', 'room.type', 'hotel']);
        $hotel = $t->hotel;

        $checkIn  = Carbon::parse($t->check_in);
        $checkOut = Carbon::parse($t->check_out);
        $nights   = max(1, $checkIn->diffInDays($checkOut));

        return $this->subject('Votre réservation est confirmée · '.($hotel->name ?? config('app.name', 'checkinHub')))
            ->view('emails.reservation-confirmation')
            ->text('emails.reservation-confirmation-text')
            ->with([
                'hotelName'    => $hotel->name ?? config('app.name', 'checkinHub'),
                'color'        => $hotel?->primaryColor() ?? '#4f46e5',
                'hotelPhone'   => $hotel->contact_phone ?? null,
                'customerName' => $t->customer->name ?? 'Client',
                'roomNumber'   => $t->room->number ?? '·',
                'roomType'     => $t->room?->type?->name ?? '',
                'checkIn'      => $checkIn->format('d/m/Y'),
                'checkOut'     => $checkOut->format('d/m/Y'),
                'nights'       => $nights,
                'total'        => (float) $t->total_price,
                'paid'         => (float) $t->total_payment,
            ]);
    }
}
