<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CheckOutNotification extends Notification
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
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $totalPaid = $this->transaction->getTotalPayment();
        $totalPrice = $this->transaction->getTotalPrice();
        $isFullyPaid = $totalPaid >= $totalPrice;
        
        return (new MailMessage)
            ->subject('🛎️ Check-out effectué - ' . $this->transaction->customer->name)
            ->greeting('Bonjour ' . $notifiable->name)
            ->line('Un client vient d\'effectuer son check-out.')
            ->line('**Détails :**')
            ->line('• Client : **' . $this->transaction->customer->name . '**')
            ->line('• Chambre : **' . $this->transaction->room->number . '**')
            ->line('• Départ effectué : **' . now()->format('d/m/Y H:i') . '**')
            ->line('• Arrivée : **' . $this->transaction->check_in->format('d/m/Y') . '**')
            ->line('• Durée du séjour : **' . $this->transaction->nights . ' nuit(s)**')
            ->line('• Total séjour : **' . number_format($totalPrice, 0, ',', ' ') . ' FCFA**')
            ->line('• Total payé : **' . number_format($totalPaid, 0, ',', ' ') . ' FCFA**')
            ->line('• Statut : **' . ($isFullyPaid ? '✅ Entièrement payé' : '⚠️ Solde restant') . '**')
            
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
        $totalPaid = $this->transaction->getTotalPayment();
        $totalPrice = $this->transaction->getTotalPrice();
        $isFullyPaid = $totalPaid >= $totalPrice;
        
        return [
            'message' => 'Check-out effectué pour ' . $this->transaction->customer->name . ' - Chambre ' . $this->transaction->room->number,
            'url' => route('transaction.show', $this->transaction->id),
            'type' => 'checkout',
            'transaction_id' => $this->transaction->id,
            'customer_name' => $this->transaction->customer->name,
            'room_number' => $this->transaction->room->number,
            'check_out' => now()->format('d/m/Y H:i'),
            'nights' => $this->transaction->nights,
            'total_price' => $totalPrice,
            'total_paid' => $totalPaid,
            'is_fully_paid' => $isFullyPaid,
        ];
    }
}