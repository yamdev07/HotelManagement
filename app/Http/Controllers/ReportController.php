<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use App\Models\RoomStatus;

class ReportController extends Controller
{
    public function index()
    {
        // Revenue per month
        $monthlyRevenue = Booking::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        // Get STATUS IDs dynamically from their code
        $occupiedStatusId = RoomStatus::where('code', 'occupied')->value('id');
        $availableStatusId = RoomStatus::where('code', 'available')->value('id');

        // Room counts
        $occupiedRooms = Room::where('room_status_id', $occupiedStatusId)->count();
        $availableRooms = Room::where('room_status_id', $availableStatusId)->count();

        return view('reports.index', [
            'monthlyRevenue' => $monthlyRevenue,
            'occupancy' => $occupiedRooms,
            'available' => $availableRooms,
        ]);
    }
}
