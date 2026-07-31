<?php

namespace App\Console\Commands;

use App\Models\Hotel;
use App\Models\Transaction;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Envoie un rappel WhatsApp aux clients dont l'arrivée est prévue le lendemain.
 * À planifier une fois par jour (voir routes/console.php).
 *
 * En console, aucun tenant n'est fixé : le HotelScope ne filtre pas, on balaie
 * donc les réservations de tous les hôtels en une passe.
 */
class SendCheckInReminders extends Command
{
    protected $signature = 'whatsapp:reminders {--date= : Date d\'arrivée ciblée (Y-m-d), défaut = demain}';

    protected $description = 'Rappel WhatsApp aux clients arrivant le lendemain';

    public function handle(WhatsAppService $whatsapp): int
    {
        if (! $whatsapp->isConfigured()) {
            $this->warn('WhatsApp non configuré (WHATSAPP_TOKEN/PHONE_ID) — aucun rappel envoyé.');

            return self::SUCCESS;
        }

        $date = $this->option('date')
            ? Carbon::parse($this->option('date'))->toDateString()
            : Carbon::tomorrow()->toDateString();

        $reservations = Transaction::with(['customer', 'room'])
            ->whereIn('status', [Transaction::STATUS_RESERVATION, Transaction::STATUS_RESERVED_WAITING])
            ->whereDate('check_in', $date)
            ->get();

        if ($reservations->isEmpty()) {
            $this->info("Aucune arrivée le {$date}.");

            return self::SUCCESS;
        }

        $hotels = Hotel::whereIn('id', $reservations->pluck('hotel_id')->unique()->filter())
            ->get()->keyBy('id');

        $sent = 0;
        foreach ($reservations as $tx) {
            $hotel = $hotels->get($tx->hotel_id);
            $phone = $tx->customer?->phone;
            if (! $hotel || ! $phone) {
                continue;
            }

            $ref = 'RES-'.str_pad((string) $tx->id, 5, '0', STR_PAD_LEFT);
            $ci = Carbon::parse($tx->check_in)->format('d/m/Y');
            $room = $tx->room->number ?? '';

            $ok = $whatsapp->sendText(
                $phone,
                "Bonjour {$tx->customer->name} 👋\n\n".
                "Petit rappel : votre arrivée chez *{$hotel->name}* est prévue *demain {$ci}* ".
                "(réservation {$ref}, chambre {$room}).\n\n".
                'Nous avons hâte de vous accueillir ! À demain 😊'
            );

            if ($ok) {
                $sent++;
            }
        }

        $this->info("Rappels envoyés : {$sent}/{$reservations->count()} (arrivées du {$date}).");

        return self::SUCCESS;
    }
}
