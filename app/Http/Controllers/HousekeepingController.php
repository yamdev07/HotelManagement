<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomStatus;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HousekeepingController extends Controller
{
    /**
     * Dashboard femmes de chambre
     */
    public function index()
    {
        // Récupérer toutes les chambres avec leurs statuts
        $rooms = Room::with(['type', 'roomStatus', 'activeTransactions.customer'])
            ->orderBy('number')
            ->get();
        
        // Grouper par statut de nettoyage
        $roomsByStatus = [
            'dirty' => $rooms->where('room_status_id', Room::STATUS_CLEANING)->values(),
            'cleaning' => $rooms->where('room_status_id', Room::STATUS_CLEANING)->values(),
            'clean' => $rooms->where('room_status_id', Room::STATUS_AVAILABLE)
                ->filter(function($room) {
                    return !$room->activeTransactions->count();
                })->values(),
            'occupied' => $rooms->where('room_status_id', Room::STATUS_OCCUPIED)
                ->merge($rooms->filter(function($room) {
                    return $room->activeTransactions->count() > 0;
                }))->unique('id')->values(),
            'maintenance' => $rooms->where('room_status_id', Room::STATUS_MAINTENANCE)->values(),
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
        $roomsCleanedToday = Room::whereHas('roomStatus', function($query) {
            $query->whereIn('id', [Room::STATUS_AVAILABLE, Room::STATUS_OCCUPIED]);
        })->whereDate('updated_at', Carbon::today())
          ->count();
        
        return view('housekeeping.index', compact(
            'roomsByStatus',
            'stats',
            'todayDepartures',
            'todayArrivals',
            'roomsCleanedToday'
        ));
    }
    
    /**
     * Interface mobile/simplifiée pour femmes de chambre
     */
    public function mobile()
    {
        // Chambres à nettoyer (prioritaires)
        $dirtyRooms = Room::with(['type', 'roomStatus'])
            ->where('room_status_id', Room::STATUS_CLEANING)
            ->orderBy('updated_at', 'asc') // Les plus anciennes d'abord
            ->get();
        
        // Chambres en cours de nettoyage
        $cleaningRooms = Room::with(['type', 'roomStatus'])
            ->where('room_status_id', Room::STATUS_CLEANING)
            ->orderBy('updated_at', 'asc')
            ->get();
        
        // Statistiques simplifiées
        $stats = [
            'dirty' => $dirtyRooms->count(),
            'cleaning' => $cleaningRooms->count(),
            'cleaned_today' => Room::where('room_status_id', Room::STATUS_AVAILABLE)
                ->whereDate('updated_at', Carbon::today())
                ->count(),
        ];
        
        return view('housekeeping.mobile', compact(
            'dirtyRooms',
            'cleaningRooms',
            'stats'
        ));
    }
    
    /**
     * Liste rapide des chambres par statut
     */
    public function quickList($status)
    {
        $statusMap = [
            'dirty' => Room::STATUS_CLEANING,
            'cleaning' => Room::STATUS_CLEANING,
            'clean' => Room::STATUS_AVAILABLE,
            'occupied' => Room::STATUS_OCCUPIED,
            'maintenance' => Room::STATUS_MAINTENANCE,
        ];
        
        if (!isset($statusMap[$status])) {
            return redirect()->route('housekeeping.index')
                ->with('error', 'Statut invalide');
        }
        
        $rooms = Room::with(['type', 'roomStatus', 'activeTransactions.customer'])
            ->where('room_status_id', $statusMap[$status])
            ->orderBy('number')
            ->get();
        
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
        $request->validate([
            'room_number' => 'required|string|max:10',
            'action' => 'required|in:start-cleaning,mark-cleaned,mark-inspection,mark-maintenance'
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
            case 'mark-inspection':
                return $this->markInspection($room);
            case 'mark-maintenance':
                return $this->markMaintenance($room);
        }
        
        return back()->with('error', 'Action non reconnue');
    }
    
    /**
     * Chambres à nettoyer
     */
    public function toClean()
    {
        $rooms = Room::with(['type', 'roomStatus', 'activeTransactions.customer'])
            ->where('room_status_id', Room::STATUS_CLEANING)
            ->orWhere(function($query) {
                // Chambres avec départ aujourd'hui
                $query->whereHas('activeTransactions', function($q) {
                    $q->whereDate('check_out', Carbon::today());
                });
            })
            ->orderByRaw("
                CASE 
                    WHEN room_status_id = " . Room::STATUS_CLEANING . " THEN 1
                    ELSE 2
                END
            ")
            ->orderBy('number')
            ->get();
        
        // Statistiques
        $stats = [
            'total_to_clean' => $rooms->count(),
            'dirty' => $rooms->where('room_status_id', Room::STATUS_CLEANING)->count(),
            'departing_today' => $rooms->filter(function($room) {
                return $room->activeTransactions->where('check_out', '>=', Carbon::today())->count() > 0;
            })->count(),
        ];
        
        return view('housekeeping.to-clean', compact('rooms', 'stats'));
    }
    
    /**
     * Marquer comme en nettoyage
     */
    public function startCleaning(Room $room)
    {
        DB::beginTransaction();
        
        try {
            $oldStatus = $room->room_status_id;
            
            $room->update([
                'room_status_id' => Room::STATUS_CLEANING,
                'cleaning_started_at' => now(),
                'cleaned_by' => auth()->id(),
            ]);
            
            // Journalisation
            activity()
                ->performedOn($room)
                ->causedBy(auth()->user())
                ->withProperties([
                    'old_status' => $oldStatus,
                    'new_status' => Room::STATUS_CLEANING,
                    'room_number' => $room->number
                ])
                ->log('Nettoyage démarré');
            
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
            $newStatus = $room->activeTransactions->count() > 0 
                ? Room::STATUS_OCCUPIED 
                : Room::STATUS_AVAILABLE;
            
            $room->update([
                'room_status_id' => $newStatus,
                'cleaning_completed_at' => now(),
                'cleaned_by' => auth()->id(),
                'last_cleaned_at' => now(),
            ]);
            
            // Journalisation
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
            
            DB::commit();
            
            $message = 'Chambre ' . $room->number . ' marquée comme nettoyée. ';
            $message .= $newStatus == Room::STATUS_AVAILABLE ? 'Statut: Disponible' : 'Statut: Occupée';
            
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
            $room->update([
                'needs_inspection' => true,
                'inspection_requested_at' => now(),
                'inspection_requested_by' => auth()->id(),
            ]);
            
            // Journalisation
            activity()
                ->performedOn($room)
                ->causedBy(auth()->user())
                ->withProperties(['room_number' => $room->number])
                ->log('Inspection demandée');
            
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
     * Marquer comme en maintenance
     */
    public function markMaintenance(Request $request, Room $room)
    {
        $request->validate([
            'maintenance_reason' => 'required|string|max:500',
            'estimated_duration' => 'nullable|integer|min:1',
        ]);
        
        DB::beginTransaction();
        
        try {
            $oldStatus = $room->room_status_id;
            
            $room->update([
                'room_status_id' => Room::STATUS_MAINTENANCE,
                'maintenance_reason' => $request->maintenance_reason,
                'maintenance_started_at' => now(),
                'maintenance_requested_by' => auth()->id(),
                'estimated_maintenance_duration' => $request->estimated_duration,
            ]);
            
            // Journalisation
            activity()
                ->performedOn($room)
                ->causedBy(auth()->user())
                ->withProperties([
                    'old_status' => $oldStatus,
                    'new_status' => Room::STATUS_MAINTENANCE,
                    'room_number' => $room->number,
                    'reason' => $request->maintenance_reason
                ])
                ->log('Maintenance demandée');
            
            DB::commit();
            
            return back()->with('success', 'Chambre ' . $room->number . ' marquée comme en maintenance');
            
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
     * Rapports de nettoyage
     */
    public function reports(Request $request)
    {
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $selectedDate = Carbon::parse($date);
        
        // Chambres nettoyées à cette date
        $cleanedRooms = Room::whereDate('last_cleaned_at', $selectedDate)
            ->with(['type', 'roomStatus'])
            ->orderBy('last_cleaned_at', 'desc')
            ->get();
        
        // Statistiques quotidiennes
        $stats = [
            'total_cleaned' => $cleanedRooms->count(),
            'cleaned_by_status' => $cleanedRooms->groupBy('room_status_id')->map->count(),
            'average_cleaning_time' => $this->calculateAverageCleaningTime($selectedDate),
        ];
        
        // Chambres par femme de chambre
        $cleanedByUser = DB::table('rooms')
            ->select('cleaned_by', DB::raw('count(*) as count'))
            ->whereDate('last_cleaned_at', $selectedDate)
            ->whereNotNull('cleaned_by')
            ->groupBy('cleaned_by')
            ->get()
            ->mapWithKeys(function($item) {
                $user = User::find($item->cleaned_by);
                return [$user ? $user->name : 'Inconnu' => $item->count];
            });
        
        // Dates disponibles pour le filtre
        $availableDates = Room::select(DB::raw('DATE(last_cleaned_at) as date'))
            ->whereNotNull('last_cleaned_at')
            ->groupBy(DB::raw('DATE(last_cleaned_at)'))
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get()
            ->pluck('date');
        
        return view('housekeeping.reports', compact(
            'cleanedRooms',
            'stats',
            'cleanedByUser',
            'selectedDate',
            'availableDates'
        ));
    }
    
    /**
     * Rapport quotidien
     */
    public function dailyReport()
    {
        $today = Carbon::today();
        
        // Chambres nettoyées aujourd'hui
        $cleanedToday = Room::whereDate('last_cleaned_at', $today)
            ->with(['type', 'roomStatus'])
            ->orderBy('last_cleaned_at', 'desc')
            ->get();
        
        // Chambres à nettoyer
        $toClean = Room::where('room_status_id', Room::STATUS_CLEANING)
            ->orWhere(function($query) use ($today) {
                $query->whereHas('activeTransactions', function($q) use ($today) {
                    $q->whereDate('check_out', $today);
                });
            })
            ->with(['type', 'roomStatus', 'activeTransactions.customer'])
            ->orderByRaw("
                CASE 
                    WHEN room_status_id = " . Room::STATUS_CLEANING . " THEN 1
                    ELSE 2
                END
            ")
            ->orderBy('number')
            ->get();
        
        // Statistiques
        $stats = [
            'cleaned_today' => $cleanedToday->count(),
            'to_clean' => $toClean->count(),
            'cleaned_by_user' => $cleanedToday->groupBy('cleaned_by')->map->count(),
        ];
        
        return view('housekeeping.daily-report', compact(
            'cleanedToday',
            'toClean',
            'stats',
            'today'
        ));
    }
    
    /**
     * Calculer le temps moyen de nettoyage
     */
    private function calculateAverageCleaningTime(Carbon $date)
    {
        $result = DB::table('rooms')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(MINUTE, cleaning_started_at, cleaning_completed_at)) as avg_time'))
            ->whereNotNull('cleaning_started_at')
            ->whereNotNull('cleaning_completed_at')
            ->whereDate('last_cleaned_at', $date)
            ->first();
        
        return $result ? round($result->avg_time) : 0;
    }
    
    /**
     * Afficher le formulaire de maintenance
     */
    public function showMaintenanceForm(Room $room)
    {
        return view('housekeeping.maintenance-form', compact('room'));
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
            $newStatus = $room->activeTransactions->count() > 0 
                ? Room::STATUS_OCCUPIED 
                : Room::STATUS_AVAILABLE;
            
            $room->update([
                'room_status_id' => $newStatus,
                'maintenance_ended_at' => now(),
                'maintenance_resolved_by' => auth()->id(),
            ]);
            
            // Journalisation
            activity()
                ->performedOn($room)
                ->causedBy(auth()->user())
                ->withProperties([
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'room_number' => $room->number
                ])
                ->log('Maintenance terminée');
            
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
     * Chambres en maintenance
     */
    public function maintenance()
    {
        $maintenanceRooms = Room::with(['type', 'roomStatus'])
            ->where('room_status_id', Room::STATUS_MAINTENANCE)
            ->orderBy('maintenance_started_at', 'asc')
            ->get();
        
        $stats = [
            'total_maintenance' => $maintenanceRooms->count(),
            'longest_maintenance' => $maintenanceRooms->max('maintenance_started_at'),
            'maintenance_by_reason' => $maintenanceRooms->groupBy('maintenance_reason')->map->count(),
        ];
        
        return view('housekeeping.maintenance', compact('maintenanceRooms', 'stats'));
    }
    
    /**
     * Chambres à inspecter
     */
    public function inspections()
    {
        $inspectionRooms = Room::with(['type', 'roomStatus'])
            ->where('needs_inspection', true)
            ->orderBy('inspection_requested_at', 'asc')
            ->get();
        
        return view('housekeeping.inspections', compact('inspectionRooms'));
    }
    
    /**
     * Marquer l'inspection comme terminée
     */
    public function completeInspection(Room $room)
    {
        try {
            $room->update([
                'needs_inspection' => false,
                'inspected_at' => now(),
                'inspected_by' => auth()->id(),
            ]);
            
            // Journalisation
            activity()
                ->performedOn($room)
                ->causedBy(auth()->user())
                ->withProperties(['room_number' => $room->number])
                ->log('Inspection terminée');
            
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
     * Statistiques mensuelles
     */
    public function monthlyStats(Request $request)
    {
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $selectedMonth = Carbon::parse($month . '-01');
        
        $startDate = $selectedMonth->copy()->startOfMonth();
        $endDate = $selectedMonth->copy()->endOfMonth();
        
        // Statistiques mensuelles
        $monthlyStats = DB::table('rooms')
            ->select(
                DB::raw('DATE(last_cleaned_at) as date'),
                DB::raw('COUNT(*) as cleaned_count'),
                DB::raw('AVG(TIMESTAMPDIFF(MINUTE, cleaning_started_at, cleaning_completed_at)) as avg_time')
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
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->select(
                'rooms.number',
                'room_types.name as type',
                DB::raw('COUNT(*) as cleaned_count')
            )
            ->whereBetween('last_cleaned_at', [$startDate, $endDate])
            ->whereNotNull('last_cleaned_at')
            ->groupBy('rooms.id', 'rooms.number', 'room_types.name')
            ->orderByDesc('cleaned_count')
            ->limit(10)
            ->get();
        
        // Mois disponibles pour le filtre
        $availableMonths = Room::select(DB::raw('DATE_FORMAT(last_cleaned_at, "%Y-%m") as month'))
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
    }
}