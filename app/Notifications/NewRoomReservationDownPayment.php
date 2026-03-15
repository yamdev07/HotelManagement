<?php
// app/Notifications/NewRoomReservationDownPayment.php

namespace App\Notifications;

use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewRoomReservationDownPayment extends Notification
{
    use Queueable;

    public $transaction;
    public $payment;

    public function __construct(Transaction $transaction, Payment $payment = null)
    {
        $this->transaction = $transaction;
        $this->payment = $payment;
    }

    public function via($notifiable)
    {
        return ['database']; // Uniquement base de données pour les notifications
    }

    public function toDatabase($notifiable)
    {
        $data = [
            'message' => 'Nouvelle réservation pour ' . $this->transaction->customer->name,
            'url' => route('transaction.show', $this->transaction->id),
            'type' => 'reservation',
            'transaction_id' => $this->transaction->id,
            'customer_name' => $this->transaction->customer->name,
            'room_number' => $this->transaction->room->number,
            'check_in' => $this->transaction->check_in->format('d/m/Y'),
            'check_out' => $this->transaction->check_out->format('d/m/Y'),
            'nights' => $this->transaction->nights,
            'total_price' => $this->transaction->total_price,
            'total_price_formatted' => number_format($this->transaction->total_price, 0, ',', ' ') . ' CFA',
            'created_at' => now()->format('d/m/Y H:i'),
        ];

        // Ajouter les infos de paiement si disponibles
        if ($this->payment) {
            $data['payment'] = [
                'id' => $this->payment->id,
                'amount' => $this->payment->amount,
                'amount_formatted' => number_format($this->payment->amount, 0, ',', ' ') . ' CFA',
                'method' => $this->payment->payment_method,
                'method_label' => $this->payment->payment_method_label ?? $this->payment->payment_method,
            ];
        }

        return $data;
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}