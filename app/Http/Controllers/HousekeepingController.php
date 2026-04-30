<?php

namespace App\Http\Controllers;

use App\Enums\RoomStatus;
use App\Models\Room;
use App\Services\HousekeepingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class HousekeepingController extends Controller
{
    public function __construct(private HousekeepingService $housekeeping) {}

    public function index()
    {
        try {
            $this->housekeeping->autoMarkDirtyRooms();

            $roomsByStatus = $this->housekeeping->getRoomsByStatus();
            $stats         = $this->housekeeping->getStats($roomsByStatus);
            $todayDepartures = $this->housekeeping->getTodayDepartures();
            $todayArrivals   = $this->housekeeping->getTodayArrivals();

            return view('housekeeping.index', compact('roomsByStatus', 'stats', 'todayDepartures', 'todayArrivals'));
        } catch (\Throwable $e) {
            Log::error('Housekeeping index: '.$e->getMessage());
            return back()->with('error', 'Erreur lors du chargement: '.$e->getMessage());
        }
    }

    public function mobile()
    {
        try {
            $dirtyRooms   = Room::with(['type', 'roomStatus'])
                ->where('room_status_id', RoomStatus::Dirty->value)
                ->orderBy('updated_at')->get();

            $cleaningRooms = Room::with(['type', 'roomStatus'])
                ->where('room_status_id', RoomStatus::Cleaning->value)
                ->orderBy('updated_at')->get();

            $stats = [
                'dirty'        => $dirtyRooms->count(),
                'cleaning'     => $cleaningRooms->count(),
                'cleaned_today' => Room::whereDate('last_cleaned_at', Carbon::today())->count(),
                'cleaned_by_me' => Room::where('cleaned_by', Auth::id())
                    ->whereDate('last_cleaned_at', Carbon::today())->count(),
            ];

            return view('housekeeping.mobile', compact('dirtyRooms', 'cleaningRooms', 'stats'));
        } catch (\Throwable $e) {
            Log::error('Housekeeping mobile: '.$e->getMessage());
            return back()->with('error', "Erreur lors du chargement de l'interface mobile");
        }
    }

    public function quickList(string $status)
    {
        $statusId = $this->housekeeping->statusIdFromSlug($status);

        if ($statusId === null) {
            return redirect()->route('housekeeping.index')->with('error', 'Statut invalide');
        }

        try {
            $rooms = Room::with(['type', 'roomStatus'])
                ->where('room_status_id', $statusId)
                ->orderBy('number')->get();

            if ($status === 'clean') {
                $rooms = $rooms->filter(fn ($r) => ! $this->housekeeping->isRoomOccupied($r->id))->values();
            }

            $labels = [
                'dirty'       => 'À nettoyer',
                'cleaning'    => 'En nettoyage',
                'clean'       => 'Nettoyées/Disponibles',
                'occupied'    => 'Occupées',
                'maintenance' => 'Maintenance',
                'reserved'    => 'Réservées',
            ];
            $statusLabel = $labels[$status] ?? ucfirst($status);

            return view('housekeeping.quick-list', compact('rooms', 'status', 'statusLabel'));
        } catch (\Throwable $e) {
            Log::error('Housekeeping quickList: '.$e->getMessage());
            return back()->with('error', 'Erreur lors du chargement de la liste');
        }
    }

    public function scan()
    {
        return view('housekeeping.scan');
    }

    public function processScan(Request $request)
    {
        $request->validate([
            'room_number' => 'required|string|max:10',
            'action'      => 'required|in:start-cleaning,finish-cleaning,maintenance',
        ]);

        $room = Room::where('number', $request->room_number)->first();

        if (! $room) {
            return back()->with('error', 'Chambre '.$request->room_number.' non trouvée');
        }

        return match ($request->action) {
            'start-cleaning'  => $this->startCleaning($room),
            'finish-cleaning' => $this->finishCleaning($room),
            'maintenance'     => redirect()->route('housekeeping.maintenance-form', $room),
            default           => back()->with('error', 'Action non reconnue'),
        };
    }

    public function toClean()
    {
        try {
            $rooms = Room::with(['type', 'roomStatus'])
                ->where(function ($q) {
                    $q->where('room_status_id', RoomStatus::Dirty->value)
                        ->orWhere('room_status_id', RoomStatus::Cleaning->value)
                        ->orWhere(function ($q2) {
                            $q2->whereHas('transactions', function ($q3) {
                                $q3->whereIn('status', ['active', 'checked_in'])
                                    ->whereDate('check_out', Carbon::today());
                            });
                        });
                })
                ->orderByRaw('CASE
                    WHEN room_status_id = ? THEN 1
                    WHEN room_status_id = ? THEN 2
                    ELSE 3
                END', [RoomStatus::Dirty->value, RoomStatus::Cleaning->value])
                ->orderBy('number')
                ->get();

            $stats = [
                'total_to_clean' => $rooms->count(),
                'dirty'          => $rooms->where('room_status_id', RoomStatus::Dirty->value)->count(),
                'cleaning'       => $rooms->where('room_status_id', RoomStatus::Cleaning->value)->count(),
                'departing_today' => $rooms->filter(fn ($r) => $r->transactions()
                    ->whereIn('status', ['active', 'checked_in'])
                    ->whereDate('check_out', Carbon::today())->exists())->count(),
            ];

            return view('housekeeping.to-clean', compact('rooms', 'stats'));
        } catch (\Throwable $e) {
            Log::error('Housekeeping toClean: '.$e->getMessage());
            return back()->with('error', 'Erreur lors du chargement des chambres à nettoyer');
        }
    }

    public function startCleaning(Room $room)
    {
        try {
            $this->housekeeping->startCleaning($room, Auth::id());
            return back()->with('success', 'Nettoyage démarré pour la chambre '.$room->number);
        } catch (\Throwable $e) {
            Log::error('startCleaning: '.$e->getMessage());
            return back()->with('error', 'Erreur lors du démarrage du nettoyage: '.$e->getMessage());
        }
    }

    public function finishCleaning(Room $room)
    {
        try {
            $newStatus  = $this->housekeeping->finishCleaning($room, Auth::id());
            $statusText = $newStatus === RoomStatus::Available ? 'Disponible' : 'Occupée';
            return back()->with('success', "Chambre {$room->number} nettoyée. Statut: {$statusText}");
        } catch (\Throwable $e) {
            Log::error('finishCleaning: '.$e->getMessage());
            return back()->with('error', 'Erreur lors du marquage comme nettoyée: '.$e->getMessage());
        }
    }

    public function showMaintenanceForm(Room $room)
    {
        return view('housekeeping.maintenance-form', compact('room'));
    }

    public function markMaintenance(Request $request, Room $room)
    {
        $request->validate([
            'maintenance_reason'   => 'required|string|max:500',
            'estimated_duration'   => 'nullable|integer|min:1',
        ]);

        try {
            $data = ['room_status_id' => RoomStatus::Maintenance->value];

            if (Schema::hasColumn('rooms', 'maintenance_reason')) {
                $data['maintenance_reason'] = $request->maintenance_reason;
            }
            if (Schema::hasColumn('rooms', 'maintenance_started_at')) {
                $data['maintenance_started_at'] = now();
            }
            if (Schema::hasColumn('rooms', 'maintenance_requested_by')) {
                $data['maintenance_requested_by'] = Auth::id();
            }
            if (Schema::hasColumn('rooms', 'estimated_maintenance_duration')) {
                $data['estimated_maintenance_duration'] = $request->estimated_duration;
            }

            $room->update($data);

            return redirect()->route('housekeeping.index')
                ->with('success', "Chambre {$room->number} marquée comme en maintenance");
        } catch (\Throwable $e) {
            Log::error('markMaintenance: '.$e->getMessage());
            return back()->with('error', 'Erreur lors du marquage en maintenance: '.$e->getMessage());
        }
    }

    public function maintenance()
    {
        try {
            $maintenanceRooms = Room::with(['type', 'roomStatus'])
                ->where('room_status_id', RoomStatus::Maintenance->value)
                ->orderBy('updated_at')->get();

            $stats = [
                'total_maintenance' => $maintenanceRooms->count(),
                'rooms_available'   => Room::where('room_status_id', RoomStatus::Available->value)->count(),
                'rooms_occupied'    => Room::where('room_status_id', RoomStatus::Occupied->value)->count(),
                'rooms_dirty'       => Room::where('room_status_id', RoomStatus::Dirty->value)->count(),
                'rooms_cleaning'    => Room::where('room_status_id', RoomStatus::Cleaning->value)->count(),
            ];

            return view('housekeeping.maintenance', compact('maintenanceRooms', 'stats'));
        } catch (\Throwable $e) {
            Log::error('maintenance page: '.$e->getMessage());
            return back()->with('error', 'Erreur: '.$e->getMessage());
        }
    }

    public function endMaintenance(Room $room)
    {
        try {
            $isOccupied = $this->housekeeping->isRoomOccupied($room->id);
            $newStatus  = $isOccupied ? RoomStatus::Occupied : RoomStatus::Available;

            $data = ['room_status_id' => $newStatus->value];

            if (Schema::hasColumn('rooms', 'maintenance_ended_at')) {
                $data['maintenance_ended_at'] = now();
            }
            if (Schema::hasColumn('rooms', 'maintenance_resolved_by')) {
                $data['maintenance_resolved_by'] = Auth::id();
            }

            $room->update($data);

            $statusText = $newStatus === RoomStatus::Available ? 'Disponible' : 'Occupée';
            return back()->with('success', "Maintenance terminée — chambre {$room->number}: {$statusText}");
        } catch (\Throwable $e) {
            Log::error('endMaintenance: '.$e->getMessage());
            return back()->with('error', 'Erreur: '.$e->getMessage());
        }
    }

    public function markInspection(Room $room)
    {
        try {
            $data = [];
            if (Schema::hasColumn('rooms', 'needs_inspection')) {
                $data['needs_inspection'] = true;
            }
            if (Schema::hasColumn('rooms', 'inspection_requested_at')) {
                $data['inspection_requested_at'] = now();
            }
            if (Schema::hasColumn('rooms', 'inspection_requested_by')) {
                $data['inspection_requested_by'] = Auth::id();
            }
            $room->update($data);

            return back()->with('success', "Inspection demandée pour la chambre {$room->number}");
        } catch (\Throwable $e) {
            Log::error('markInspection: '.$e->getMessage());
            return back()->with('error', "Erreur lors de la demande d'inspection: ".$e->getMessage());
        }
    }

    public function inspections()
    {
        try {
            $query = Room::with(['type', 'roomStatus']);

            if (! Schema::hasColumn('rooms', 'needs_inspection')) {
                return view('housekeeping.inspections', ['inspectionRooms' => collect()]);
            }

            $query->where('needs_inspection', true);

            $orderCol = Schema::hasColumn('rooms', 'inspection_requested_at')
                ? 'inspection_requested_at' : 'updated_at';

            $inspectionRooms = $query->orderBy($orderCol)->get();

            return view('housekeeping.inspections', compact('inspectionRooms'));
        } catch (\Throwable $e) {
            Log::error('inspections page: '.$e->getMessage());
            return back()->with('error', 'Erreur: '.$e->getMessage());
        }
    }

    public function completeInspection(Room $room)
    {
        try {
            $data = [];
            if (Schema::hasColumn('rooms', 'needs_inspection')) {
                $data['needs_inspection'] = false;
            }
            if (Schema::hasColumn('rooms', 'inspected_at')) {
                $data['inspected_at'] = now();
            }
            if (Schema::hasColumn('rooms', 'inspected_by')) {
                $data['inspected_by'] = Auth::id();
            }
            $room->update($data);

            return back()->with('success', "Inspection terminée pour la chambre {$room->number}");
        } catch (\Throwable $e) {
            Log::error('completeInspection: '.$e->getMessage());
            return back()->with('error', "Erreur lors de la fin d'inspection: ".$e->getMessage());
        }
    }
}
