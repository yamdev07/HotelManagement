<?php

namespace App\Repositories\Implementation;

use App\Models\Room;
use App\Models\RoomStatus;
use App\Repositories\Interfaces\ReservationRepositoryInterface;

class ReservationRepository implements ReservationRepositoryInterface
{
    /**
     * Chambres réellement réservables pour la période demandée (issue #193).
     *
     * Le conflit de dates est déjà géré par $occupiedRoomId (transactions qui
     * chevauchent la période). Le statut « courant » d'une chambre (Occupée /
     * Réservée) ne doit donc PAS bloquer une réservation pour d'autres dates :
     * on exclut seulement les chambres hors service (maintenance).
     *
     * Le compteur ET la liste utilisent CETTE MÊME requête : plus de décalage
     * « le système annonce des chambres dispo mais la liste est vide ».
     */
    private function bookableQuery($request, $occupiedRoomId)
    {
        // Résolu par CODE, pas par ID en dur (les IDs varient selon les installs).
        $outOfService = RoomStatus::whereIn('code', ['MNT'])->pluck('id')->all();

        // count_person peut être absent (ex : retour arrière depuis l'étape 4, où
        // le lien ne repasse pas ce paramètre) : on retombe sur 1 pour ne pas
        // renvoyer une liste vide (capacity >= NULL ne matche aucune chambre).
        $minCapacity = (int) ($request->count_person ?? 1) ?: 1;

        return Room::with('type', 'roomStatus')
            ->where('capacity', '>=', $minCapacity)
            ->whereNotIn('id', $occupiedRoomId)
            ->when(! empty($outOfService), fn ($q) => $q->whereNotIn('room_status_id', $outOfService));
    }

    public function getUnocuppiedroom($request, $occupiedRoomId)
    {
        $query = $this->bookableQuery($request, $occupiedRoomId);

        if (! empty($request->sort_name)) {
            $query->orderBy($request->sort_name, $request->sort_type);
        }

        $query->orderBy('capacity');

        return $query->paginate(5);
    }

    public function countUnocuppiedroom($request, $occupiedRoomId)
    {
        return $this->bookableQuery($request, $occupiedRoomId)->count();
    }
}
