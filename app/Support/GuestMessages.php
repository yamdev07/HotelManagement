<?php

namespace App\Support;

use App\Models\Hotel;
use App\Models\Transaction;
use App\Services\WhatsAppService;
use Carbon\Carbon;

/**
 * Messages WhatsApp prêts à envoyer au client + génération de liens « wa.me ».
 *
 * Sert au parcours « en un tap » : l'app prépare le texte, l'hôtelier l'envoie
 * depuis SON WhatsApp via un lien wa.me (aucun compte API requis).
 * Les mêmes textes peuvent servir à l'envoi automatique (WhatsAppService).
 */
class GuestMessages
{
    /** Référence lisible d'une réservation (RES-00042). */
    public static function ref(Transaction $tx): string
    {
        return 'RES-'.str_pad((string) $tx->id, 5, '0', STR_PAD_LEFT);
    }

    /** Message de confirmation de réservation. */
    public static function confirmation(Hotel $hotel, Transaction $tx): string
    {
        $ci = Carbon::parse($tx->check_in)->format('d/m/Y');
        $co = Carbon::parse($tx->check_out)->format('d/m/Y');
        $room = $tx->room->number ?? '';
        $currency = $hotel->currency ?: 'XOF';
        $total = number_format((float) $tx->total_price, 0, ',', ' ');

        return "Bonjour {$tx->customer->name},\n\n".
            'Votre réservation *'.self::ref($tx)."* chez *{$hotel->name}* est confirmée ✅\n\n".
            "🛏️ Chambre {$room}\n📅 Du {$ci} au {$co}\n💰 Total : {$total} {$currency}\n\n".
            'Merci et à bientôt !'.self::signature($hotel);
    }

    /** Signature de l'établissement (nom + contact) apposée à chaque message. */
    private static function signature(Hotel $hotel): string
    {
        $sig = "\n\n- *{$hotel->name}*";
        if (! empty($hotel->contact_phone)) {
            $sig .= "\n📞 {$hotel->contact_phone}";
        }

        return $sig;
    }

    /** Message d'accusé de paiement (acompte / règlement reçu). */
    public static function paymentReceived(Hotel $hotel, Transaction $tx): string
    {
        $currency = $hotel->currency ?: 'XOF';
        $paid = number_format((float) ($tx->total_payment ?? 0), 0, ',', ' ');
        $balance = number_format(max(0, (float) $tx->total_price - (float) ($tx->total_payment ?? 0)), 0, ',', ' ');

        return "Merci {$tx->customer->name} ! 🎉\n\n".
            'Nous confirmons la réception de votre paiement de *'.$paid." {$currency}* ".
            'pour la réservation *'.self::ref($tx)."*.\n".
            "Solde restant : {$balance} {$currency} (à régler à l'arrivée).\n\n".
            'À très bientôt !'.self::signature($hotel);
    }

    /** Invitation à remplir le pré-check-in en ligne (avec le lien). */
    public static function preCheckinInvite(Hotel $hotel, Transaction $tx): string
    {
        return "Bonjour {$tx->customer->name},\n\n".
            "Pour un check-in *express* à votre arrivée chez *{$hotel->name}*, ".
            "remplissez votre pré-enregistrement en 1 minute ici :\n".
            $tx->checkinUrl()."\n\n".
            'Merci et à bientôt !'.self::signature($hotel);
    }

    /** Message de rappel la veille de l'arrivée. */
    public static function checkInReminder(Hotel $hotel, Transaction $tx): string
    {
        $ci = Carbon::parse($tx->check_in)->format('d/m/Y');
        $room = $tx->room->number ?? '';

        return "Bonjour {$tx->customer->name} 👋\n\n".
            "Petit rappel : votre arrivée chez *{$hotel->name}* est prévue le *{$ci}* ".
            '(réservation '.self::ref($tx).", chambre {$room}).\n\n".
            'Nous avons hâte de vous accueillir ! 😊'.self::signature($hotel);
    }

    /**
     * Lien wa.me ouvrant une conversation avec $phone, message pré-rempli.
     * Renvoie null si le numéro est inexploitable.
     */
    public static function link(?string $phone, string $message): ?string
    {
        $normalized = app(WhatsAppService::class)->normalize($phone);
        if (! $normalized) {
            return null;
        }

        return 'https://wa.me/'.$normalized.'?text='.rawurlencode($message);
    }
}
