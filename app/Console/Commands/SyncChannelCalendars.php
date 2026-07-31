<?php

namespace App\Console\Commands;

use App\Models\RoomCalendarFeed;
use App\Services\IcalService;
use Illuminate\Console\Command;

/**
 * Importe tous les calendriers OTA configurés (Booking, Airbnb, ...) pour
 * bloquer localement les dates déjà vendues ailleurs (anti-double-réservation).
 * À planifier régulièrement (voir routes/console.php).
 */
class SyncChannelCalendars extends Command
{
    protected $signature = 'ical:sync {--room= : Limiter à une chambre (id)}';

    protected $description = 'Synchronise les calendriers OTA (import iCal) pour bloquer les dates vendues ailleurs';

    public function handle(IcalService $ical): int
    {
        $feeds = RoomCalendarFeed::query()
            ->when($this->option('room'), fn ($q) => $q->where('room_id', $this->option('room')))
            ->get();

        if ($feeds->isEmpty()) {
            $this->info('Aucun calendrier OTA configuré.');

            return self::SUCCESS;
        }

        $ok = 0;
        $ko = 0;
        foreach ($feeds as $feed) {
            $result = $ical->syncFeed($feed);
            if ($result['ok']) {
                $ok++;
                $this->line("✓ Flux #{$feed->id} ({$feed->source}) : {$result['count']} période(s).");
            } else {
                $ko++;
                $this->warn("✗ Flux #{$feed->id} ({$feed->source}) : {$result['error']}");
            }
        }

        $this->info("Synchronisation terminée : {$ok} OK, {$ko} en erreur.");

        return self::SUCCESS;
    }
}
