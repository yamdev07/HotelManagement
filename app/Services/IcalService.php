<?php

namespace App\Services;

use App\Models\Room;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Génère et analyse des flux iCal (RFC 5545) pour la synchronisation de
 * disponibilité avec les OTA (Booking.com, Airbnb, ...).
 *
 * Format simple « journée entière » (VALUE=DATE) : DTEND est exclusif
 * (le jour du départ n'est pas bloqué), convention des OTA.
 */
class IcalService
{
    /** Statuts de réservation qui bloquent une chambre (donc exportés). */
    private const BLOCKING_STATUSES = [
        Transaction::STATUS_RESERVATION,
        Transaction::STATUS_RESERVED_WAITING,
        Transaction::STATUS_ACTIVE,
        Transaction::STATUS_PENDING_CHECKOUT,
    ];

    /** Construit le flux iCal d'une chambre à partir de ses réservations. */
    public function buildRoomFeed(Room $room): string
    {
        $reservations = $room->transactions()
            ->whereIn('status', self::BLOCKING_STATUSES)
            ->whereNotNull('check_in')
            ->whereNotNull('check_out')
            ->whereDate('check_out', '>=', Carbon::today()->subDay()->toDateString())
            ->get();

        $host = parse_url(config('app.url'), PHP_URL_HOST) ?: 'checkinhub';

        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//checkinHub//Room Calendar//FR',
            'CALSCALE:GREGORIAN',
            'METHOD:PUBLISH',
            'X-WR-CALNAME:'.$this->escape('Chambre '.($room->number ?? $room->id)),
        ];

        foreach ($reservations as $tx) {
            $start = Carbon::parse($tx->check_in);
            $end = Carbon::parse($tx->check_out);
            // DTEND exclusif : au minimum le lendemain de l'arrivée.
            if ($end->lessThanOrEqualTo($start)) {
                $end = $start->copy()->addDay();
            }

            $lines[] = 'BEGIN:VEVENT';
            $lines[] = 'UID:room-'.$room->id.'-tx-'.$tx->id.'@'.$host;
            $lines[] = 'DTSTAMP:'.now()->utc()->format('Ymd\THis\Z');
            $lines[] = 'DTSTART;VALUE=DATE:'.$start->format('Ymd');
            $lines[] = 'DTEND;VALUE=DATE:'.$end->format('Ymd');
            $lines[] = 'SUMMARY:'.$this->escape('Indisponible');
            $lines[] = 'END:VEVENT';
        }

        $lines[] = 'END:VCALENDAR';

        return implode("\r\n", $lines)."\r\n";
    }

    /**
     * Analyse un contenu iCal et renvoie les périodes bloquées.
     *
     * @return Collection<int, array{uid:string, start:Carbon, end:Carbon, summary:string}>
     */
    public function parseEvents(string $ics): Collection
    {
        $events = collect();
        // Déplie les lignes repliées (RFC 5545 : continuation par espace/tab).
        $unfolded = preg_replace("/\r\n[ \t]/", '', str_replace("\n", "\r\n", str_replace("\r\n", "\n", $ics)));
        $lines = preg_split('/\r\n|\n/', (string) $unfolded);

        $cur = null;
        foreach ($lines as $line) {
            if ($line === 'BEGIN:VEVENT') {
                $cur = ['uid' => '', 'start' => null, 'end' => null, 'summary' => ''];

                continue;
            }
            if ($line === 'END:VEVENT') {
                if ($cur && $cur['start'] && $cur['end']) {
                    if ($cur['uid'] === '') {
                        $cur['uid'] = md5($cur['start']->format('Ymd').$cur['end']->format('Ymd'));
                    }
                    $events->push($cur);
                }
                $cur = null;

                continue;
            }
            if ($cur === null) {
                continue;
            }

            [$name, $value] = $this->splitLine($line);
            if ($name === '') {
                continue;
            }

            $key = strtoupper(explode(';', $name)[0]);
            match ($key) {
                'UID' => $cur['uid'] = trim($value),
                'DTSTART' => $cur['start'] = $this->parseDate($value),
                'DTEND' => $cur['end'] = $this->parseDate($value),
                'SUMMARY' => $cur['summary'] = $this->unescape(trim($value)),
                default => null,
            };
        }

        return $events->filter(fn ($e) => $e['start'] && $e['end'])->values();
    }

    /** @return array{0:string,1:string} [nom (avec params), valeur] */
    private function splitLine(string $line): array
    {
        $pos = strpos($line, ':');
        if ($pos === false) {
            return ['', ''];
        }

        return [substr($line, 0, $pos), substr($line, $pos + 1)];
    }

    private function parseDate(?string $value): ?Carbon
    {
        if (! $value) {
            return null;
        }
        $value = trim($value);
        // Formats : 20260731, 20260731T140000, 20260731T140000Z
        $digits = preg_replace('/[^0-9TZ]/', '', $value);
        try {
            if (strlen($digits) >= 8) {
                return Carbon::parse($value);
            }
        } catch (\Throwable $e) {
            return null;
        }

        return null;
    }

    private function escape(string $text): string
    {
        return str_replace(['\\', ';', ',', "\n"], ['\\\\', '\\;', '\\,', '\\n'], $text);
    }

    private function unescape(string $text): string
    {
        return str_replace(['\\n', '\\,', '\\;', '\\\\'], ["\n", ',', ';', '\\'], $text);
    }
}
