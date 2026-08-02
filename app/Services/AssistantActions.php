<?php

namespace App\Services;

use App\Enums\RoomStatus;
use App\Exceptions\HotelException;
use App\Models\Room;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

/**
 * Registre des actions que l'assistant IA peut déclencher (function calling).
 *
 * Sécurité : chaque action d'écriture repasse par les MÊMES permissions et
 * validations que l'application (policies/rôles + services métier). L'IA ne
 * peut donc rien faire que l'utilisateur n'ait déjà le droit de faire, et les
 * actions sensibles exigent une confirmation explicite (voir isWrite()).
 */
class AssistantActions
{
    public function __construct(
        private CheckInService $checkIn,
        private HousekeepingService $housekeeping,
    ) {}

    /** Actions qui MODIFIENT des données -> confirmation obligatoire avant exécution. */
    private const WRITE = ['check_in_reservation', 'mark_room_clean'];

    /** Schémas des outils au format attendu par l'API (compatible OpenAI). */
    public function definitions(): array
    {
        return [
            $this->def('list_available_rooms', 'Liste les chambres actuellement disponibles (numéro, type, prix).'),
            $this->def('list_arrivals_today', "Liste les réservations dont l'arrivée est prévue aujourd'hui (client, chambre, numéro de réservation)."),
            $this->def('check_in_reservation', "Enregistre l'arrivée (check-in) d'une réservation à partir de son numéro.", [
                'reservation_id' => ['type' => 'integer', 'description' => 'Numéro (id) de la réservation'],
            ], ['reservation_id']),
            $this->def('mark_room_clean', 'Marque une chambre comme propre / disponible après nettoyage.', [
                'room_number' => ['type' => 'string', 'description' => 'Numéro de la chambre'],
            ], ['room_number']),
        ];
    }

    public function isWrite(string $name): bool
    {
        return in_array($name, self::WRITE, true);
    }

    /** Résumé lisible d'une action d'écriture, pour la confirmation. */
    public function summary(string $name, array $args): string
    {
        if ($name === 'check_in_reservation') {
            $tx = Transaction::with(['room', 'customer'])->find((int) ($args['reservation_id'] ?? 0));
            if (! $tx) {
                return "Check-in : réservation introuvable.";
            }

            return "Faire le check-in de la réservation #{$tx->id}, "
                .($tx->customer->name ?? 'client').', chambre '.($tx->room->number ?? '?').'.';
        }

        if ($name === 'mark_room_clean') {
            return 'Marquer la chambre '.($args['room_number'] ?? '?').' comme propre (disponible).';
        }

        return "Exécuter l'action « {$name} ».";
    }

    /** Exécute une action de LECTURE et renvoie un texte destiné au modèle. */
    public function runRead(User $user, string $name, array $args): string
    {
        return match ($name) {
            'list_available_rooms' => $this->listAvailableRooms(),
            'list_arrivals_today' => $this->listArrivalsToday(),
            default => "Action de lecture inconnue.",
        };
    }

    /**
     * Exécute une action d'ÉCRITURE (après confirmation).
     *
     * @return array{0:bool,1:string} [succès, message]
     */
    public function execute(User $user, string $name, array $args): array
    {
        return match ($name) {
            'check_in_reservation' => $this->doCheckIn($user, $args),
            'mark_room_clean' => $this->doMarkClean($user, $args),
            default => [false, "Action inconnue."],
        };
    }

    // ------------------------------------------------------------------
    // Lectures
    // ------------------------------------------------------------------

    private function listAvailableRooms(): string
    {
        $rooms = Room::with('type')
            ->where('room_status_id', RoomStatus::Available->value)
            ->orderBy('price')->get();

        if ($rooms->isEmpty()) {
            return "Aucune chambre disponible actuellement.";
        }

        return $rooms->map(function (Room $r) {
            return 'Chambre '.$r->number.' ('.($r->type->name ?? '').'), '
                .number_format((float) $r->price, 0, ',', ' ').' FCFA';
        })->implode("\n");
    }

    private function listArrivalsToday(): string
    {
        $txs = Transaction::with(['room', 'customer'])
            ->where('status', 'reservation')
            ->whereDate('check_in', today())->get();

        if ($txs->isEmpty()) {
            return "Aucune arrivée prévue aujourd'hui.";
        }

        return $txs->map(function (Transaction $t) {
            return 'Réservation #'.$t->id.', '.($t->customer->name ?? 'client')
                .', chambre '.($t->room->number ?? '?');
        })->implode("\n");
    }

    // ------------------------------------------------------------------
    // Écritures (permissions + validation métier)
    // ------------------------------------------------------------------

    private function doCheckIn(User $user, array $args): array
    {
        if (! Gate::forUser($user)->allows('updateStatus', Transaction::class)) {
            return [false, "Vous n'avez pas la permission d'effectuer un check-in."];
        }

        $tx = Transaction::with('room')->find((int) ($args['reservation_id'] ?? 0));
        if (! $tx) {
            return [false, "Réservation introuvable."];
        }

        try {
            $this->checkIn->checkIn($tx);

            return [true, '✅ Check-in effectué, chambre '.($tx->room->number ?? '?').'.'];
        } catch (HotelException $e) {
            return [false, 'Impossible : '.$e->getMessage()];
        }
    }

    private function doMarkClean(User $user, array $args): array
    {
        if (! in_array($user->role, ['Super', 'Admin', 'Manager', 'Housekeeping', 'Receptionist'], true)) {
            return [false, "Vous n'avez pas la permission de gérer le ménage."];
        }

        $room = Room::where('number', (string) ($args['room_number'] ?? ''))->first();
        if (! $room) {
            return [false, "Chambre introuvable."];
        }

        $this->housekeeping->finishCleaning($room, $user->id);

        return [true, '✅ Chambre '.$room->number.' marquée comme propre (disponible).'];
    }

    // ------------------------------------------------------------------

    private function def(string $name, string $description, array $properties = [], array $required = []): array
    {
        return [
            'type' => 'function',
            'function' => [
                'name' => $name,
                'description' => $description,
                'parameters' => [
                    'type' => 'object',
                    'properties' => (object) $properties,
                    'required' => $required,
                ],
            ],
        ];
    }
}
