<?php

namespace App\Repositories\Interfaces;

interface ReservationRepositoryInterface
{
    public function getUnocuppiedroom($request, $occupiedRoomId);

    public function countUnocuppiedroom($request, $occupiedRoomId);
}
