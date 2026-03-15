<?php

namespace App\Notifications;

use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentNotification extends Notification
{
    use Queueable;

    public $payment;
    public $transaction;

    /**
     * Create a new notification instance.
     */
    public function __construct(Payment $payment, Transaction $transaction)
    {
        $this->payment = $payment;
        $this->transaction = $transaction;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail']; // Stockage en base + email
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $remaining = $this->transaction->getRemainingPayment();
        $isFullyPaid = $remaining <= 0;
        
        return (new MailMessage)
            ->subject('💰 Paiement enregistré - ' . number_format($this->payment->amount, 0, ',', ' ') . ' FCFA')
            ->greeting('Bonjour ' . $notifiable->name)
            ->line('Un nouveau paiement a été enregistré dans le système.')
            ->line('**Détails du paiement :**')
            ->line('• Client : **' . $this->transaction->customer->name . '**')
            ->line('• Montant : **' . number_format($this->payment->amount, 0, ',', ' ') . ' FCFA**')
            ->line('• Méthode : **' . ucfirst(str_replace('_', ' ', $this->payment->payment_method)) . '**')
            ->line('• Transaction # : **' . $this->transaction->id . '**')
            ->line('• Référence : **' . $this->payment->reference . '**')
            ->line('• Date : **' . $this->payment->created_at->format('d/m/Y H:i') . '**')
            ->line('')
            ->line('**Récapitulatif de la transaction :**')
            ->line('• Total séjour : **' . number_format($this->transaction->getTotalPrice(), 0, ',', ' ') . ' FCFA**')
            ->line('• Total payé : **' . number_format($this->transaction->getTotalPayment(), 0, ',', ' ') . ' FCFA**')
            ->line('• Solde restant : **' . number_format($remaining, 0, ',', ' ') . ' FCFA**')
            ->line('• Statut : **' . ($isFullyPaid ? '✅ Entièrement payé' : '⚠️ Paiement partiel') . '**')
            ->action('Voir la facture', route('payment.invoice', $this->payment->id))
            ->line('Merci d\'utiliser notre système de gestion hôtelière !');
    }

    /**
     * Get the array representation of the notification (pour stockage en DB).
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $remaining = $this->transaction->getRemainingPayment();
        $isFullyPaid = $remaining <= 0;
        
        return [
            'message' => '💰 Paiement de ' . number_format($this->payment->amount, 0, ',', ' ') . ' FCFA - ' . $this->transaction->customer->name,
            'url' => route('payment.invoice', $this->payment->id),
            'type' => 'payment',
            'payment_id' => $this->payment->id,
            'transaction_id' => $this->transaction->id,
            'customer_name' => $this->transaction->customer->name,
            'room_number' => $this->transaction->room->number,
            'amount' => $this->payment->amount,
            'formatted_amount' => number_format($this->payment->amount, 0, ',', ' ') . ' FCFA',
            'method' => $this->payment->payment_method,
            'method_label' => ucfirst(str_replace('_', ' ', $this->payment->payment_method)),
            'reference' => $this->payment->reference,
            'remaining' => $remaining,
            'is_fully_paid' => $isFullyPaid,
        ];
    }
}