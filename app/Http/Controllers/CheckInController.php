<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Room;
use App\Models\Transaction;
use App\Models\Type;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckInController extends Controller
{
    /**
     * Page principale de check-in
     */
    public function index(Request $request)
    {
        $today = Carbon::today();

        // Réservations à venir (aujourd'hui et demain) - Filtrer celles qui ont un type mais pas de chambre attribuée
        $upcomingReservations = Transaction::with(['customer', 'roomType', 'room', 'room.roomStatus'])
            ->where('status', 'reservation')
            ->whereDate('check_in', '<=', $today->copy()->addDay()) // Aujourd'hui et demain
            ->whereDate('check_in', '>=', $today->copy()->subDays(1))
            ->where(function($query) {
                $query->where('is_assigned', false)
                      ->orWhereNull('room_id');
            })
            ->orderBy('check_in')
            ->get()
            ->groupBy(function ($transaction) {
                return Carbon::parse($transaction->check_in)->format('Y-m-d');
            });

        // Réservations avec chambre attribuée mais pas encore checkées-in
        $uncheckedAssigned = Transaction::with(['customer', 'roomType', 'room', 'room.roomStatus'])
            ->where('status', 'reservation')
            ->where('is_assigned', true)
            ->whereDate('check_in', '<=', $today->copy()->addDay())
            ->orderBy('check_in')
            ->get();

        // Réservations actives (dans l'hôtel)
        $activeGuests = Transaction::with(['customer', 'roomType', 'room', 'room.roomStatus', 'payments'])
            ->where('status', 'active')
            ->orderBy('check_in')
            ->get();

        // Départs du jour
        $todayDepartures = Transaction::with(['customer', 'roomType', 'room'])
            ->where('status', 'active')
            ->whereDate('check_out', $today)
            ->orderBy('check_out')
            ->get();

        // Statistiques
        $stats = [
            'arrivals_today' => Transaction::whereDate('check_in', $today)
                ->where('status', 'reservation')
                ->count(),
            'departures_today' => Transaction::whereDate('check_out', $today)
                ->where('status', 'active')
                ->count(),
            'currently_checked_in' => Transaction::where('status', 'active')->count(),
            'available_rooms' => Room::where('room_status_id', 1)->count(),
            'unassigned_reservations' => Transaction::where('status', 'reservation')
                ->where('is_assigned', false)
                ->whereDate('check_in', '<=', $today->copy()->addDay())
                ->count(),
            'occupancy_rate' => $this->calculateOccupancyRate(),
        ];

        return view('checkin.index', compact(
            'upcomingReservations',
            'uncheckedAssigned',
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
                ->with('error', 'Cette réservation ne peut pas être checkée-in. Statut: '.$transaction->status_label);
        }

        // Vérifier si une chambre est attribuée
        if (!$transaction->isRoomAssigned()) {
            // Rediriger vers l'attribution de chambre
            return redirect()->route('room-assignment.available-rooms', $transaction)
                ->with('warning', 'Veuillez d\'abord attribuer une chambre au client avant le check-in.');
        }

        // Vérifier disponibilité de la chambre attribuée
        $isRoomAvailable = $transaction->room->isAvailableForPeriod(
            $transaction->check_in,
            $transaction->check_out,
            $transaction->id
        );

        // Si chambre attribuée mais non disponible, proposer des alternatives du même type
        $alternativeRooms = collect();
        if (!$isRoomAvailable && $transaction->room_type_id) {
            $alternativeRooms = Room::where('type_id', $transaction->room_type_id)
                ->where('id', '!=', $transaction->room_id)
                ->where('room_status_id', 1) // Disponible
                ->get()
                ->filter(function ($room) use ($transaction) {
                    return $room->isAvailableForPeriod($transaction->check_in, $transaction->check_out, $transaction->id);
                });
        }

        // Types de pièces d'identité
        $idTypes = [
            'passeport' => 'Passeport',
            'cni' => 'Carte Nationale d\'Identité',
            'permis' => 'Permis de Conduire',
            'autre' => 'Autre',
        ];

        return view('checkin.show', compact(
            'transaction',
            'isRoomAvailable',
            'alternativeRooms',
            'idTypes'
        ));
    }

    /**
     * Effectuer le check-in avec vérification d'attribution
     */
    public function store(Request $request, Transaction $transaction)
    {
        // VÉRIFICATION CRITIQUE : La chambre est-elle attribuée ?
        if (!$transaction->isRoomAssigned()) {
            return redirect()->route('room-assignment.available-rooms', $transaction)
                ->with('error', 'Impossible de procéder au check-in : aucune chambre n\'a été attribuée à cette réservation.');
        }

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

            // Vérifier que la nouvelle chambre est du même type
            if ($newRoom->type_id != $transaction->room_type_id) {
                return back()->with('error', 'La nouvelle chambre doit être du même type que celui réservé.')
                    ->withInput();
            }

            // Vérifier disponibilité
            if (!$newRoom->isAvailableForPeriod($transaction->check_in, $transaction->check_out, $transaction->id)) {
                return back()->with('error', 'La chambre sélectionnée n\'est pas disponible pour cette période')
                    ->withInput();
            }

            // Calculer la différence de prix
            $oldPrice = $transaction->getTotalPrice();
            $newPrice = $this->calculateRoomPrice($newRoom, $transaction->check_in, $transaction->check_out);
            $priceDifference = $newPrice - $oldPrice;

            // Si le prix est différent, demander confirmation
            if ($priceDifference != 0 && !$request->confirmed_price_change) {
                return back()->with('warning',
                    'Changement de prix détecté. Ancien prix: '.number_format($oldPrice, 0, ',', ' ').' CFA, '.
                    'Nouveau prix: '.number_format($newPrice, 0, ',', ' ').' CFA. '.
                    'Différence: '.($priceDifference > 0 ? '+' : '').number_format($priceDifference, 0, ',', ' ').' CFA. '.
                    'Veuillez confirmer le changement de prix.')
                    ->withInput()
                    ->with('show_price_confirmation', true);
            }
        }

        DB::beginTransaction();

        try {
            // Si changement de chambre demandé et confirmé
            if ($request->change_room && $request->new_room_id) {
                $newRoom = Room::findOrFail($request->new_room_id);
                
                // Attribuer la nouvelle chambre
                $result = $transaction->assignRoom($newRoom->id, auth()->id(), 'Changement de chambre au check-in');
                
                if (!$result['success']) {
                    throw new \Exception($result['error']);
                }
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

            // Effectuer le check-in via la méthode du modèle
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

            Log::error('Erreur check-in: '.$e->getMessage(), [
                'transaction_id' => $transaction->id,
                'user_id' => auth()->id(),
                'request' => $request->all(),
            ]);

            return back()->with('error', 'Erreur lors du check-in: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Check-in rapide (pour chambre déjà attribuée)
     */
    public function quickCheckIn(Transaction $transaction)
    {
        // Vérifier si la réservation peut être checkée-in
        if ($transaction->status !== 'reservation') {
            return response()->json([
                'success' => false,
                'message' => 'Cette réservation ne peut pas être checkée-in. Statut: '.$transaction->status_label,
            ], 422);
        }

        // VÉRIFICATION CRITIQUE : La chambre est-elle attribuée ?
        if (!$transaction->isRoomAssigned()) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune chambre attribuée à cette réservation.',
                'redirect' => route('room-assignment.available-rooms', $transaction)
            ], 422);
        }

        // Vérifier disponibilité de la chambre
        if (!$transaction->room->isAvailableForPeriod($transaction->check_in, $transaction->check_out, $transaction->id)) {
            return response()->json([
                'success' => false,
                'message' => 'La chambre n\'est pas disponible. Veuillez utiliser le check-in normal.',
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Utiliser la méthode checkIn du modèle Transaction
            $result = $transaction->checkIn(auth()->id(), [
                'adults' => $transaction->person_count ?? 1,
                'children' => 0,
            ]);

            if (!$result['success']) {
                throw new \Exception($result['error']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Check-in rapide effectué avec succès',
                'transaction' => $transaction->fresh()->load('customer', 'room', 'roomType'),
                'html' => $this->generateQuickCheckInSuccessHtml($transaction),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur check-in rapide: '.$e->getMessage(), [
                'transaction_id' => $transaction->id,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du check-in rapide: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Recherche de réservations pour check-in
     */
    public function search(Request $request)
    {
        $search = $request->search;
        $perPage = $request->get('per_page', 10);
        $dateFilter = $request->get('date_filter', 'all');
        $assignmentFilter = $request->get('assignment_filter', 'all'); // all, assigned, unassigned

        $query = Transaction::with(['customer', 'roomType', 'room', 'room.roomStatus'])
            ->where('status', 'reservation');

        // Filtre par attribution
        if ($assignmentFilter === 'assigned') {
            $query->where('is_assigned', true);
        } elseif ($assignmentFilter === 'unassigned') {
            $query->where('is_assigned', false);
        }

        // Recherche par texte
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->whereHas('customer', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhere('phone', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('roomType', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('room', function ($q) use ($search) {
                    $q->where('number', 'LIKE', "%{$search}%");
                })
                ->orWhere('id', 'LIKE', "%{$search}%");
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
                    $query->whereDate('check_in', $today->copy()->addDay());
                    break;
                case 'this_week':
                    $query->whereBetween('check_in', [$today, $today->copy()->endOfWeek()]);
                    break;
                case 'next_week':
                    $query->whereBetween('check_in', [$today->copy()->addWeek()->startOfWeek(), $today->copy()->addWeek()->endOfWeek()]);
                    break;
                case 'overdue':
                    $query->whereDate('check_in', '<', $today);
                    break;
            }
        }

        // Filtre par type de chambre
        if ($request->has('room_type_id')) {
            $query->where('room_type_id', $request->room_type_id);
        }

        $reservations = $query->orderBy('check_in', 'asc')
            ->orderBy('is_assigned', 'asc') // Les non-attribuées d'abord
            ->paginate($perPage)
            ->appends($request->except('page'));

        $roomTypes = Type::orderBy('name')->get();

        return view('checkin.search', compact(
            'reservations',
            'search',
            'perPage',
            'dateFilter',
            'assignmentFilter',
            'roomTypes'
        ));
    }

    /**
     * Check-in direct (sans réservation)
     */
    public function directCheckIn()
    {
        // Récupérer les types de chambre disponibles
        $availableRoomTypes = Type::with(['rooms' => function($query) {
            $query->where('room_status_id', 1); // Disponible
        }])->whereHas('rooms', function($query) {
            $query->where('room_status_id', 1);
        })->get();

        $idTypes = [
            'passeport' => 'Passeport',
            'cni' => 'Carte Nationale d\'Identité',
            'permis' => 'Permis de Conduire',
            'autre' => 'Autre',
        ];

        return view('checkin.direct', compact('availableRoomTypes', 'idTypes'));
    }

    /**
     * Vérifier disponibilité d'une chambre pour check-in direct
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required|exists:types,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        $roomType = Type::findOrFail($request->room_type_id);
        
        // Trouver une chambre disponible de ce type
        $availableRoom = Room::where('type_id', $roomType->id)
            ->where('room_status_id', 1) // Disponible
            ->whereDoesntHave('transactions', function ($query) use ($request) {
                $query->where('check_in', '<', $request->check_out)
                    ->where('check_out', '>', $request->check_in)
                    ->whereIn('status', ['reservation', 'active']);
            })
            ->first();

        if (!$availableRoom) {
            return response()->json([
                'available' => false,
                'message' => 'Aucune chambre disponible pour ce type et cette période.',
                'suggestion' => 'Veuillez choisir d\'autres dates ou un autre type de chambre.',
            ], 200);
        }

        // Calculer le nombre de nuits et le prix total
        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $nights = $checkIn->diffInDays($checkOut);
        $totalPrice = $roomType->price * $nights;

        return response()->json([
            'available' => true,
            'room_type' => [
                'id' => $roomType->id,
                'name' => $roomType->name,
                'price' => $roomType->price,
                'capacity' => $roomType->capacity,
                'formatted_price' => number_format($roomType->price, 0, ',', ' ').' CFA/nuit',
            ],
            'assigned_room' => [
                'id' => $availableRoom->id,
                'number' => $availableRoom->number,
                'floor' => $availableRoom->floor,
            ],
            'check_in' => $checkIn->format('Y-m-d'),
            'check_out' => $checkOut->format('Y-m-d'),
            'nights' => $nights,
            'total_price' => $totalPrice,
            'formatted_total_price' => number_format($totalPrice, 0, ',', ' ').' CFA',
        ]);
    }

    /**
     * Traiter un check-in direct (walk-in)
     */
    public function processDirectCheckIn(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required|exists:types,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'required_without:customer_id|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'required_without:customer_id|string|max:50',
            'adults' => 'required|integer|min:1|max:10',
            'children' => 'nullable|integer|min:0|max:10',
            'id_type' => 'required|string|in:passeport,cni,permis,autre',
            'id_number' => 'required|string|max:50',
            'nationality' => 'required|string|max:50',
            'special_requests' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            // Vérifier la chambre
            $room = Room::findOrFail($request->room_id);
            
            // Vérifier que la chambre est du type sélectionné
            if ($room->type_id != $request->room_type_id) {
                throw new \Exception('La chambre sélectionnée n\'est pas du type choisi.');
            }

            // Vérifier disponibilité
            if (!$room->isAvailableForPeriod($request->check_in, $request->check_out)) {
                throw new \Exception('La chambre n\'est pas disponible pour cette période.');
            }

            // Créer ou récupérer le client
            if ($request->customer_id) {
                $customer = Customer::findOrFail($request->customer_id);
            } else {
                $customer = Customer::create([
                    'name' => $request->customer_name,
                    'email' => $request->customer_email ?? null,
                    'phone' => $request->customer_phone,
                    'user_id' => auth()->id(),
                ]);
            }

            // Calculer le prix
            $roomType = Type::find($request->room_type_id);
            $checkIn = Carbon::parse($request->check_in);
            $checkOut = Carbon::parse($request->check_out);
            $nights = $checkIn->diffInDays($checkOut);
            if ($nights == 0) $nights = 1;
            $totalPrice = $roomType->price * $nights;
            $personCount = $request->adults + ($request->children ?? 0);

            // Créer la transaction avec chambre attribuée immédiatement
            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'customer_id' => $customer->id,
                'room_type_id' => $request->room_type_id,
                'room_id' => $room->id,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'total_price' => $totalPrice,
                'person_count' => $personCount,
                'status' => 'reservation',
                'is_assigned' => true,
                'assigned_at' => now(),
                'assigned_by' => auth()->id(),
                'special_requests' => $request->special_requests,
                'id_type' => $request->id_type,
                'id_number' => $request->id_number,
                'nationality' => $request->nationality,
                'notes' => $request->notes,
            ]);

            // Mettre à jour le statut de la chambre
            $room->update(['room_status_id' => 2]); // Occupée

            // Effectuer le check-in immédiatement
            $checkInData = [
                'adults' => $request->adults,
                'children' => $request->children ?? 0,
                'id_type' => $request->id_type,
                'id_number' => $request->id_number,
                'nationality' => $request->nationality,
                'special_requests' => $request->special_requests,
                'notes' => $request->notes,
            ];

            $result = $transaction->checkIn(auth()->id(), $checkInData);

            if (!$result['success']) {
                throw new \Exception($result['error']);
            }

            DB::commit();

            $message = $this->generateDirectCheckInSuccessMessage($transaction, $customer, $room, $checkIn, $checkOut, $nights, $totalPrice);

            return redirect()->route('transaction.show', $transaction)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur check-in direct: '.$e->getMessage(), [
                'user_id' => auth()->id(),
                'request' => $request->all(),
            ]);

            return back()->with('error', 'Erreur lors du check-in direct: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Générer le message de succès pour check-in normal
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
                        <strong>'.$transaction->customer->name.'</strong> a été enregistré.
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Chambre :</strong> '.$transaction->room->number.'</p>
                            <p class="mb-1"><strong>Type :</strong> '.($transaction->roomType->name ?? 'N/A').'</p>
                            <p class="mb-1"><strong>Arrivée :</strong> '.now()->format('d/m/Y H:i').'</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Personnes :</strong> '.$totalPersons.' ('.$request->adults.' adultes'.
                                ($request->children > 0 ? ', '.$request->children.' enfants' : '').')</p>
                            <p class="mb-1"><strong>Départ :</strong> '.$transaction->check_out->format('d/m/Y H:i').'</p>
                            <p class="mb-1"><strong>Nuits :</strong> '.$transaction->nights.' nuit'.($transaction->nights > 1 ? 's' : '').'</p>
                        </div>
                    </div>';

        if ($request->id_number) {
            $message .= '<div class="mt-2">
                <p class="mb-1"><small><strong>Pièce d\'identité :</strong> '.$request->id_number.' ('.$request->id_type.')</small></p>
                <p class="mb-0"><small><strong>Nationalité :</strong> '.$request->nationality.'</small></p>
            </div>';
        }

        if ($request->special_requests) {
            $message .= '<div class="mt-2">
                <p class="mb-0"><small><strong>Demandes spéciales :</strong> '.$request->special_requests.'</small></p>
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
     * Générer le message de succès pour check-in direct
     */
    private function generateDirectCheckInSuccessMessage($transaction, $customer, $room, $checkIn, $checkOut, $nights, $totalPrice)
    {
        $message = '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-user-plus fa-2x text-success"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h5 class="alert-heading mb-2">
                        <i class="fas fa-bolt me-2"></i>Check-in direct effectué !
                    </h5>
                    <div class="mb-2">
                        <strong>'.$customer->name.'</strong> a été enregistré en chambre '.$room->number.'.
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Chambre :</strong> '.$room->number.'</p>
                            <p class="mb-1"><strong>Type :</strong> '.($transaction->roomType->name ?? 'N/A').'</p>
                            <p class="mb-1"><strong>Période :</strong> '.$checkIn->format('d/m/Y').' - '.$checkOut->format('d/m/Y').'</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Nuits :</strong> '.$nights.' nuit'.($nights > 1 ? 's' : '').'</p>
                            <p class="mb-1"><strong>Prix total :</strong> '.number_format($totalPrice, 0, ',', ' ').' CFA</p>
                            <p class="mb-1"><strong>Référence :</strong> #TRX-'.$transaction->id.'</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="fas fa-user-circle me-1"></i>Agent: '.auth()->user()->name.' | 
                            <i class="fas fa-calendar me-1"></i>Créé le: '.now()->format('d/m/Y H:i')
                        .'</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>';

        return $message;
    }

    /**
     * Générer le HTML de succès pour check-in rapide (AJAX)
     */
    private function generateQuickCheckInSuccessHtml($transaction)
    {
        return '
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
                        <strong>'.$transaction->customer->name.'</strong> a été enregistré dans la chambre '.$transaction->room->number.'.
                    </div>
                    <p class="mb-0"><small>Arrivée: '.now()->format('d/m/Y H:i').'</small></p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>';
    }

    /**
     * Calculer le prix d'une chambre pour une période
     */
    private function calculateRoomPrice($room, $checkIn, $checkOut)
    {
        $checkIn = Carbon::parse($checkIn);
        $checkOut = Carbon::parse($checkOut);
        $nights = $checkIn->diffInDays($checkOut);
        if ($nights == 0) $nights = 1;
        
        return $room->price * $nights;
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

    /**
     * Afficher le dashboard avancé pour le check-in
     */
    public function dashboard()
    {
        $today = Carbon::today();
        
        // Réservations non attribuées pour aujourd'hui
        $unassignedToday = Transaction::with(['customer', 'roomType'])
            ->where('status', 'reservation')
            ->where('is_assigned', false)
            ->whereDate('check_in', $today)
            ->orderBy('check_in')
            ->get();
        
        // Réservations attribuées mais pas encore checkées-in
        $assignedNotCheckedIn = Transaction::with(['customer', 'roomType', 'room'])
            ->where('status', 'reservation')
            ->where('is_assigned', true)
            ->whereDate('check_in', $today)
            ->orderBy('check_in')
            ->get();
        
        // Arrivées du jour avec chambre attribuée
        $todayArrivals = Transaction::with(['customer', 'roomType', 'room'])
            ->where('status', 'reservation')
            ->whereDate('check_in', $today)
            ->orderBy('check_in')
            ->get();
        
        // Départs du jour
        $todayDepartures = Transaction::with(['customer', 'roomType', 'room'])
            ->where('status', 'active')
            ->whereDate('check_out', $today)
            ->orderBy('check_out')
            ->get();
        
        // Types de chambre avec disponibilité
        $roomTypes = Type::withCount(['rooms as total_rooms'])
            ->withCount(['rooms as available_rooms' => function($query) {
                $query->where('room_status_id', 1);
            }])
            ->get();
        
        return view('checkin.dashboard', compact(
            'unassignedToday',
            'assignedNotCheckedIn',
            'todayArrivals',
            'todayDepartures',
            'roomTypes',
            'today'
        ));
    }
}