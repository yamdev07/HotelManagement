<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomStatusRequest;
use App\Models\RoomStatus;
use App\Repositories\Interface\RoomStatusRepositoryInterface;
use Illuminate\Http\Request;

class RoomStatusController extends Controller
{
    public function __construct(
        private RoomStatusRepositoryInterface $roomStatusRepository
    ) {}

    /**
     * AJOUTER CETTE MÉTHODE POUR INITIALISER LES STATUTS
     */
    public function initializeDefaults()
    {
        try {
            $defaultStatuses = [
                [
                    'id' => 1,
                    'name' => 'Available',
                    'code' => 'AVL',
                    'information' => 'Room is available for booking',
                    'color' => '#28a745',
                    'is_available' => true,
                ],
                [
                    'id' => 2,
                    'name' => 'Occupied',
                    'code' => 'OCC',
                    'information' => 'Room is currently occupied',
                    'color' => '#007bff',
                    'is_available' => false,
                ],
                [
                    'id' => 3,
                    'name' => 'Maintenance',
                    'code' => 'MNT',
                    'information' => 'Room is under maintenance',
                    'color' => '#6c757d',
                    'is_available' => false,
                ],
                [
                    'id' => 4,
                    'name' => 'Reserved',
                    'code' => 'RSV',
                    'information' => 'Room is reserved',
                    'color' => '#ffc107',
                    'is_available' => false,
                ],
                [
                    'id' => 5,
                    'name' => 'Cleaning',
                    'code' => 'CLN',
                    'information' => 'Room is being cleaned',
                    'color' => '#17a2b8',
                    'is_available' => false,
                ],
                [
                    'id' => 6,
                    'name' => 'Dirty',
                    'code' => 'DRT',
                    'information' => 'Room needs cleaning',
                    'color' => '#dc3545',
                    'is_available' => false,
                ],
            ];

            foreach ($defaultStatuses as $status) {
                RoomStatus::updateOrCreate(
                    ['id' => $status['id']],
                    $status
                );
            }

            return response()->json([
                'message' => 'Statuses initialized successfully!',
                'count' => count($defaultStatuses),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: '.$e->getMessage(),
            ], 500);
        }
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->roomStatusRepository->getDatatable($request);
        }

        return view('roomstatus.index');
    }

    public function create()
    {
        return response()->json([
            'view' => view('roomstatus.create')->render(),
        ]);
    }

    public function store(StoreRoomStatusRequest $request)
    {
        $roomstatus = RoomStatus::create($request->all());

        return response()->json([
            'message' => 'success', "Room $roomstatus->name created",
        ]);
    }

    public function edit(RoomStatus $roomstatus)
    {
        $view = view('roomstatus.edit', [
            'roomstatus' => $roomstatus,
        ])->render();

        return response()->json([
            'view' => $view,
        ]);
    }

    public function update(StoreRoomStatusRequest $request, RoomStatus $roomstatus)
    {
        $roomstatus->update($request->all());

        return response()->json([
            'message' => 'success', "Room $roomstatus->name udpated",
        ]);
    }

    public function destroy(RoomStatus $roomstatus)
    {
        try {
            $roomstatus->delete();

            return response()->json([
                'message' => "Room $roomstatus->name deleted!",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Type {$roomstatus->name} cannot be deleted! Error Code: {$e->errorInfo[1]}",
            ], 500);
        }
    }
}
