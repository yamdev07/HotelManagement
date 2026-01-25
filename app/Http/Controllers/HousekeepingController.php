<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomStatus;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HousekeepingController extends Controller
{
    // Constantes pour les statuts (en attendant de les mettre dans le modèle Room)
    const STATUS_AVAILABLE = 1;      // Disponible
    const STATUS_MAINTENANCE = 2;    // En maintenance
    const STATUS_CLEANING = 3;       // À nettoyer/En nettoyage
    const STATUS_OCCUPIED = 4;       // Occupée
    
    /**
     * Dashboard femmes de chambre
     */
    public function index()
    {
        try {
            // Récupérer toutes les chambres avec leurs statuts
            $rooms = Room::with(['type', 'roomStatus'])
                ->orderBy('number')
                ->get();
            
            // Pour chaque chambre, vérifier si elle est occupée
            foreach ($rooms as $room) {
                $room->is_occupied = Transaction::where('room_id', $room->id)
                    ->whereIn('status', ['active', 'reservation'])
                    ->where('check_in', '<=', now())
                    ->where('check_out', '>=', now())
                    ->exists();
            }
            
            // Grouper par statut de nettoyage
            $roomsByStatus = [
                'dirty' => $rooms->where('room_status_id', self::STATUS_CLEANING)->values(),
                'cleaning' => $rooms->where('room_status_id', self::STATUS_CLEANING)->values(),
                'clean' => $rooms->where('room_status_id', self::STATUS_AVAILABLE)
                    ->filter(function($room) {
                        return !$room->is_occupied;
                    })->values(),
                'occupied' => $rooms->filter(function($room) {
                        return $room->is_occupied || $room->room_status_id == self::STATUS_OCCUPIED;
                    })->unique('id')->values(),
                'maintenance' => $rooms->where('room_status_id', self::STATUS_MAINTENANCE)->values(),
            ];
            
            // Statistiques
            $stats = [
                'total_rooms' => $rooms->count(),
                'dirty_rooms' => $roomsByStatus['dirty']->count(),
                'cleaning_rooms' => $roomsByStatus['cleaning']->count(),
                'clean_rooms' => $roomsByStatus['clean']->count(),
                'occupied_rooms' => $roomsByStatus['occupied']->count(),
                'maintenance_rooms' => $roomsByStatus['maintenance']->count(),
            ];
            
            // Départs du jour (chambres à nettoyer)
            $todayDepartures = Transaction::with(['room', 'customer'])
                ->where('status', 'active')
                ->whereDate('check_out', Carbon::today())
                ->orderBy('check_out')
                ->get();
            
            // Arrivées du jour (chambres préparées)
            $todayArrivals = Transaction::with(['room', 'customer'])
                ->where('status', 'reservation')
                ->whereDate('check_in', Carbon::today())
                ->orderBy('check_in')
                ->get();
            
            // Chambres changées aujourd'hui
            $roomsCleanedToday = Room::whereIn('room_status_id', [self::STATUS_AVAILABLE, self::STATUS_OCCUPIED])
                ->whereDate('updated_at', Carbon::today())
                ->count();
            
            return view('housekeeping.index', compact(
                'roomsByStatus',
                'stats',
                'todayDepartures',
                'todayArrivals',
                'roomsCleanedToday'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Housekeeping index error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du chargement du dashboard');
        }
    }
    
    /**
     * Interface mobile/simplifiée pour femmes de chambre
     */
    public function mobile()
    {
        try {
            // Chambres à nettoyer (prioritaires)
            $dirtyRooms = Room::with(['type', 'roomStatus'])
                ->where('room_status_id', self::STATUS_CLEANING)
                ->orderBy('updated_at', 'asc')
                ->get();
            
            // Chambres en cours de nettoyage
            $cleaningRooms = Room::with(['type', 'roomStatus'])
                ->where('room_status_id', self::STATUS_CLEANING)
                ->orderBy('updated_at', 'asc')
                ->get();
            
            // Statistiques simplifiées
            $stats = [
                'dirty' => $dirtyRooms->count(),
                'cleaning' => $cleaningRooms->count(),
                'cleaned_today' => Room::where('room_status_id', self::STATUS_AVAILABLE)
                    ->whereDate('updated_at', Carbon::today())
                    ->count(),
            ];
            
            return view('housekeeping.mobile', compact(
                'dirtyRooms',
                'cleaningRooms',
                'stats'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Housekeeping mobile error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du chargement de l\'interface mobile');
        }
    }
    
    /**
     * Liste rapide des chambres par statut
     */
    public function quickList($status)
    {
        try {
            $statusMap = [
                'dirty' => self::STATUS_CLEANING,
                'cleaning' => self::STATUS_CLEANING,
                'clean' => self::STATUS_AVAILABLE,
                'occupied' => self::STATUS_OCCUPIED,
                'maintenance' => self::STATUS_MAINTENANCE,
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
                    return !Transaction::where('room_id', $room->id)
                        ->whereIn('status', ['active', 'reservation'])
                        ->where('check_in', '<=', now())
                        ->where('check_out', '>=', now())
                        ->exists();
                })->values();
            }
            
            $statusLabels = [
                'dirty' => 'À nettoyer',
                'cleaning' => 'En nettoyage',
                'clean' => 'Nettoyées',
                'occupied' => 'Occupées',
                'maintenance' => 'Maintenance',
            ];
            
            $statusLabel = $statusLabels[$status] ?? ucfirst($status);
            
            return view('housekeeping.quick-list', compact(
                'rooms',
                'status',
                'statusLabel'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Housekeeping quickList error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du chargement de la liste');
        }
    }
    
    /**
     * Scanner QR code (pour mobile)
     */
    public function scan()
    {
        return view('housekeeping.scan');
    }
    
    /**
     * Traiter le scan QR code
     */
    public function processScan(Request $request)
    {
        try {
            $request->validate([
                'room_number' => 'required|string|max:10',
                'action' => 'required|in:start-cleaning,mark-cleaned,mark-maintenance'
            ]);
            
            // Trouver la chambre par numéro
            $room = Room::where('number', $request->room_number)->first();
            
            if (!$room) {
                return back()->with('error', 'Chambre ' . $request->room_number . ' non trouvée');
            }
            
            // Exécuter l'action
            switch ($request->action) {
                case 'start-cleaning':
                    return $this->startCleaning($room);
                case 'mark-cleaned':
                    return $this->markCleaned($room);
                case 'mark-maintenance':
                    return redirect()->route('housekeeping.maintenance-form', $room);
            }
            
            return back()->with('error', 'Action non reconnue');
            
        } catch (\Exception $e) {
            \Log::error('Housekeeping processScan error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du traitement du scan');
        }
    }
    
    /**
     * Chambres à nettoyer
     */
    public function toClean()
    {
        try {
            $rooms = Room::with(['type', 'roomStatus'])
                ->where('room_status_id', self::STATUS_CLEANING)
                ->orWhere(function($query) {
                    // Chambres avec départ aujourd'hui
                    $query->whereHas('transactions', function($q) {
                        $q->whereIn('status', ['active'])
                          ->whereDate('check_out', Carbon::today());
                    });
                })
                ->orderByRaw("
                    CASE 
                        WHEN room_status_id = " . self::STATUS_CLEANING . " THEN 1
                        ELSE 2
                    END
                ")
                ->orderBy('number')
                ->get();
            
            // Statistiques
            $stats = [
                'total_to_clean' => $rooms->count(),
                'dirty' => $rooms->where('room_status_id', self::STATUS_CLEANING)->count(),
                'departing_today' => $rooms->filter(function($room) {
                    return $room->transactions()
                        ->whereIn('status', ['active'])
                        ->whereDate('check_out', Carbon::today())
                        ->exists();
                })->count(),
            ];
            
            return view('housekeeping.to-clean', compact('rooms', 'stats'));
            
        } catch (\Exception $e) {
            \Log::error('Housekeeping toClean error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du chargement des chambres à nettoyer');
        }
    }
    
    /**
     * Marquer comme en nettoyage
     */
    public function startCleaning(Room $room)
    {
        DB::beginTransaction();
        
        try {
            $oldStatus = $room->room_status_id;
            
            $updateData = [
                'room_status_id' => self::STATUS_CLEANING,
                'updated_at' => now(),
            ];
            
            // Ajouter les colonnes si elles existent
            if (Schema::hasColumn('rooms', 'cleaning_started_at')) {
                $updateData['cleaning_started_at'] = now();
            }
            
            if (Schema::hasColumn('rooms', 'cleaned_by')) {
                $updateData['cleaned_by'] = auth()->id();
            }
            
            $room->update($updateData);
            
            // Journalisation
            if (class_exists('\Spatie\Activitylog\Models\Activity')) {
                activity()
                    ->performedOn($room)
                    ->causedBy(auth()->user())
                    ->withProperties([
                        'old_status' => $oldStatus,
                        'new_status' => self::STATUS_CLEANING,
                        'room_number' => $room->number
                    ])
                    ->log('Nettoyage démarré');
            }
            
            DB::commit();
            
            return back()->with('success', 'Nettoyage démarré pour la chambre ' . $room->number);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Erreur démarrage nettoyage: ' . $e->getMessage(), [
                'room_id' => $room->id,
                'user_id' => auth()->id(),
            ]);
            
            return back()->with('error', 'Erreur lors du démarrage du nettoyage: ' . $e->getMessage());
        }
    }
    
    /**
     * Marquer comme nettoyée
     */
    public function markCleaned(Room $room)
    {
        DB::beginTransaction();
        
        try {
            $oldStatus = $room->room_status_id;
            
            // Déterminer le nouveau statut
            $isOccupied = Transaction::where('room_id', $room->id)
                ->whereIn('status', ['active', 'reservation'])
                ->where('check_in', '<=', now())
                ->where('check_out', '>=', now())
                ->exists();
            
            $newStatus = $isOccupied ? self::STATUS_OCCUPIED : self::STATUS_AVAILABLE;
            
            $updateData = [
                'room_status_id' => $newStatus,
                'updated_at' => now(),
            ];
            
            // Ajouter les colonnes si elles existent
            if (Schema::hasColumn('rooms', 'cleaning_completed_at')) {
                $updateData['cleaning_completed_at'] = now();
            }
            
            if (Schema::hasColumn('rooms', 'cleaned_by')) {
                $updateData['cleaned_by'] = auth()->id();
            }
            
            if (Schema::hasColumn('rooms', 'last_cleaned_at')) {
                $updateData['last_cleaned_at'] = now();
            }
            
            $room->update($updateData);
            
            // Journalisation
            if (class_exists('\Spatie\Activitylog\Models\Activity')) {
                activity()
                    ->performedOn($room)
                    ->causedBy(auth()->user())
                    ->withProperties([
                        'old_status' => $oldStatus,
                        'new_status' => $newStatus,
                        'room_number' => $room->number,
                        'cleaned_by' => auth()->user()->name
                    ])
                    ->log('Chambre nettoyée');
            }
            
            DB::commit();
            
            $message = 'Chambre ' . $room->number . ' marquée comme nettoyée. ';
            $message .= $newStatus == self::STATUS_AVAILABLE ? 'Statut: Disponible' : 'Statut: Occupée';
            
            return back()->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Erreur marquage nettoyée: ' . $e->getMessage(), [
                'room_id' => $room->id,
                'user_id' => auth()->id(),
            ]);
            
            return back()->with('error', 'Erreur lors du marquage comme nettoyée: ' . $e->getMessage());
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
            
            // Ajouter les colonnes si elles existent
            if (Schema::hasColumn('rooms', 'needs_inspection')) {
                $updateData['needs_inspection'] = true;
            }
            
            if (Schema::hasColumn('rooms', 'inspection_requested_at')) {
                $updateData['inspection_requested_at'] = now();
            }
            
            if (Schema::hasColumn('rooms', 'inspection_requested_by')) {
                $updateData['inspection_requested_by'] = auth()->id();
            }
            
            $room->update($updateData);
            
            // Journalisation
            if (class_exists('\Spatie\Activitylog\Models\Activity')) {
                activity()
                    ->performedOn($room)
                    ->causedBy(auth()->user())
                    ->withProperties(['room_number' => $room->number])
                    ->log('Inspection demandée');
            }
            
            return back()->with('success', 'Inspection demandée pour la chambre ' . $room->number);
            
        } catch (\Exception $e) {
            \Log::error('Erreur demande inspection: ' . $e->getMessage(), [
                'room_id' => $room->id,
                'user_id' => auth()->id(),
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
     * Marquer comme en maintenance
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
            
            $updateData = [
                'room_status_id' => self::STATUS_MAINTENANCE,
                'updated_at' => now(),
            ];
            
            // Ajouter les colonnes si elles existent
            if (Schema::hasColumn('rooms', 'maintenance_reason')) {
                $updateData['maintenance_reason'] = $request->maintenance_reason;
            }
            
            if (Schema::hasColumn('rooms', 'maintenance_started_at')) {
                $updateData['maintenance_started_at'] = now();
            }
            
            if (Schema::hasColumn('rooms', 'maintenance_requested_by')) {
                $updateData['maintenance_requested_by'] = auth()->id();
            }
            
            if (Schema::hasColumn('rooms', 'estimated_maintenance_duration')) {
                $updateData['estimated_maintenance_duration'] = $request->estimated_duration;
            }
            
            $room->update($updateData);
            
            // Journalisation
            if (class_exists('\Spatie\Activitylog\Models\Activity')) {
                activity()
                    ->performedOn($room)
                    ->causedBy(auth()->user())
                    ->withProperties([
                        'old_status' => $oldStatus,
                        'new_status' => self::STATUS_MAINTENANCE,
                        'room_number' => $room->number,
                        'reason' => $request->maintenance_reason
                    ])
                    ->log('Maintenance demandée');
            }
            
            DB::commit();
            
            return redirect()->route('housekeeping.index')
                ->with('success', 'Chambre ' . $room->number . ' marquée comme en maintenance');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Erreur maintenance: ' . $e->getMessage(), [
                'room_id' => $room->id,
                'user_id' => auth()->id(),
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
            
            // Initialiser les statistiques
            $stats = [
                'total_maintenance' => $maintenanceRooms->count(),
            ];
            
            // Ajouter maintenance_by_reason si la colonne existe
            if (Schema::hasColumn('rooms', 'maintenance_reason') && $maintenanceRooms->isNotEmpty()) {
                $grouped = $maintenanceRooms->groupBy('maintenance_reason')->map->count();
                $stats['maintenance_by_reason'] = $grouped;
            }
            
            // Ajouter longest_maintenance si la colonne existe
            if (Schema::hasColumn('rooms', 'maintenance_started_at') && $maintenanceRooms->isNotEmpty()) {
                $longest = $maintenanceRooms->where('maintenance_started_at', '!=', null)
                    ->max('maintenance_started_at');
                if ($longest) {
                    $stats['longest_maintenance'] = $longest;
                }
            }
            
            return view('housekeeping.maintenance', compact('maintenanceRooms', 'stats'));
            
        } catch (\Exception $e) {
            \Log::error('Maintenance page error: ' . $e->getMessage());
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
            
            // Vérifier si la colonne existe
            if (Schema::hasColumn('rooms', 'needs_inspection')) {
                $query->where('needs_inspection', true);
            } else {
                // Si la colonne n'existe pas, retourner une liste vide
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
            \Log::error('Inspections page error: ' . $e->getMessage());
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
    
    /**
     * Marquer l'inspection comme terminée
     */
    public function completeInspection(Room $room)
    {
        try {
            $updateData = [
                'updated_at' => now(),
            ];
            
            // Ajouter les colonnes si elles existent
            if (Schema::hasColumn('rooms', 'needs_inspection')) {
                $updateData['needs_inspection'] = false;
            }
            
            if (Schema::hasColumn('rooms', 'inspected_at')) {
                $updateData['inspected_at'] = now();
            }
            
            if (Schema::hasColumn('rooms', 'inspected_by')) {
                $updateData['inspected_by'] = auth()->id();
            }
            
            $room->update($updateData);
            
            // Journalisation
            if (class_exists('\Spatie\Activitylog\Models\Activity')) {
                activity()
                    ->performedOn($room)
                    ->causedBy(auth()->user())
                    ->withProperties(['room_number' => $room->number])
                    ->log('Inspection terminée');
            }
            
            return back()->with('success', 'Inspection terminée pour la chambre ' . $room->number);
            
        } catch (\Exception $e) {
            \Log::error('Erreur fin inspection: ' . $e->getMessage(), [
                'room_id' => $room->id,
                'user_id' => auth()->id(),
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
            
            // Déterminer le nouveau statut
            $isOccupied = Transaction::where('room_id', $room->id)
                ->whereIn('status', ['active', 'reservation'])
                ->where('check_in', '<=', now())
                ->where('check_out', '>=', now())
                ->exists();
            
            $newStatus = $isOccupied ? self::STATUS_OCCUPIED : self::STATUS_AVAILABLE;
            
            $updateData = [
                'room_status_id' => $newStatus,
                'updated_at' => now(),
            ];
            
            // Ajouter les colonnes si elles existent
            if (Schema::hasColumn('rooms', 'maintenance_ended_at')) {
                $updateData['maintenance_ended_at'] = now();
            }
            
            if (Schema::hasColumn('rooms', 'maintenance_resolved_by')) {
                $updateData['maintenance_resolved_by'] = auth()->id();
            }
            
            $room->update($updateData);
            
            // Journalisation
            if (class_exists('\Spatie\Activitylog\Models\Activity')) {
                activity()
                    ->performedOn($room)
                    ->causedBy(auth()->user())
                    ->withProperties([
                        'old_status' => $oldStatus,
                        'new_status' => $newStatus,
                        'room_number' => $room->number
                    ])
                    ->log('Maintenance terminée');
            }
            
            DB::commit();
            
            return back()->with('success', 'Maintenance terminée pour la chambre ' . $room->number);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Erreur fin maintenance: ' . $e->getMessage(), [
                'room_id' => $room->id,
                'user_id' => auth()->id(),
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
            
            // Base query pour les chambres nettoyées
            $query = Room::with(['type', 'roomStatus']);
            
            // Vérifier si la colonne last_cleaned_at existe
            if (Schema::hasColumn('rooms', 'last_cleaned_at')) {
                $query->whereDate('last_cleaned_at', $selectedDate);
            } else {
                // Fallback sur updated_at si last_cleaned_at n'existe pas
                $query->whereDate('updated_at', $selectedDate)
                    ->whereIn('room_status_id', [self::STATUS_AVAILABLE, self::STATUS_OCCUPIED]);
            }
            
            $cleanedRooms = $query->orderBy('updated_at', 'desc')->get();
            
            // Statistiques quotidiennes
            $stats = [
                'total_cleaned' => $cleanedRooms->count(),
                'cleaned_by_status' => $cleanedRooms->groupBy('room_status_id')->map->count(),
            ];
            
            // Chambres par femme de chambre (si la colonne cleaned_by existe)
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
            
            // Dates disponibles pour le filtre
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
            \Log::error('Housekeeping reports error: ' . $e->getMessage());
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
            $cleanedToday = $queryCleaned->orderBy('updated_at', 'desc')->get();
            
            // Chambres à nettoyer
            $toClean = Room::with(['type', 'roomStatus'])
                ->where('room_status_id', self::STATUS_CLEANING)
                ->orWhere(function($query) use ($today) {
                    $query->whereHas('transactions', function($q) use ($today) {
                        $q->whereIn('status', ['active'])
                          ->whereDate('check_out', $today);
                    });
                })
                ->orderByRaw("
                    CASE 
                        WHEN room_status_id = " . self::STATUS_CLEANING . " THEN 1
                        ELSE 2
                    END
                ")
                ->orderBy('number')
                ->get();
            
            // Statistiques
            $stats = [
                'cleaned_today' => $cleanedToday->count(),
                'to_clean' => $toClean->count(),
            ];
            
            // Chambres par utilisateur (si cleaned_by existe)
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
            \Log::error('Housekeeping dailyReport error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du chargement du rapport quotidien');
        }
    }
    
    /**
     * Statistiques mensuelles
     */
    public function monthlyStats(Request $request)
    {
        try {
            $month = $request->get('month', Carbon::now()->format('Y-m'));
            $selectedMonth = Carbon::parse($month . '-01');
            
            $startDate = $selectedMonth->copy()->startOfMonth();
            $endDate = $selectedMonth->copy()->endOfMonth();
            
            // Vérifier si la colonne last_cleaned_at existe
            if (!Schema::hasColumn('rooms', 'last_cleaned_at')) {
                // Si la colonne n'existe pas, retourner des données vides
                return view('housekeeping.monthly-stats', [
                    'monthlyStats' => collect(),
                    'topCleaners' => collect(),
                    'mostCleanedRooms' => collect(),
                    'selectedMonth' => $selectedMonth,
                    'availableMonths' => collect(),
                ]);
            }
            
            // Statistiques mensuelles
            $monthlyStats = DB::table('rooms')
                ->select(
                    DB::raw('DATE(last_cleaned_at) as date'),
                    DB::raw('COUNT(*) as cleaned_count')
                )
                ->whereBetween('last_cleaned_at', [$startDate, $endDate])
                ->whereNotNull('last_cleaned_at')
                ->groupBy(DB::raw('DATE(last_cleaned_at)'))
                ->orderBy('date')
                ->get();
            
            // Top femmes de chambre du mois
            $topCleaners = DB::table('rooms')
                ->select('cleaned_by', DB::raw('COUNT(*) as cleaned_count'))
                ->whereBetween('last_cleaned_at', [$startDate, $endDate])
                ->whereNotNull('cleaned_by')
                ->groupBy('cleaned_by')
                ->orderByDesc('cleaned_count')
                ->limit(10)
                ->get()
                ->map(function($item) {
                    $user = User::find($item->cleaned_by);
                    return [
                        'name' => $user ? $user->name : 'Inconnu',
                        'count' => $item->cleaned_count
                    ];
                });
            
            // Chambres les plus nettoyées
            $mostCleanedRooms = DB::table('rooms')
                ->join('types', 'rooms.type_id', '=', 'types.id')
                ->select(
                    'rooms.number',
                    'types.name as type',
                    DB::raw('COUNT(*) as cleaned_count')
                )
                ->whereBetween('last_cleaned_at', [$startDate, $endDate])
                ->whereNotNull('last_cleaned_at')
                ->groupBy('rooms.id', 'rooms.number', 'types.name')
                ->orderByDesc('cleaned_count')
                ->limit(10)
                ->get();
            
            // Mois disponibles pour le filtre
            $availableMonths = DB::table('rooms')
                ->select(DB::raw('DATE_FORMAT(last_cleaned_at, "%Y-%m") as month'))
                ->whereNotNull('last_cleaned_at')
                ->groupBy(DB::raw('DATE_FORMAT(last_cleaned_at, "%Y-%m")'))
                ->orderBy('month', 'desc')
                ->limit(12)
                ->get()
                ->pluck('month');
            
            return view('housekeeping.monthly-stats', compact(
                'monthlyStats',
                'topCleaners',
                'mostCleanedRooms',
                'selectedMonth',
                'availableMonths'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Housekeeping monthlyStats error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du chargement des statistiques mensuelles');
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
                ->where('status', 'active')
                ->whereBetween('check_out', [Carbon::today(), Carbon::today()->addDays(7)])
                ->orderBy('check_out')
                ->get()
                ->groupBy(function($transaction) {
                    return $transaction->check_out->format('Y-m-d');
                });
            
            // Arrivées des 7 prochains jours
            $nextWeekArrivals = Transaction::with(['room', 'customer'])
                ->where('status', 'reservation')
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
            \Log::error('Housekeeping schedule error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du chargement du planning');
        }
    }
    
    /**
     * Statistiques pour femmes de chambre
     */
    public function stats()
    {
        try {
            $today = Carbon::today();
            $lastMonth = $today->copy()->subMonth();
            
            // Statistiques générales
            $generalStats = [
                'total_rooms' => Room::count(),
                'available_rooms' => Room::where('room_status_id', self::STATUS_AVAILABLE)->count(),
                'occupied_rooms' => Room::where('room_status_id', self::STATUS_OCCUPIED)->count(),
                'cleaning_rooms' => Room::where('room_status_id', self::STATUS_CLEANING)->count(),
                'maintenance_rooms' => Room::where('room_status_id', self::STATUS_MAINTENANCE)->count(),
            ];
            
            // Chambres nettoyées aujourd'hui
            $cleanedToday = 0;
            if (Schema::hasColumn('rooms', 'last_cleaned_at')) {
                $cleanedToday = Room::whereDate('last_cleaned_at', $today)->count();
            }
            
            // Chambres nettoyées ce mois
            $cleanedThisMonth = 0;
            if (Schema::hasColumn('rooms', 'last_cleaned_at')) {
                $cleanedThisMonth = Room::whereBetween('last_cleaned_at', [
                    $today->copy()->startOfMonth(),
                    $today->copy()->endOfMonth()
                ])->count();
            }
            
            // Performance par femme de chambre (si cleaned_by existe)
            $performanceByCleaner = collect();
            if (Schema::hasColumn('rooms', 'cleaned_by') && Schema::hasColumn('rooms', 'last_cleaned_at')) {
                $performanceByCleaner = DB::table('rooms')
                    ->select('cleaned_by', DB::raw('COUNT(*) as cleaned_count'))
                    ->whereBetween('last_cleaned_at', [$lastMonth, $today])
                    ->whereNotNull('cleaned_by')
                    ->groupBy('cleaned_by')
                    ->orderByDesc('cleaned_count')
                    ->get()
                    ->map(function($item) {
                        $user = User::find($item->cleaned_by);
                        return [
                            'name' => $user ? $user->name : 'Inconnu',
                            'count' => $item->cleaned_count
                        ];
                    });
            }
            
            return view('housekeeping.stats', compact(
                'generalStats',
                'cleanedToday',
                'cleanedThisMonth',
                'performanceByCleaner'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Housekeeping stats error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du chargement des statistiques');
        }
    }
}