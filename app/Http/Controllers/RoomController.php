<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomStatus;
use App\Models\Transaction;
use App\Models\Type;
use App\Repositories\Interface\ImageRepositoryInterface;
use App\Repositories\Interface\RoomRepositoryInterface;
use App\Repositories\Interface\RoomStatusRepositoryInterface;
use App\Repositories\Interface\TypeRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log; 

class RoomController extends Controller
{
    public function __construct(
        private RoomRepositoryInterface $roomRepository,
        private TypeRepositoryInterface $typeRepository,
        private RoomStatusRepositoryInterface $roomStatusRepositoryInterface
    ) {}

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->roomRepository->getRoomsDatatable($request);
        }

        $types = $this->typeRepository->getTypeList($request);
        $roomStatuses = $this->roomStatusRepositoryInterface->getRoomStatusList($request);

        // Récupérer toutes les chambres avec leurs relations
        $rooms = Room::with(['type', 'roomStatus', 'transactions' => function ($query) {
            $query->where('status', 'active')
                ->where('check_in', '<=', now())
                ->where('check_out', '>=', now());
        }])->paginate(10);

        return view('room.index', [
            'rooms' => $rooms,
            'types' => $types,
            'roomStatuses' => $roomStatuses,
        ]);
    }

    public function create()
    {
        $types = Type::all();
        $roomstatuses = RoomStatus::all();

        // Retourner directement la vue HTML
        return view('room.create', [
            'types' => $types,
            'roomstatuses' => $roomstatuses,
        ]);
    }

    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'type_id' => 'required|exists:types,id',
            'room_status_id' => 'required|exists:room_statuses,id',
            'number' => 'required|string|max:10|unique:rooms,number',
            'name' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1|max:10',
            'price' => 'required|numeric|min:0',
            'view' => 'nullable|string|max:500',
        ], [
            'type_id.required' => 'Please select a room type',
            'room_status_id.required' => 'Please select a room status',
            'number.required' => 'Room number is required',
            'number.unique' => 'This room number already exists',
            'capacity.required' => 'Capacity is required',
            'price.required' => 'Price is required',
        ]);

        // Si validation échoue
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Préparer les données
        $data = $validator->validated();

        // S'assurer que view n'est pas null
        if (empty($data['view'])) {
            $data['view'] = '';
        }

        // S'assurer que name est null si vide
        if (empty($data['name'])) {
            $data['name'] = null;
        }

        // Créer la chambre
        $room = Room::create($data);

        // Redirection vers la liste avec message de succès
        return redirect()->route('room.index')
            ->with('success', 'Room '.$room->number.' created successfully!');
    }

    public function show(Room $room)
    {
        // Charger les relations nécessaires
        $room->load(['type', 'roomStatus', 'transactions' => function ($query) {
            $query->orderBy('check_in', 'desc')
                ->with('customer');
        }]);

        // Trouver la transaction active
        $activeTransaction = Transaction::where([
            ['check_in', '<=', Carbon::now()],
            ['check_out', '>=', Carbon::now()],
            ['room_id', $room->id],
            ['status', 'active'],
        ])->first();

        // Trouver la prochaine réservation
        $nextReservation = Transaction::where([
            ['check_in', '>', Carbon::now()],
            ['room_id', $room->id],
            ['status', 'reservation'],
        ])->orderBy('check_in', 'asc')->first();

        return view('room.show', [
            'room' => $room,
            'activeTransaction' => $activeTransaction,
            'nextReservation' => $nextReservation,
        ]);
    }

    public function edit(Room $room)
    {
        $types = Type::all();
        $roomstatuses = RoomStatus::all();

        return view('room.edit', [
            'room' => $room,
            'types' => $types,
            'roomstatuses' => $roomstatuses,
        ]);
    }

    public function update(Request $request, Room $room)
    {
        // DÉBUT : Validation personnalisée pour le statut
        // Récupérer l'utilisateur actuel
        $user = auth()->user();
        $isSystemUpdate = $request->has('_system_update');

        // Si ce n'est pas une mise à jour système et que l'utilisateur n'est pas Super Admin,
        // on empêche la modification manuelle du room_status_id
        if (! $isSystemUpdate && ! in_array($user->role, ['Super']) && $room->isDirty('room_status_id')) {
            // Rétablir la valeur originale
            $room->room_status_id = $room->getOriginal('room_status_id');

            // Ajouter un message d'information
            session()->flash('info',
                'Room status is automatically managed by the system. '.
                'It changes based on reservations and stays. '.
                'Only Super Administrators can manually set maintenance mode.');
        }
        // FIN : Validation personnalisée

        // Validation standard pour l'update
        $validator = Validator::make($request->all(), [
            'type_id' => 'required|exists:types,id',
            'room_status_id' => 'required|exists:room_statuses,id',
            'number' => 'required|string|max:10|unique:rooms,number,'.$room->id,
            'name' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1|max:10',
            'price' => 'required|numeric|min:0',
            'view' => 'nullable|string|max:500',
        ], [
            'type_id.required' => 'Please select a room type',
            'room_status_id.required' => 'Please select a room status',
            'number.required' => 'Room number is required',
            'number.unique' => 'This room number already exists',
            'capacity.required' => 'Capacity is required',
            'price.required' => 'Price is required',
        ]);

        // Si validation échoue
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Préparer les données
        $data = $validator->validated();

        // S'assurer que view n'est pas null
        if (empty($data['view'])) {
            $data['view'] = '';
        }

        // S'assurer que name est null si vide
        if (empty($data['name'])) {
            $data['name'] = null;
        }

        // Mettre à jour la chambre
        $room->update($data);

        return redirect()->route('room.index')
            ->with('success', 'Room '.$room->number.' updated successfully!');
    }

    public function destroy(Room $room, ImageRepositoryInterface $imageRepository)
    {
        try {
            $room->delete();

            // Supprimer les images associées
            $path = 'img/room/'.$room->number;
            $path = public_path($path);

            if (is_dir($path)) {
                $imageRepository->destroy($path);
            }

            // Redirection après suppression
            return redirect()->route('room.index')
                ->with('success', 'Room '.$room->number.' deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->route('room.index')
                ->with('failed', 'Room '.$room->number.' cannot be deleted!');
        }
    }

    /**
     * Toggle maintenance mode for a room
     */
    public function toggleMaintenance(Request $request, Room $room)
    {
        // Vérifier les permissions
        if (! in_array(auth()->user()->role, ['Super'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Only Super Administrators can modify room status',
            ], 403);
        }

        $action = $request->input('action'); // 'start' ou 'end'
        $reason = $request->input('reason', '');

        DB::beginTransaction();
        try {
            if ($action === 'start') {
                // Vérifier si la chambre peut être mise en maintenance
                if ($room->roomStatus->code === 'occupied') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot set to maintenance: Room is currently occupied by a guest',
                    ], 400);
                }

                // Annuler les réservations futures si existantes
                $futureReservations = $room->transactions()
                    ->where('status', 'reservation')
                    ->where('check_in', '>', now())
                    ->get();

                foreach ($futureReservations as $reservation) {
                    $reservation->update([
                        'status' => 'cancelled',
                        'cancelled_at' => now(),
                        'cancelled_by' => auth()->id(),
                        'cancel_reason' => 'Room set to maintenance mode',
                    ]);
                }

                // Mettre la chambre en maintenance
                $room->update([
                    'room_status_id' => 4, // ID pour maintenance
                    'maintenance_reason' => $reason,
                    'maintenance_started_at' => now(),
                    'maintenance_ended_at' => null,
                    'maintenance_requested_by' => auth()->id(),
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Room set to maintenance mode. '.
                                 ($futureReservations->count() ?
                                 $futureReservations->count().' future reservation(s) cancelled.' : ''),
                ]);

            } elseif ($action === 'end') {
                // Déterminer le nouveau statut basé sur les réservations
                $hasActiveReservation = $room->transactions()
                    ->where('status', 'active')
                    ->where('check_in', '<=', now())
                    ->where('check_out', '>=', now())
                    ->exists();

                $hasFutureReservation = $room->transactions()
                    ->where('status', 'reservation')
                    ->where('check_in', '>', now())
                    ->exists();

                $newStatusId = $hasActiveReservation ? 2 : ($hasFutureReservation ? 3 : 1);

                $room->update([
                    'room_status_id' => $newStatusId,
                    'maintenance_ended_at' => now(),
                    'maintenance_resolved_by' => auth()->id(),
                ]);

                DB::commit();

                $statusName = RoomStatus::find($newStatusId)->name;

                return response()->json([
                    'success' => true,
                    'message' => 'Maintenance mode ended. Room status updated to: '.$statusName,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid action. Use "start" or "end"',
                ], 400);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Maintenance toggle error: '.$e->getMessage(), [
                'room_id' => $room->id,
                'action' => $action,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get room status history
     */
    public function statusHistory(Room $room)
    {
        $statusChanges = DB::table('room_status_history')
            ->where('room_id', $room->id)
            ->orderBy('changed_at', 'desc')
            ->get();

        return view('room.status-history', [
            'room' => $room,
            'statusChanges' => $statusChanges,
        ]);
    }

    /**
     * Sync all room statuses based on transactions
     */
    public function syncStatuses()
    {
        if (! in_array(auth()->user()->role, ['Super', 'Admin'])) {
            return redirect()->back()
                ->with('error', 'Unauthorized');
        }

        $rooms = Room::all();
        $updatedCount = 0;

        foreach ($rooms as $room) {
            $this->updateRoomStatusAutomatically($room);
            $updatedCount++;
        }

        return redirect()->route('room.index')
            ->with('success', 'Synced '.$updatedCount.' room statuses based on current reservations');
    }

    /**
     * Update room status automatically based on transactions
     */
    private function updateRoomStatusAutomatically(Room $room)
    {
        $now = Carbon::now();

        // Vérifier les transactions actives
        $activeTransaction = Transaction::where('room_id', $room->id)
            ->where('status', 'active')
            ->where('check_in', '<=', $now)
            ->where('check_out', '>=', $now)
            ->first();

        // Vérifier les réservations futures
        $futureReservation = Transaction::where('room_id', $room->id)
            ->where('status', 'reservation')
            ->where('check_in', '>', $now)
            ->orderBy('check_in', 'asc')
            ->first();

        // Vérifier les arrivées aujourd'hui
        $todayCheckIn = Transaction::where('room_id', $room->id)
            ->where('status', 'reservation')
            ->whereDate('check_in', $now->toDateString())
            ->first();

        // Si en maintenance, ne pas changer
        if ($room->roomStatus->code === 'maintenance') {
            return;
        }

        // Déterminer le statut
        if ($activeTransaction) {
            $newStatusId = 2; // Occupied
        } elseif ($todayCheckIn) {
            $newStatusId = 3; // Réservée (arrivée aujourd'hui)
        } elseif ($futureReservation) {
            $newStatusId = 3; // Réservée
        } else {
            $newStatusId = 1; // Disponible
        }

        // Mettre à jour seulement si différent
        if ($room->room_status_id != $newStatusId) {
            // Enregistrer l'historique
            DB::table('room_status_history')->insert([
                'room_id' => $room->id,
                'old_status_id' => $room->room_status_id,
                'new_status_id' => $newStatusId,
                'changed_by' => auth()->id(),
                'reason' => 'Automatic update based on reservations',
                'changed_at' => now(),
            ]);

            // Mettre à jour la chambre avec le flag système
            $room->update([
                'room_status_id' => $newStatusId,
                '_system_update' => true,
            ]);
        }
    }

    /**
     * ✅ Marquer une chambre comme sale manuellement
     * 
     * @param Room $room
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsDirty(Room $room)
    {
        Log::info("🔴 Tentative de marquer la chambre #{$room->id} comme sale", [
            'user' => auth()->user()->name,
            'room' => $room->number,
            'old_status' => $room->room_status_id
        ]);

        DB::beginTransaction();

        try {
            // Vérifier si la chambre est occupée
            if ($room->isOccupied()) {
                Log::warning("❌ Tentative de marquer une chambre occupée comme sale", [
                    'room_id' => $room->id,
                    'current_status' => $room->room_status_id
                ]);

                return redirect()->back()
                    ->with('error', 'Impossible de marquer une chambre occupée comme sale. Le client est toujours présent.');
            }

            // Vérifier si la chambre est déjà sale
            if ($room->room_status_id == Room::STATUS_DIRTY) {
                return redirect()->back()
                    ->with('info', "La chambre {$room->number} est déjà marquée comme sale.");
            }

            // Vérifier si la chambre est en maintenance
            if ($room->room_status_id == Room::STATUS_MAINTENANCE) {
                return redirect()->back()
                    ->with('error', "Impossible de marquer une chambre en maintenance comme sale. Terminez d'abord la maintenance.");
            }

            // Marquer comme sale
            $room->update([
                'room_status_id' => Room::STATUS_DIRTY, // 6
                'last_cleaned_at' => null, // Réinitialiser la date de dernier nettoyage
            ]);

            // Journaliser l'action
            activity()
                ->performedOn($room)
                ->causedBy(auth()->user())
                ->withProperties([
                    'old_status' => $room->getOriginal('room_status_id'),
                    'new_status' => Room::STATUS_DIRTY,
                    'room_number' => $room->number,
                    'action' => 'mark_dirty'
                ])
                ->log('a marqué la chambre comme sale');

            DB::commit();

            Log::info("✅ Chambre #{$room->id} marquée comme sale avec succès");

            return redirect()->back()
                ->with('success', "✅ Chambre {$room->number} marquée comme sale avec succès.");

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('❌ Erreur lors du marquage de la chambre comme sale:', [
                'room_id' => $room->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Erreur lors du marquage de la chambre: ' . $e->getMessage());
        }
    }

    /**
     * ✅ Marquer une chambre comme propre (après nettoyage)
     * 
     * @param Room $room
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsClean(Room $room)
    {
        Log::info("🟢 Tentative de marquer la chambre #{$room->id} comme propre", [
            'user' => auth()->user()->name,
            'room' => $room->number,
            'old_status' => $room->room_status_id
        ]);

        DB::beginTransaction();

        try {
            // Vérifier si la chambre est occupée
            if ($room->isOccupied()) {
                return redirect()->back()
                    ->with('error', 'Impossible de marquer une chambre occupée comme propre. Le client est toujours présent.');
            }

            // Vérifier si la chambre est déjà propre
            if ($room->room_status_id == Room::STATUS_AVAILABLE) {
                return redirect()->back()
                    ->with('info', "La chambre {$room->number} est déjà marquée comme propre.");
            }

            // Vérifier si la chambre est en maintenance
            if ($room->room_status_id == Room::STATUS_MAINTENANCE) {
                return redirect()->back()
                    ->with('error', "Impossible de marquer une chambre en maintenance comme propre. Terminez d'abord la maintenance.");
            }

            // Déterminer le nouveau statut
            $newStatus = Room::STATUS_AVAILABLE; // Par défaut, disponible

            $room->update([
                'room_status_id' => $newStatus,
                'last_cleaned_at' => now(),
            ]);

            // Journaliser l'action
            activity()
                ->performedOn($room)
                ->causedBy(auth()->user())
                ->withProperties([
                    'old_status' => $room->getOriginal('room_status_id'),
                    'new_status' => $newStatus,
                    'cleaned_at' => now(),
                    'room_number' => $room->number,
                    'action' => 'mark_clean'
                ])
                ->log('a marqué la chambre comme propre');

            DB::commit();

            Log::info("✅ Chambre #{$room->id} marquée comme propre avec succès");

            return redirect()->back()
                ->with('success', "✅ Chambre {$room->number} marquée comme propre.");

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('❌ Erreur lors du marquage de la chambre comme propre:', [
                'room_id' => $room->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * ✅ Vérifier si une chambre peut être marquée comme sale
     * (Méthode utilitaire pour la vue)
     * 
     * @param Room $room
     * @return bool
     */
    public function canMarkAsDirty(Room $room): bool
    {
        // Ne peut pas être marquée comme sale si :
        // - Elle est occupée
        // - Elle est déjà sale
        // - Elle est en maintenance
        // - L'utilisateur n'a pas les droits
        
        if (!in_array(auth()->user()->role, ['Super', 'Admin', 'Housekeeping'])) {
            return false;
        }
        
        if ($room->isOccupied()) {
            return false;
        }
        
        if ($room->room_status_id == Room::STATUS_DIRTY) {
            return false;
        }
        
        if ($room->room_status_id == Room::STATUS_MAINTENANCE) {
            return false;
        }
        
        return true;
    }

    /**
     * ✅ Vérifier si une chambre peut être marquée comme propre
     * (Méthode utilitaire pour la vue)
     * 
     * @param Room $room
     * @return bool
     */
    public function canMarkAsClean(Room $room): bool
    {
        // Ne peut être marquée comme propre que si :
        // - Elle est sale
        // - L'utilisateur a les droits
        
        if (!in_array(auth()->user()->role, ['Super', 'Admin', 'Housekeeping'])) {
            return false;
        }
        
        if ($room->room_status_id != Room::STATUS_DIRTY) {
            return false;
        }
        
        return true;
    }
}
