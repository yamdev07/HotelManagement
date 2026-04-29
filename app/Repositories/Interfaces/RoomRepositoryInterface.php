<?php

namespace App\Repositories\Interfaces;

interface RoomRepositoryInterface
{
    public function getRooms($request);

    public function getRoomsDatatable($request);
}
