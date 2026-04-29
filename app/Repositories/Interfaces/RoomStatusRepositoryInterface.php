<?php

namespace App\Repositories\Interfaces;

use Illuminate\Http\Request;

interface RoomStatusRepositoryInterface
{
    /**
     * @deprecated since updated to getDatatable
     */
    public function getRoomStatuses(Request $request);

    public function getDatatable(Request $request);

    public function getRoomStatusList(Request $request);
}
