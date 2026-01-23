<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CheckInController extends Controller
{
    /**
     * Page principale de check-in
     */
    public function index(Request $request)
    {
        $today = Carbon::today();
        
        // Réservations à venir (aujourd'hui et demain)
        $upcomingReservations = Transaction::with(['customer', 'room.type', 'room.roomStatus'])
            ->where('status', 'reservation')
            ->whereDate('check_in', '<=', $today->copy()->addDay()) // Aujourd'hui et demain
            ->whereDate('check_in', '>=', $today->copy()->subDays(1))
            ->orderBy('check_in')
            ->get()
            ->groupBy(function($transaction) {
                return Carbon::parse($transaction->check_in)->format('Y-m-d');
            });
        
        // Réservations actives (dans l'hôtel)
        $activeGuests = Transaction::with(['customer', 'room.type', 'room.roomStatus', 'payments'])
            ->where('status', 'active')
            ->orderBy('check_in')
            ->get();
        
        // Départs du jour
        $todayDepartures = Transaction::with(['customer', 'room.type'])
            ->where('status', 'active')
            ->whereDate('check_out', $today)
            ->orderBy('check_out')
            ->get();
        
        // Statistiques
        $stats = [
            'arrivals_today' => Transaction::whereDate('check_in', $today)->where('status', 'reservation')->count(),
            'departures_today' => Transaction::whereDate('check_out', $today)->where('status', 'active')->count(),
            'currently_checked_in' => Transaction::where('status', 'active')->count(),
            'available_rooms' => Room::where('room_status_id', 1)->count(),
            'occupancy_rate' => $this->calculateOccupancyRate(),
        ];
        
        return view('checkin.index', compact(
            'upcomingReservations',
            'activeGuests',
            'todayDepartures',
            'stats',
            'today'
        ));
    }
    
    /**
     * Page de détail pour check-in d'une réservation
     */
    public function show(Transaction $transaction)
    {
        // Vérifier si la réservation peut être checkée-in
        if ($transaction->status !== 'reservation') {
            return redirect()->route('checkin.index')
                ->with('error', 'Cette réservation ne peut pas être checkée-in. Statut: ' . $transaction->status_label);
        }
        
        // Vérifier disponibilité de la chambre
        $isRoomAvailable = $transaction->room->isAvailableForPeriod(
            $transaction->check_in,
            $transaction->check_out,
            $transaction->id
        );
        
        // Chambres alternatives si besoin
        $alternativeRooms = [];
        if (!$isRoomAvailable) {
            $alternativeRooms = Room::where('room_type_id', $transaction->room->room_type_id)
                ->where('id', '!=', $transaction->room_id)
                ->where('room_status_id', 1) // Available
                ->get()
                ->filter(function($room) use ($transaction) {
                    return $room->isAvailableForPeriod($transaction->check_in, $transaction->check_out);
                });
        }
        
        // Types de pièces d'identité
        $idTypes = [
            'passeport' => 'Passeport',
            'cni' => 'Carte Nationale d\'Identité',
            'permis' => 'Permis de Conduire',
            'autre' => 'Autre'
        ];
        
        return view('checkin.show', compact(
            'transaction',
            'isRoomAvailable',
            'alternativeRooms',
            'idTypes'
        ));
    }
    
    /**
     * Effectuer le check-in
     */
    public function store(Request $request, Transaction $transaction)
    {
        $request->validate([
            'adults' => 'required|integer|min:1|max:10',
            'children' => 'nullable|integer|min:0|max:10',
            'id_type' => 'required|string|in:passeport,cni,permis,autre',
            'id_number' => 'required|string|max:50',
            'nationality' => 'required|string|max:50',
            'special_requests' => 'nullable|string|max:500',
            'change_room' => 'nullable|boolean',
            'new_room_id' => 'nullable|exists:rooms,id',
            'notes' => 'nullable|string|max:500',
        ]);
        
        // Vérifier si changement de chambre demandé
        if ($request->change_room && $request->new_room_id) {
            $newRoom = Room::findOrFail($request->new_room_id);
            
            // Vérifier disponibilité
            if (!$newRoom->isAvailableForPeriod($transaction->check_in, $transaction->check_out, $transaction->id)) {
                return back()->with('error', 'La chambre sélectionnée n\'est pas disponible pour cette période')
                    ->withInput();
            }
            
            // Calculer la différence de prix
            $oldPrice = $transaction->room->price * $transaction->nights;
            $newPrice = $newRoom->price * $transaction->nights;
            $priceDifference = $newPrice - $oldPrice;
            
            // Si le prix est différent, demander confirmation
            if ($priceDifference != 0 && !$request->confirmed_price_change) {
                return back()->with('warning', 
                    'Changement de prix détecté. Ancien prix: ' . number_format($oldPrice, 0, ',', ' ') . ' CFA, ' .
                    'Nouveau prix: ' . number_format($newPrice, 0, ',', ' ') . ' CFA. ' .
                    'Différence: ' . ($priceDifference > 0 ? '+' : '') . number_format($priceDifference, 0, ',', ' ') . ' CFA. ' .
                    'Veuillez confirmer le changement de prix.')
                    ->withInput()
                    ->with('show_price_confirmation', true);
            }
        }
        
        DB::beginTransaction();
        
        try {
            // Si changement de chambre
            if ($request->change_room && $request->new_room_id) {
                $newRoom = Room::findOrFail($request->new_room_id);
                
                // Mettre à jour la transaction avec la nouvelle chambre
                $transaction->update([
                    'room_id' => $newRoom->id,
                    'total_price' => $newRoom->price * $transaction->nights,
                ]);
                
                // Libérer l'ancienne chambre si elle n'est plus occupée
                $transaction->room->update(['room_status_id' => 1]); // Available
            }
            
            // Préparer les données de check-in
            $checkInData = [
                'adults' => $request->adults,
                'children' => $request->children ?? 0,
                'id_type' => $request->id_type,
                'id_number' => $request->id_number,
                'nationality' => $request->nationality,
                'special_requests' => $request->special_requests,
                'notes' => $request->notes,
            ];
            
            // Effectuer le check-in
            $result = $transaction->checkIn(auth()->id(), $checkInData);
            
            if (!$result['success']) {
                throw new \Exception($result['error']);
            }
            
            DB::commit();
            
            // Préparer message de succès
            $message = $this->generateSuccessMessage($transaction, $request);
            
            return redirect()->route('checkin.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Erreur check-in: ' . $e->getMessage(), [
                'transaction_id' => $transaction->id,
                'user_id' => auth()->id(),
                'request' => $request->all(),
            ]);
            
            return back()->with('error', 'Erreur lors du check-in: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Check-in rapide (sans formulaire détaillé)
     */
    public function quickCheckIn(Transaction $transaction)
    {
        // Vérifier si la réservation peut être checkée-in
        if ($transaction->status !== 'reservation') {
            return back()->with('error', 'Cette réservation ne peut pas être checkée-in. Statut: ' . $transaction->status_label);
        }
        
        // Vérifier disponibilité de la chambre
        if (!$transaction->room->isAvailableForPeriod($transaction->check_in, $transaction->check_out, $transaction->id)) {
            return back()->with('error', 'La chambre n\'est pas disponible. Veuillez utiliser le check-in normal pour sélectionner une autre chambre.');
        }
        
        DB::beginTransaction();
        
        try {
            $result = $transaction->checkIn(auth()->id(), [
                'adults' => $transaction->person_count ?? 1,
                'children' => 0,
            ]);
            
            if (!$result['success']) {
                throw new \Exception($result['error']);
            }
            
            DB::commit();
            
            $message = '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="alert-heading mb-2">
                            <i class="fas fa-bolt me-2"></i>Check-in rapide effectué !
                        </h5>
                        <div class="mb-2">
                            <strong>' . $transaction->customer->name . '</strong> a été enregistré dans la chambre ' . $transaction->room->number . '.
                        </div>
                        <p class="mb-0"><small>Arrivée: ' . now()->format('d/m/Y H:i') . '</small></p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>';
            
            return back()->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Erreur check-in rapide: ' . $e->getMessage(), [
                'transaction_id' => $transaction->id,
                'user_id' => auth()->id(),
            ]);
            
            return back()->with('error', 'Erreur lors du check-in rapide: ' . $e->getMessage());
        }
    }
    
    /**
     * Recherche de réservations pour check-in
     */
    public function search(Request $request)
    {
        $search = $request->search;
        $perPage = $request->get('per_page', 10);
        $dateFilter = $request->get('date_filter', 'all'); // all, today, tomorrow, this_week
        
        $query = Transaction::with(['customer', 'room.type', 'room.roomStatus'])
            ->where('status', 'reservation');
        
        // Recherche par texte
        if ($search) {
            $query->where(function($query) use ($search) {
                $query->whereHas('customer', function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('room', function($q) use ($search) {
                    $q->where('number', 'LIKE', "%{$search}%");
                })
                ->orWhere('reference', 'LIKE', "%{$search}%");
            });
        }
        
        // Filtre par date
        if ($dateFilter !== 'all') {
            $today = Carbon::today();
            
            switch ($dateFilter) {
                case 'today':
                    $query->whereDate('check_in', $today);
                    break;
                case 'tomorrow':
                    $query->whereDate('check_in', $today->addDay());
                    break;
                case 'this_week':
                    $query->whereBetween('check_in', [$today, $today->copy()->endOfWeek()]);
                    break;
                case 'next_week':
                    $query->whereBetween('check_in', [$today->copy()->addWeek()->startOfWeek(), $today->copy()->addWeek()->endOfWeek()]);
                    break;
            }
        }
        
        // Filtre par type de chambre
        if ($request->has('room_type_id')) {
            $query->whereHas('room', function($q) use ($request) {
                $q->where('type_id', $request->room_type_id);
            });
        }
        
        $reservations = $query->orderBy('check_in', 'asc')
            ->paginate($perPage)
            ->appends($request->except('page'));
        
        // Pour les filtres dans la vue
        $roomTypes = \App\Models\Type::orderBy('name')->get();
        
        return view('checkin.search', compact(
            'reservations', 
            'search', 
            'perPage', 
            'dateFilter',
            'roomTypes'
        ));
    }
    /**
     * Check-in direct (sans réservation)
     */
    public function directCheckIn()
    {
        $availableRooms = Room::where('room_status_id', 1) // Available
            ->with(['type', 'roomStatus'])
            ->orderBy('number')
            ->get();
        
        $idTypes = [
            'passeport' => 'Passeport',
            'cni' => 'Carte Nationale d\'Identité',
            'permis' => 'Permis de Conduire',
            'autre' => 'Autre'
        ];
        
        return view('checkin.direct', compact('availableRooms', 'idTypes'));
    }
    
    /**
     * Vérifier disponibilité d'une chambre
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'exclude_transaction_id' => 'nullable|exists:transactions,id'
        ]);
        
        $room = Room::findOrFail($request->room_id);
        $isAvailable = $room->isAvailableForPeriod(
            $request->check_in,
            $request->check_out,
            $request->exclude_transaction_id
        );
        
        // Calculer le nombre de nuits et le prix total
        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $nights = $checkIn->diffInDays($checkOut);
        $totalPrice = $room->price * $nights;
        
        $response = [
            'available' => $isAvailable,
            'room' => [
                'id' => $room->id,
                'number' => $room->number,
                'type' => $room->type->name ?? 'N/A',
                'price' => $room->price,
                'capacity' => $room->capacity,
                'formatted_price' => number_format($room->price, 0, ',', ' ') . ' CFA/nuit'
            ],
            'check_in' => $checkIn->format('Y-m-d'),
            'check_out' => $checkOut->format('Y-m-d'),
            'nights' => $nights,
            'total_price' => $totalPrice,
            'formatted_total_price' => number_format($totalPrice, 0, ',', ' ') . ' CFA'
        ];
        
        // Si non disponible, obtenir les conflits
        if (!$isAvailable) {
            $conflicts = $room->getReservationsForPeriod($request->check_in, $request->check_out);
            $response['conflicts'] = $conflicts->map(function($transaction) {
                return [
                    'id' => $transaction->id,
                    'customer' => $transaction->customer->name,
                    'check_in' => $transaction->check_in->format('d/m/Y'),
                    'check_out' => $transaction->check_out->format('d/m/Y'),
                    'status' => $transaction->status
                ];
            });
            
            // Proposer la prochaine date disponible
            $nextAvailable = $room->getNextAvailableDate($checkOut);
            if ($nextAvailable) {
                $response['next_available'] = $nextAvailable->format('Y-m-d');
                $response['suggestion'] = "Disponible à partir du " . $nextAvailable->format('d/m/Y');
            }
        }
        
        return response()->json($response);
    }
    
    /**
     * Générer le message de succès
     */
    private function generateSuccessMessage(Transaction $transaction, Request $request)
    {
        $totalPersons = ($request->adults ?? 1) + ($request->children ?? 0);
        
        $message = '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle fa-2x text-success"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h5 class="alert-heading mb-2">
                        <i class="fas fa-door-open me-2"></i>Check-in effectué avec succès !
                    </h5>
                    <div class="mb-2">
                        <strong>' . $transaction->customer->name . '</strong> a été enregistré.
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Chambre :</strong> ' . $transaction->room->number . '</p>
                            <p class="mb-1"><strong>Arrivée :</strong> ' . now()->format('d/m/Y H:i') . '</p>
                            <p class="mb-1"><strong>Personnes :</strong> ' . $totalPersons . ' (' . $request->adults . ' adultes' . 
                                ($request->children > 0 ? ', ' . $request->children . ' enfants' : '') . ')</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Type de chambre :</strong> ' . ($transaction->room->type->name ?? 'N/A') . '</p>
                            <p class="mb-1"><strong>Départ prévu :</strong> ' . $transaction->check_out->format('d/m/Y H:i') . '</p>
                            <p class="mb-1"><strong>Nuits :</strong> ' . $transaction->nights . ' nuit' . ($transaction->nights > 1 ? 's' : '') . '</p>
                        </div>
                    </div>';
        
        if ($request->id_number) {
            $message .= '<div class="mt-2">
                <p class="mb-1"><small><strong>Pièce d\'identité :</strong> ' . $request->id_number . ' (' . $request->id_type . ')</small></p>
                <p class="mb-0"><small><strong>Nationalité :</strong> ' . $request->nationality . '</small></p>
            </div>';
        }
        
        if ($request->special_requests) {
            $message .= '<div class="mt-2">
                <p class="mb-0"><small><strong>Demandes spéciales :</strong> ' . $request->special_requests . '</small></p>
            </div>';
        }
        
        $message .= '
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>';
        
        return $message;
    }
    
    /**
     * Calculer le taux d'occupation
     */
    private function calculateOccupancyRate()
    {
        $totalRooms = Room::count();
        $occupiedRooms = Transaction::where('status', 'active')->count();
        
        return $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0;
    }
}