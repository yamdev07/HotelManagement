<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomStatus;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HousekeepingController extends Controller
{
    // Constantes basées sur VOTRE table room_statuses
    const STATUS_AVAILABLE = 1;      // Available (Disponible)
    const STATUS_OCCUPIED = 2;       // Occupied (Occupée)
    const STATUS_MAINTENANCE = 3;    // Maintenance (Maintenance)
    const STATUS_RESERVED = 4;       // Reserved (Réservée)
    const STATUS_CLEANING = 5;       // Cleaning (En nettoyage)
    const STATUS_DIRTY = 6;          // Dirty (Sale/À nettoyer) - AJOUTÉ
    
    public function index()
    {
        try {
            // 1. Marquer automatiquement les chambres occupées comme sales
            $this->autoMarkDirtyRooms();
            
            // 2. Récupérer toutes les chambres
            $rooms = Room::with(['type', 'roomStatus'])
                ->orderBy('number')
                ->get();
            
            // 3. Calculer le statut "is_occupied" pour chaque chambre
            foreach ($rooms as $room) {
                $room->is_occupied = $this->isRoomOccupied($room->id);
            }
            
            // 4. Grouper par statut CORRECTEMENT
            $roomsByStatus = [
                'dirty' => $rooms->where('room_status_id', self::STATUS_DIRTY)->values(),
                'cleaning' => $rooms->where('room_status_id', self::STATUS_CLEANING)->values(),
                'clean' => $rooms->where('room_status_id', self::STATUS_AVAILABLE)
                    ->filter(function($room) {
                        return !$room->is_occupied;
                    })->values(),
                'occupied' => $rooms->filter(function($room) {
                    return $room->is_occupied || $room->room_status_id == self::STATUS_OCCUPIED;
                })->unique('id')->values(),
                'maintenance' => $rooms->where('room_status_id', self::STATUS_MAINTENANCE)->values(),
                'reserved' => $rooms->where('room_status_id', self::STATUS_RESERVED)->values(),
            ];
            
            // 5. Statistiques
            $stats = [
                'total_rooms' => $rooms->count(),
                'dirty_rooms' => $roomsByStatus['dirty']->count(),
                'cleaning_rooms' => $roomsByStatus['cleaning']->count(),
                'clean_rooms' => $roomsByStatus['clean']->count(),
                'occupied_rooms' => $roomsByStatus['occupied']->count(),
                'maintenance_rooms' => $roomsByStatus['maintenance']->count(),
                'reserved_rooms' => $roomsByStatus['reserved']->count(),
                'cleaned_today' => Room::whereDate('last_cleaned_at', Carbon::today())->count(),
            ];
            
            // 6. Départs du jour (chambres à nettoyer)
            $todayDepartures = Transaction::with(['room', 'customer'])
                ->whereIn('status', ['active', 'checked_in'])
                ->whereDate('check_out', Carbon::today())
                ->orderBy('check_out')
                ->get();
            
            // 7. Arrivées du jour (chambres préparées)
            $todayArrivals = Transaction::with(['room', 'customer'])
                ->whereIn('status', ['reservation', 'confirmed'])
                ->whereDate('check_in', Carbon::today())
                ->orderBy('check_in')
                ->get();
            
            return view('housekeeping.index', compact(
                'roomsByStatus',
                'stats',
                'todayDepartures',
                'todayArrivals'
                // Supprimez $roomsCleanedToday - utilisez $stats['cleaned_today'] dans le template
            ));
            
        } catch (\Exception $e) {
            Log::error('Housekeeping index error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du chargement du dashboard: ' . $e->getMessage());
        }
    }
    
    /**
     * Vérifier si une chambre est occupée
     */
    private function isRoomOccupied($roomId)
    {
        return Transaction::where('room_id', $roomId)
            ->whereIn('status', ['active', 'checked_in'])
            ->where('check_in', '<=', now())
            ->where('check_out', '>=', now())
            ->exists();
    }
    
    /**
     * Marquer automatiquement les chambres occupées comme sales
     */
    private function autoMarkDirtyRooms()
    {
        try {
            $occupiedRooms = Room::where('room_status_id', self::STATUS_OCCUPIED)
                ->where(function($query) {
                    $query->whereNull('last_cleaned_at')
                          ->orWhereDate('last_cleaned_at', '<', Carbon::today());
                })
                ->get();
            
            foreach ($occupiedRooms as $room) {
                $room->update([
                    'room_status_id' => self::STATUS_DIRTY,
                    'needs_cleaning' => 1,
                    'updated_at' => now(),
                ]);
                
                Log::info("Auto-marked room {$room->number} as DIRTY (needs cleaning)");
            }
            
            return count($occupiedRooms);
            
        } catch (\Exception $e) {
            Log::error('Auto-mark error: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Interface mobile/simplifiée
     */
    public function mobile()
    {
        try {
            // Chambres à nettoyer (Dirty)
            $dirtyRooms = Room::with(['type', 'roomStatus'])
                ->where('room_status_id', self::STATUS_DIRTY)
                ->orderBy('updated_at', 'asc')
                ->get();
            
            // Chambres en cours de nettoyage
            $cleaningRooms = Room::with(['type', 'roomStatus'])
                ->where('room_status_id', self::STATUS_CLEANING)
                ->orderBy('updated_at', 'asc')
                ->get();
            
            // Statistiques
            $stats = [
                'dirty' => $dirtyRooms->count(),
                'cleaning' => $cleaningRooms->count(),
                'cleaned_today' => Room::whereDate('last_cleaned_at', Carbon::today())->count(),
                'cleaned_by_me' => Room::where('cleaned_by', Auth::id())
                    ->whereDate('last_cleaned_at', Carbon::today())
                    ->count(),
            ];
            
            return view('housekeeping.mobile', compact(
                'dirtyRooms',
                'cleaningRooms',
                'stats'
            ));
            
        } catch (\Exception $e) {
            Log::error('Housekeeping mobile error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du chargement de l\'interface mobile');
        }
    }
    
    /**
     * Liste rapide des chambres par statut - CORRIGÉE
     */
    public function quickList($status)
    {
        try {
            $statusMap = [
                'dirty' => self::STATUS_DIRTY,      // CHANGÉ ICI
                'cleaning' => self::STATUS_CLEANING,
                'clean' => self::STATUS_AVAILABLE,
                'occupied' => self::STATUS_OCCUPIED,
                'maintenance' => self::STATUS_MAINTENANCE,
                'reserved' => self::STATUS_RESERVED,
            ];
            
            if (!isset($statusMap[$status])) {
                return redirect()->route('housekeeping.index')
                    ->with('error', 'Statut invalide');
            }
            
            $rooms = Room::with(['type', 'roomStatus'])
                ->where('room_status_id', $statusMap[$status])
                ->orderBy('number')
                ->get();
            
            // Pour les chambres "clean", filtrer celles qui ne sont pas occupées
            if ($status == 'clean') {
                $rooms = $rooms->filter(function($room) {
                    return !$this->isRoomOccupied($room->id);
                })->values();
            }
            
            $statusLabels = [
                'dirty' => 'À nettoyer',
                'cleaning' => 'En nettoyage',
                'clean' => 'Nettoyées/Disponibles',
                'occupied' => 'Occupées',
                'maintenance' => 'Maintenance',
                'reserved' => 'Réservées',
            ];
            
            $statusLabel = $statusLabels[$status] ?? ucfirst($status);
            
            return view('housekeeping.quick-list', compact(
                'rooms',
                'status',
                'statusLabel'
            ));
            
        } catch (\Exception $e) {
            Log::error('Housekeeping quickList error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du chargement de la liste');
        }
    }
    
    /**
     * Scanner QR code
     */
    public function scan()
    {
        return view('housekeeping.scan');
    }
    
    /**
     * Traiter le scan QR code - AMÉLIORÉ
     */
    public function processScan(Request $request)
    {
        try {
            $request->validate([
                'room_number' => 'required|string|max:10',
                'action' => 'required|in:start-cleaning,finish-cleaning,maintenance'
            ]);
            
            $room = Room::where('number', $request->room_number)->first();
            
            if (!$room) {
                return back()->with('error', 'Chambre ' . $request->room_number . ' non trouvée');
            }
            
            switch ($request->action) {
                case 'start-cleaning':
                    return $this->startCleaning($room);
                case 'finish-cleaning':
                    return $this->finishCleaning($room);
                case 'maintenance':
                    return redirect()->route('housekeeping.maintenance-form', $room);
            }
            
            return back()->with('error', 'Action non reconnue');
            
        } catch (\Exception $e) {
            Log::error('Housekeeping processScan error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du traitement du scan');
        }
    }
    
    /**
     * Chambres à nettoyer - CORRIGÉ
     */
    public function toClean()
    {
        try {
            $rooms = Room::with(['type', 'roomStatus'])
                ->where('room_status_id', self::STATUS_DIRTY)  // CHANGÉ ICI
                ->orWhere('room_status_id', self::STATUS_CLEANING)
                ->orWhere(function($query) {
                    // Chambres avec départ aujourd'hui
                    $query->whereHas('transactions', function($q) {
                        $q->whereIn('status', ['active', 'checked_in'])
                          ->whereDate('check_out', Carbon::today());
                    });
                })
                ->orderByRaw("
                    CASE 
                        WHEN room_status_id = " . self::STATUS_DIRTY . " THEN 1
                        WHEN room_status_id = " . self::STATUS_CLEANING . " THEN 2
                        ELSE 3
                    END
                ")
                ->orderBy('number')
                ->get();
            
            // Statistiques
            $stats = [
                'total_to_clean' => $rooms->count(),
                'dirty' => $rooms->where('room_status_id', self::STATUS_DIRTY)->count(),
                'cleaning' => $rooms->where('room_status_id', self::STATUS_CLEANING)->count(),
                'departing_today' => $rooms->filter(function($room) {
                    return $room->transactions()
                        ->whereIn('status', ['active', 'checked_in'])
                        ->whereDate('check_out', Carbon::today())
                        ->exists();
                })->count(),
            ];
            
            return view('housekeeping.to-clean', compact('rooms', 'stats'));
            
        } catch (\Exception $e) {
            Log::error('Housekeeping toClean error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du chargement des chambres à nettoyer');
        }
    }
    
    /**
     * Marquer comme en nettoyage - AMÉLIORÉ
     */
    public function startCleaning(Room $room)
    {
        DB::beginTransaction();
        
        try {
            $oldStatus = $room->room_status_id;
            $user = Auth::user();
            
            $updateData = [
                'room_status_id' => self::STATUS_CLEANING,
                'updated_at' => now(),
            ];
            
            // Ajouter les colonnes si elles existent
            if (Schema::hasColumn('rooms', 'cleaning_started_at')) {
                $updateData['cleaning_started_at'] = now();
            }
            
            if (Schema::hasColumn('rooms', 'cleaned_by')) {
                $updateData['cleaned_by'] = $user->id;
            }
            
            $room->update($updateData);
            
            // Journalisation
            if (class_exists('\Spatie\Activitylog\Models\Activity')) {
                activity()
                    ->performedOn($room)
                    ->causedBy($user)
                    ->withProperties([
                        'old_status' => $oldStatus,
                        'new_status' => self::STATUS_CLEANING,
                        'room_number' => $room->number
                    ])
                    ->log('Nettoyage démarré');
            }
            
            // Notification à la réception
            $this->notifyReception(
                $room, 
                'cleaning_started', 
                "🚿 Nettoyage démarré pour la chambre {$room->number} par {$user->name}"
            );
            
            DB::commit();
            
            return back()->with('success', 'Nettoyage démarré pour la chambre ' . $room->number);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erreur démarrage nettoyage: ' . $e->getMessage(), [
                'room_id' => $room->id,
                'user_id' => Auth::id(),
            ]);
            
            return back()->with('error', 'Erreur lors du démarrage du nettoyage: ' . $e->getMessage());
        }
    }
    
    /**
     * Marquer comme nettoyée - AMÉLIORÉ
     */
    public function finishCleaning(Room $room)
    {
        DB::beginTransaction();
        
        try {
            $oldStatus = $room->room_status_id;
            $user = Auth::user();
            
            // Vérifier si la chambre est occupée
            $isOccupied = $this->isRoomOccupied($room->id);
            
            // Déterminer le nouveau statut
            $newStatus = $isOccupied ? self::STATUS_OCCUPIED : self::STATUS_AVAILABLE;
            
            $updateData = [
                'room_status_id' => $newStatus,
                'needs_cleaning' => 0,
                'updated_at' => now(),
            ];
            
            // Ajouter les colonnes si elles existent
            if (Schema::hasColumn('rooms', 'cleaning_completed_at')) {
                $updateData['cleaning_completed_at'] = now();
            }
            
            if (Schema::hasColumn('rooms', 'cleaned_by')) {
                $updateData['cleaned_by'] = $user->id;
            }
            
            if (Schema::hasColumn('rooms', 'last_cleaned_at')) {
                $updateData['last_cleaned_at'] = now();
            }
            
            $room->update($updateData);
            
            // Journalisation
            if (class_exists('\Spatie\Activitylog\Models\Activity')) {
                activity()
                    ->performedOn($room)
                    ->causedBy($user)
                    ->withProperties([
                        'old_status' => $oldStatus,
                        'new_status' => $newStatus,
                        'room_number' => $room->number,
                        'cleaned_by' => $user->name
                    ])
                    ->log('Chambre nettoyée');
            }
            
            // Notification IMPORTANTE à la réception
            $statusText = $newStatus == self::STATUS_AVAILABLE ? 'DISPONIBLE' : 'OCCUPÉE (nettoyée)';
            $this->notifyReception(
                $room,
                'room_cleaned',
                "✅ Chambre {$room->number} nettoyée par {$user->name} - Statut: {$statusText}",
                true // Notification importante
            );
            
            DB::commit();
            
            $message = 'Chambre ' . $room->number . ' marquée comme nettoyée. ';
            $message .= 'Statut: ' . ($newStatus == self::STATUS_AVAILABLE ? 'Disponible' : 'Occupée');
            
            return back()->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erreur marquage nettoyée: ' . $e->getMessage(), [
                'room_id' => $room->id,
                'user_id' => Auth::id(),
            ]);
            
            return back()->with('error', 'Erreur lors du marquage comme nettoyée: ' . $e->getMessage());
        }
    }
    
    /**
     * Notifier la réception
     */
    private function notifyReception($room, $type, $message, $important = false)
    {
        try {
            // Récupérer les réceptionnistes et administrateurs
            $receptionists = User::whereHas('roles', function($query) {
                $query->whereIn('name', ['receptionist', 'admin', 'manager']);
            })->get();
            
            foreach ($receptionists as $receptionist) {
                Notification::create([
                    'user_id' => $receptionist->id,
                    'type' => $type,
                    'title' => $important ? '🚨 Housekeeping Important' : 'Housekeeping Update',
                    'message' => $message,
                    'data' => json_encode([
                        'room_id' => $room->id,
                        'room_number' => $room->number,
                        'action_url' => route('rooms.show', $room),
                        'important' => $important
                    ]),
                    'read_at' => null,
                    'created_at' => now(),
                ]);
            }
            
            Log::info("Notification envoyée: {$message}");
            
        } catch (\Exception $e) {
            Log::error('Erreur notification: ' . $e->getMessage());
        }
    }
    
    /**
     * Marquer comme à inspecter
     */
    public function markInspection(Room $room)
    {
        try {
            $updateData = [
                'updated_at' => now(),
            ];
            
            if (Schema::hasColumn('rooms', 'needs_inspection')) {
                $updateData['needs_inspection'] = true;
            }
            
            if (Schema::hasColumn('rooms', 'inspection_requested_at')) {
                $updateData['inspection_requested_at'] = now();
            }
            
            if (Schema::hasColumn('rooms', 'inspection_requested_by')) {
                $updateData['inspection_requested_by'] = Auth::id();
            }
            
            $room->update($updateData);
            
            $this->notifyReception(
                $room,
                'inspection_requested',
                "🔍 Inspection demandée pour la chambre {$room->number}"
            );
            
            return back()->with('success', 'Inspection demandée pour la chambre ' . $room->number);
            
        } catch (\Exception $e) {
            Log::error('Erreur demande inspection: ' . $e->getMessage(), [
                'room_id' => $room->id,
                'user_id' => Auth::id(),
            ]);
            
            return back()->with('error', 'Erreur lors de la demande d\'inspection: ' . $e->getMessage());
        }
    }
    
    /**
     * Afficher le formulaire de maintenance
     */
    public function showMaintenanceForm(Room $room)
    {
        return view('housekeeping.maintenance-form', compact('room'));
    }
    
    /**
     * Marquer comme en maintenance - AMÉLIORÉ
     */
    public function markMaintenance(Request $request, Room $room)
    {
        try {
            $request->validate([
                'maintenance_reason' => 'required|string|max:500',
                'estimated_duration' => 'nullable|integer|min:1',
            ]);
            
            DB::beginTransaction();
            
            $oldStatus = $room->room_status_id;
            $user = Auth::user();
            
            $updateData = [
                'room_status_id' => self::STATUS_MAINTENANCE,
                'updated_at' => now(),
            ];
            
            if (Schema::hasColumn('rooms', 'maintenance_reason')) {
                $updateData['maintenance_reason'] = $request->maintenance_reason;
            }
            
            if (Schema::hasColumn('rooms', 'maintenance_started_at')) {
                $updateData['maintenance_started_at'] = now();
            }
            
            if (Schema::hasColumn('rooms', 'maintenance_requested_by')) {
                $updateData['maintenance_requested_by'] = $user->id;
            }
            
            if (Schema::hasColumn('rooms', 'estimated_maintenance_duration')) {
                $updateData['estimated_maintenance_duration'] = $request->estimated_duration;
            }
            
            $room->update($updateData);
            
            // Journalisation
            if (class_exists('\Spatie\Activitylog\Models\Activity')) {
                activity()
                    ->performedOn($room)
                    ->causedBy($user)
                    ->withProperties([
                        'old_status' => $oldStatus,
                        'new_status' => self::STATUS_MAINTENANCE,
                        'room_number' => $room->number,
                        'reason' => $request->maintenance_reason
                    ])
                    ->log('Maintenance demandée');
            }
            
            // Notification IMPORTANTE à la réception
            $this->notifyReception(
                $room,
                'maintenance_started',
                "🔧 MAINTENANCE: Chambre {$room->number} - Raison: {$request->maintenance_reason}",
                true
            );
            
            DB::commit();
            
            return redirect()->route('housekeeping.index')
                ->with('success', 'Chambre ' . $room->number . ' marquée comme en maintenance');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erreur maintenance: ' . $e->getMessage(), [
                'room_id' => $room->id,
                'user_id' => Auth::id(),
            ]);
            
            return back()->with('error', 'Erreur lors du marquage en maintenance: ' . $e->getMessage());
        }
    }
    
    /**
     * Chambres en maintenance
     */
    public function maintenance()
    {
        try {
            $maintenanceRooms = Room::with(['type', 'roomStatus'])
                ->where('room_status_id', self::STATUS_MAINTENANCE)
                ->orderBy('updated_at', 'asc')
                ->get();
            
            $stats = [
                'total_maintenance' => $maintenanceRooms->count(),
                'rooms_available' => Room::where('room_status_id', self::STATUS_AVAILABLE)->count(),
                'rooms_occupied' => Room::where('room_status_id', self::STATUS_OCCUPIED)->count(),
                'rooms_dirty' => Room::where('room_status_id', self::STATUS_DIRTY)->count(),
                'rooms_cleaning' => Room::where('room_status_id', self::STATUS_CLEANING)->count(),
            ];
            
            return view('housekeeping.maintenance', compact('maintenanceRooms', 'stats'));
            
        } catch (\Exception $e) {
            Log::error('Maintenance page error: ' . $e->getMessage());
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
    
    /**
     * Chambres à inspecter
     */
    public function inspections()
    {
        try {
            $query = Room::with(['type', 'roomStatus']);
            
            if (Schema::hasColumn('rooms', 'needs_inspection')) {
                $query->where('needs_inspection', true);
            } else {
                return view('housekeeping.inspections', ['inspectionRooms' => collect()]);
            }
            
            if (Schema::hasColumn('rooms', 'inspection_requested_at')) {
                $query->orderBy('inspection_requested_at', 'asc');
            } else {
                $query->orderBy('updated_at', 'asc');
            }
            
            $inspectionRooms = $query->get();
            
            return view('housekeeping.inspections', compact('inspectionRooms'));
            
        } catch (\Exception $e) {
            Log::error('Inspections page error: ' . $e->getMessage());
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
    
    /**
     * Marquer l'inspection comme terminée
     */
    public function completeInspection(Room $room)
    {
        try {
            $user = Auth::user();
            $updateData = [
                'updated_at' => now(),
            ];
            
            if (Schema::hasColumn('rooms', 'needs_inspection')) {
                $updateData['needs_inspection'] = false;
            }
            
            if (Schema::hasColumn('rooms', 'inspected_at')) {
                $updateData['inspected_at'] = now();
            }
            
            if (Schema::hasColumn('rooms', 'inspected_by')) {
                $updateData['inspected_by'] = $user->id;
            }
            
            $room->update($updateData);
            
            $this->notifyReception(
                $room,
                'inspection_completed',
                "✅ Inspection terminée pour la chambre {$room->number}"
            );
            
            return back()->with('success', 'Inspection terminée pour la chambre ' . $room->number);
            
        } catch (\Exception $e) {
            Log::error('Erreur fin inspection: ' . $e->getMessage(), [
                'room_id' => $room->id,
                'user_id' => Auth::id(),
            ]);
            
            return back()->with('error', 'Erreur lors de la fin d\'inspection: ' . $e->getMessage());
        }
    }
    
    /**
     * Mettre fin à la maintenance
     */
    public function endMaintenance(Room $room)
    {
        DB::beginTransaction();
        
        try {
            $oldStatus = $room->room_status_id;
            $user = Auth::user();
            
            // Déterminer le nouveau statut
            $isOccupied = $this->isRoomOccupied($room->id);
            $newStatus = $isOccupied ? self::STATUS_OCCUPIED : self::STATUS_AVAILABLE;
            
            $updateData = [
                'room_status_id' => $newStatus,
                'updated_at' => now(),
            ];
            
            if (Schema::hasColumn('rooms', 'maintenance_ended_at')) {
                $updateData['maintenance_ended_at'] = now();
            }
            
            if (Schema::hasColumn('rooms', 'maintenance_resolved_by')) {
                $updateData['maintenance_resolved_by'] = $user->id;
            }
            
            $room->update($updateData);
            
            // Notification IMPORTANTE à la réception
            $statusText = $newStatus == self::STATUS_AVAILABLE ? 'DISPONIBLE' : 'OCCUPÉE';
            $this->notifyReception(
                $room,
                'maintenance_ended',
                "✅ MAINTENANCE TERMINÉE: Chambre {$room->number} - Statut: {$statusText}",
                true
            );
            
            DB::commit();
            
            return back()->with('success', 'Maintenance terminée pour la chambre ' . $room->number);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erreur fin maintenance: ' . $e->getMessage(), [
                'room_id' => $room->id,
                'user_id' => Auth::id(),
            ]);
            
            return back()->with('error', 'Erreur lors de la fin de maintenance: ' . $e->getMessage());
        }
    }
    
    /**
     * Rapports de nettoyage
     */
    public function reports(Request $request)
    {
        try {
            $date = $request->get('date', Carbon::today()->format('Y-m-d'));
            $selectedDate = Carbon::parse($date);
            
            $query = Room::with(['type', 'roomStatus']);
            
            if (Schema::hasColumn('rooms', 'last_cleaned_at')) {
                $query->whereDate('last_cleaned_at', $selectedDate);
            } else {
                $query->whereDate('updated_at', $selectedDate)
                    ->whereIn('room_status_id', [self::STATUS_AVAILABLE, self::STATUS_OCCUPIED]);
            }
            
            $cleanedRooms = $query->orderBy('last_cleaned_at', 'desc')->get();
            
            $stats = [
                'total_cleaned' => $cleanedRooms->count(),
                'cleaned_by_status' => $cleanedRooms->groupBy('room_status_id')->map->count(),
            ];
            
            $cleanedByUser = collect();
            if (Schema::hasColumn('rooms', 'cleaned_by')) {
                $cleanedByUser = $cleanedRooms->groupBy('cleaned_by')->map(function($rooms, $userId) {
                    $user = User::find($userId);
                    return [
                        'name' => $user ? $user->name : 'Inconnu',
                        'count' => $rooms->count()
                    ];
                })->values();
            }
            
            $availableDates = collect();
            if (Schema::hasColumn('rooms', 'last_cleaned_at')) {
                $availableDates = Room::select(DB::raw('DATE(last_cleaned_at) as date'))
                    ->whereNotNull('last_cleaned_at')
                    ->groupBy(DB::raw('DATE(last_cleaned_at)'))
                    ->orderBy('date', 'desc')
                    ->limit(30)
                    ->get()
                    ->pluck('date');
            }
            
            return view('housekeeping.reports', compact(
                'cleanedRooms',
                'stats',
                'cleanedByUser',
                'selectedDate',
                'availableDates'
            ));
            
        } catch (\Exception $e) {
            Log::error('Housekeeping reports error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du chargement des rapports');
        }
    }
    
    /**
     * Rapport quotidien
     */
    public function dailyReport()
    {
        try {
            $today = Carbon::today();
            
            // Chambres nettoyées aujourd'hui
            $queryCleaned = Room::with(['type', 'roomStatus']);
            if (Schema::hasColumn('rooms', 'last_cleaned_at')) {
                $queryCleaned->whereDate('last_cleaned_at', $today);
            } else {
                $queryCleaned->whereDate('updated_at', $today)
                    ->whereIn('room_status_id', [self::STATUS_AVAILABLE, self::STATUS_OCCUPIED]);
            }
            $cleanedToday = $queryCleaned->orderBy('last_cleaned_at', 'desc')->get();
            
            // Chambres à nettoyer
            $toClean = Room::with(['type', 'roomStatus'])
                ->where('room_status_id', self::STATUS_DIRTY)
                ->orWhere('room_status_id', self::STATUS_CLEANING)
                ->orWhere(function($query) use ($today) {
                    $query->whereHas('transactions', function($q) use ($today) {
                        $q->whereIn('status', ['active', 'checked_in'])
                          ->whereDate('check_out', $today);
                    });
                })
                ->orderByRaw("
                    CASE 
                        WHEN room_status_id = " . self::STATUS_DIRTY . " THEN 1
                        WHEN room_status_id = " . self::STATUS_CLEANING . " THEN 2
                        ELSE 3
                    END
                ")
                ->orderBy('number')
                ->get();
            
            $stats = [
                'cleaned_today' => $cleanedToday->count(),
                'to_clean' => $toClean->count(),
                'dirty' => $toClean->where('room_status_id', self::STATUS_DIRTY)->count(),
                'cleaning' => $toClean->where('room_status_id', self::STATUS_CLEANING)->count(),
            ];
            
            if (Schema::hasColumn('rooms', 'cleaned_by')) {
                $stats['cleaned_by_user'] = $cleanedToday->groupBy('cleaned_by')->map->count();
            }
            
            return view('housekeeping.daily-report', compact(
                'cleanedToday',
                'toClean',
                'stats',
                'today'
            ));
            
        } catch (\Exception $e) {
            Log::error('Housekeeping dailyReport error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du chargement du rapport quotidien');
        }
    }
    
    /**
     * Planning de nettoyage
     */
    public function schedule()
    {
        try {
            // Départs des 7 prochains jours
            $nextWeekDepartures = Transaction::with(['room', 'customer'])
                ->whereIn('status', ['active', 'checked_in'])
                ->whereBetween('check_out', [Carbon::today(), Carbon::today()->addDays(7)])
                ->orderBy('check_out')
                ->get()
                ->groupBy(function($transaction) {
                    return $transaction->check_out->format('Y-m-d');
                });
            
            // Arrivées des 7 prochains jours
            $nextWeekArrivals = Transaction::with(['room', 'customer'])
                ->whereIn('status', ['reservation', 'confirmed'])
                ->whereBetween('check_in', [Carbon::today(), Carbon::today()->addDays(7)])
                ->orderBy('check_in')
                ->get()
                ->groupBy(function($transaction) {
                    return $transaction->check_in->format('Y-m-d');
                });
            
            // Chambres actuellement en maintenance
            $maintenanceRooms = Room::with(['type', 'roomStatus'])
                ->where('room_status_id', self::STATUS_MAINTENANCE)
                ->get();
            
            return view('housekeeping.schedule', compact(
                'nextWeekDepartures',
                'nextWeekArrivals',
                'maintenanceRooms'
            ));
            
        } catch (\Exception $e) {
            Log::error('Housekeeping schedule error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du chargement du planning');
        }
    }
    
    /**
     * API pour scanner QR code
     */
    public function scanApi(Request $request)
    {
        try {
            $request->validate([
                'room_number' => 'required',
                'action' => 'required|in:start,finish',
            ]);
            
            $room = Room::where('number', $request->room_number)->first();
            
            if (!$room) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chambre non trouvée'
                ], 404);
            }
            
            if ($request->action == 'start') {
                $this->startCleaning($room);
                $message = "Nettoyage démarré pour la chambre {$room->number}";
            } else {
                $this->finishCleaning($room);
                $message = "Chambre {$room->number} marquée comme nettoyée";
            }
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'room' => $room->load(['type', 'roomStatus'])
            ]);
            
        } catch (\Exception $e) {
            Log::error('Scan API error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }
}