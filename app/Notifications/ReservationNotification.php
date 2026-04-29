<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationNotification extends Notification
{
    use Queueable;

    public function __construct(public Transaction $transaction) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Nouvelle réservation en ligne — ' . $this->transaction->customer->name
                . ' · Chambre ' . $this->transaction->room->number
                . ' du ' . $this->transaction->check_in->format('d/m/Y')
                . ' au ' . $this->transaction->check_out->format('d/m/Y'),
            'url' => route('transaction.show', $this->transaction->id),
            'type' => 'reservation',
            'transaction_id' => $this->transaction->id,
            'customer_name' => $this->transaction->customer->name,
            'room_number' => $this->transaction->room->number,
            'check_in' => $this->transaction->check_in->format('d/m/Y'),
            'check_out' => $this->transaction->check_out->format('d/m/Y'),
        ];
    }
}
