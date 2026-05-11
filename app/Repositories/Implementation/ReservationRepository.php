<?php

namespace App\Repositories\Implementation;

use App\Models\Room;
use App\Repositories\Interfaces\ReservationRepositoryInterface;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function getUnocuppiedroom($request, $occupiedRoomId)
    {
        $query = Room::with('type', 'roomStatus')
            ->where('capacity', '>=', $request->count_person)
            ->whereNotIn('id', $occupiedRoomId)
            ->whereIn('room_status_id', [1, 3]);

        if (! empty($request->sort_name)) {
            $query->orderBy($request->sort_name, $request->sort_type);
        }

        $query->orderBy('capacity');

        return $query->paginate(5);
    }

    public function countUnocuppiedroom($request, $occupiedRoomId)
    {
        return Room::with('type', 'roomStatus')
            ->where('capacity', '>=', $request->count_person)
            ->whereNotIn('id', $occupiedRoomId)
            ->orderBy('price')
            ->orderBy('capacity')
            ->count();
    }
}
