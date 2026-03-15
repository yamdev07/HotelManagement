<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CheckInNotification extends Notification
{
    use Queueable;

    public $transaction;

    /**
     * Create a new notification instance.
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail']; // Stocker en base + email
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('✅ Check-in effectué - ' . $this->transaction->customer->name)
            ->greeting('Bonjour ' . $notifiable->name)
            ->line('Un client vient d\'effectuer son check-in.')
            ->line('**Détails :**')
            ->line('• Client : **' . $this->transaction->customer->name . '**')
            ->line('• Chambre : **' . $this->transaction->room->number . '**')
            ->line('• Arrivée : **' . $this->transaction->check_in->format('d/m/Y H:i') . '**')
            ->line('• Départ prévu : **' . $this->transaction->check_out->format('d/m/Y H:i') . '**')
            ->action('Voir la transaction', route('transaction.show', $this->transaction->id))
            ->line('Merci d\'utiliser notre système de gestion hôtelière !');
    }

    /**
     * Get the array representation of the notification (pour stockage en DB).
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Check-in effectué pour ' . $this->transaction->customer->name . ' en chambre ' . $this->transaction->room->number,
            'url' => route('transaction.show', $this->transaction->id),
            'type' => 'checkin',
            'transaction_id' => $this->transaction->id,
            'customer_name' => $this->transaction->customer->name,
            'room_number' => $this->transaction->room->number,
            'check_in' => $this->transaction->check_in->format('d/m/Y H:i'),
        ];
    }
}