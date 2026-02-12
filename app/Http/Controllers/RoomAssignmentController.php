<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Room;
use App\Models\Type;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RoomAssignmentController extends Controller
{
    /**
     * Afficher les réservations nécessitant une attribution
     */
    public function index(Request $request)
    {
        // Types de chambre pour le filtre
        $roomTypes = Type::all();
        
        $query = Transaction::with(['customer', 'roomType', 'room'])
            ->where('is_assigned', false)
            ->whereIn('status', ['reservation', 'active'])
            ->whereDate('check_in', '<=', now()->addDays(2)) // Arrivée aujourd'hui ou demain
            ->orderBy('check_in');
        
        // Filtres
        if ($request->filled('arrival_date')) {
            $query->whereDate('check_in', $request->arrival_date);
        }
        
        if ($request->filled('room_type_id')) {
            $query->where('room_type_id', $request->room_type_id);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $reservations = $query->paginate(20);
        
        return view('room-assignment.index', compact('reservations', 'roomTypes'));
    }
    
    /**
     * Vue d'ensemble des arrivées/départs
     */
    public function overview(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        
        // Arrivées du jour
        $arrivals = Transaction::whereDate('check_in', $date)
            ->whereIn('status', ['reservation', 'active'])
            ->with(['customer', 'roomType', 'room', 'assignedBy'])
            ->orderBy('check_in')
            ->get();
        
        // Départs du jour
        $departures = Transaction::whereDate('check_out', $date)
            ->whereIn('status', ['active', 'checked-in'])
            ->with(['customer', 'roomType', 'room'])
            ->orderBy('check_out')
            ->get();
        
        // Statistiques
        $unassignedCount = Transaction::whereDate('check_in', $date)
            ->where('is_assigned', false)
            ->where('status', 'reservation')
            ->count();
            
        $assignedCount = Transaction::whereDate('check_in', $date)
            ->where('is_assigned', true)
            ->whereIn('status', ['reservation', 'active'])
            ->count();
        
        return view('room-assignment.overview', compact(
            'date', 'arrivals', 'departures', 'unassignedCount', 'assignedCount'
        ));
    }
    
    /**
     * Afficher les chambres disponibles pour une réservation
     */
    public function showAvailableRooms(Transaction $transaction)
    {
        if ($transaction->isRoomAssigned()) {
            return redirect()->route('room-assignment.index')
                ->with('info', 'Cette réservation a déjà une chambre attribuée.');
        }
        
        $availableRooms = $transaction->getAvailableRooms();
        
        return view('room-assignment.available-rooms', compact('transaction', 'availableRooms'));
    }
    
    /**
     * Attribuer une chambre à une réservation
     */
    public function assignRoom(Request $request, Transaction $transaction)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'notes' => 'nullable|string|max:500',
        ]);
        
        $result = $transaction->assignRoom($request->room_id, auth()->id(), $request->notes);
        
        if ($result['success']) {
            return redirect()->route('room-assignment.index')
                ->with('success', $result['message']);
        }
        
        return back()->with('error', $result['error']);
    }
    
    /**
     * Changer la chambre attribuée
     */
    public function changeRoom(Request $request, Transaction $transaction)
    {
        if (!$transaction->isRoomAssigned()) {
            return back()->with('error', 'Aucune chambre attribuée à cette réservation.');
        }
        
        $request->validate([
            'new_room_id' => 'required|exists:rooms,id',
            'reason' => 'required|string|max:255',
        ]);
        
        $result = $transaction->assignRoom($request->new_room_id, auth()->id(), 
            "Changement de chambre: {$request->reason}");
        
        if ($result['success']) {
            return redirect()->route('room-assignment.index')
                ->with('success', "Chambre changée: {$result['message']}");
        }
        
        return back()->with('error', $result['error']);
    }
    
    /**
     * API pour obtenir les chambres disponibles
     */
    public function getAvailableRoomsApi(Transaction $transaction)
    {
        $rooms = $transaction->getAvailableRooms();
        
        return response()->json([
            'success' => true,
            'rooms' => $rooms->map(function($room) {
                return [
                    'id' => $room->id,
                    'number' => $room->number,
                    'floor' => $room->floor,
                    'status' => $room->roomStatus->name,
                    'price' => $room->price,
                    'type' => $room->type->name,
                ];
            }),
            'transaction' => [
                'id' => $transaction->id,
                'customer' => $transaction->customer->name,
                'room_type' => $transaction->roomType->name,
                'check_in' => $transaction->check_in->format('d/m/Y'),
                'check_out' => $transaction->check_out->format('d/m/Y'),
            ]
        ]);
    }
    
    /**
     * Dashboard pour la réception
     */
    public function receptionDashboard()
    {
        $today = now()->format('Y-m-d');
        $tomorrow = now()->addDay()->format('Y-m-d');
        
        // Arrivées aujourd'hui
        $todayArrivals = Transaction::whereDate('check_in', $today)
            ->whereIn('status', ['reservation', 'active'])
            ->with(['customer', 'roomType', 'room'])
            ->orderBy('check_in')
            ->get();
        
        // Arrivées demain
        $tomorrowArrivals = Transaction::whereDate('check_in', $tomorrow)
            ->whereIn('status', ['reservation', 'active'])
            ->with(['customer', 'roomType', 'room'])
            ->orderBy('check_in')
            ->get();
        
        // Réservations non attribuées
        $unassignedReservations = Transaction::where('is_assigned', false)
            ->whereIn('status', ['reservation', 'active'])
            ->whereDate('check_in', '<=', $tomorrow)
            ->count();
        
        // Chambres disponibles par type
        $roomTypes = Type::withCount(['rooms as available_rooms_count' => function($query) {
            $query->where('room_status_id', 1); // Disponible
        }])->get();
        
        return view('room-assignment.reception-dashboard', compact(
            'todayArrivals', 'tomorrowArrivals', 'unassignedReservations', 'roomTypes'
        ));
    }
    
    /**
     * Exporter les attributions
     */
    public function export(Request $request)
    {
        $query = Transaction::with(['customer', 'roomType', 'room', 'assignedBy'])
            ->where('is_assigned', true)
            ->whereDate('assigned_at', '>=', $request->get('start_date', now()->subWeek()))
            ->whereDate('assigned_at', '<=', $request->get('end_date', now()))
            ->orderBy('assigned_at', 'desc');
        
        $assignments = $query->get();
        
        // Générer CSV
        $fileName = 'attributions-chambres-' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ];
        
        $callback = function() use ($assignments) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'ID Réservation', 'Client', 'Type Chambre', 'Chambre', 
                'Date Attribution', 'Attribué par', 'Arrivée', 'Départ'
            ]);
            
            foreach ($assignments as $assignment) {
                fputcsv($file, [
                    $assignment->id,
                    $assignment->customer->name,
                    $assignment->roomType->name,
                    $assignment->room->number ?? 'N/A',
                    $assignment->assigned_at->format('d/m/Y H:i'),
                    $assignment->assignedBy->name ?? 'N/A',
                    $assignment->check_in->format('d/m/Y'),
                    $assignment->check_out->format('d/m/Y'),
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}