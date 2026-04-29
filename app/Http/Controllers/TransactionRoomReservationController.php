<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\ChooseRoomRequest;
use App\Models\Customer;
use App\Models\Room;
use App\Models\Transaction;
use App\Models\User; 
use App\Repositories\Interface\PaymentRepositoryInterface;
use App\Repositories\Interface\ReservationRepositoryInterface;
use App\Repositories\Interface\TransactionRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Notifications\NewRoomReservationDownPayment;

class TransactionRoomReservationController extends Controller
{
    public function __construct(
        private ReservationRepositoryInterface $reservationRepository
    ) {}

    /**
     * Afficher le formulaire de création d'identité
     */
    public function createIdentity()
    {
        return view('transaction.reservation.createIdentity', [
            'info' => 'Same email can be used for multiple reservations. If customer exists, information will be updated.',
        ]);
    }

    /**
     * Enregistrer ou mettre à jour un client
     */
    public function storeCustomer(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'gender' => 'required|in:Male,Female,Other',
            'address' => 'nullable|string',
            'job' => 'nullable|string|max:100',
            'birthdate' => 'nullable|date',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Rechercher un client avec le même email ET même nom
        $existingCustomer = Customer::where('email', $validated['email'])
            ->where('name', $validated['name'])
            ->first();

        if ($existingCustomer) {
            // Mettre à jour le client existant
            $updateData = [
                'phone' => $validated['phone'],
                'gender' => $validated['gender'],
                'address' => $validated['address'],
                'job' => $validated['job'],
                'birthdate' => $validated['birthdate'],
            ];

            // Gérer l'avatar si fourni
            if ($request->hasFile('avatar')) {
                if ($existingCustomer->avatar && Storage::exists($existingCustomer->avatar)) {
                    Storage::delete($existingCustomer->avatar);
                }

                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $updateData['avatar'] = $avatarPath;
            }

            $existingCustomer->update($updateData);
            $customer = $existingCustomer;

            $message = 'Informations client mises à jour : '.$customer->name;
        } else {
            // Récupérer l'utilisateur connecté
            $user = auth()->user();
            if (! $user) {
                return redirect()->route('login')
                    ->with('error', 'Vous devez être connecté pour créer un client');
            }

            // Créer un nouveau client - seulement les champs nécessaires
            $customerData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'gender' => $validated['gender'],
                'address' => $validated['address'] ?? null,
                'job' => $validated['job'] ?? null,
                'birthdate' => $validated['birthdate'] ?? null,
                'user_id' => $user->id,
            ];

            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $customerData['avatar'] = $avatarPath;
            }

            $customer = Customer::create($customerData);
            $message = 'Nouveau client créé par '.$user->name.' : '.$customer->name;
        }

        return redirect()
            ->route('transaction.reservation.viewCountPerson', ['customer' => $customer->id])
            ->with('success', $message);
    }

    /**
     * Afficher le formulaire pour saisir les dates de séjour
     */
    public function viewCountPerson(Customer $customer)
    {
        $existingReservations = $customer->transactions()->count();

        return view('transaction.reservation.viewCountPerson', [
            'customer' => $customer,
            'existingReservations' => $existingReservations,
        ]);
    }

    /**
     * Choisir une chambre disponible
     */
    public function chooseRoom(ChooseRoomRequest $request, Customer $customer)
    {
        $stayFrom = $request->check_in;
        $stayUntil = $request->check_out;

        // Vérifier les chambres occupées
        $occupiedRoomId = $this->getOccupiedRoomID($request->check_in, $request->check_out);

        // Récupérer les chambres disponibles
        $rooms = $this->reservationRepository->getUnocuppiedroom($request, $occupiedRoomId);
        $roomsCount = $this->reservationRepository->countUnocuppiedroom($request, $occupiedRoomId);

        return view('transaction.reservation.chooseRoom', [
            'customer' => $customer,
            'rooms' => $rooms,
            'stayFrom' => $stayFrom,
            'stayUntil' => $stayUntil,
            'roomsCount' => $roomsCount,
            'occupiedRoomIds' => $occupiedRoomId,
        ]);
    }

    /**
     * Afficher la confirmation de réservation
     */
    public function confirmation(Customer $customer, Room $room, $stayFrom, $stayUntil)
    {
        // Calculer le prix
        $price = $room->price;
        $dayDifference = Helper::getDateDifference($stayFrom, $stayUntil);
        $downPayment = ($price * $dayDifference) * 0.15;

        $existingReservationsCount = $customer->transactions()->count();

        return view('transaction.reservation.confirmation', [
            'customer' => $customer,
            'room' => $room,
            'stayFrom' => $stayFrom,
            'stayUntil' => $stayUntil,
            'downPayment' => $downPayment,
            'dayDifference' => $dayDifference,
            'existingReservationsCount' => $existingReservationsCount,
        ]);
    }

    /**
     * Traiter le paiement et créer la réservation
     */
    public function payDownPayment(
        Customer $customer,
        Room $room,
        Request $request,
        ?TransactionRepositoryInterface $transactionRepository = null,
        ?PaymentRepositoryInterface $paymentRepository = null
    ) {
        // ============ GESTION UTILISATEUR CONNECTÉ ============
        $user = auth()->user();
        $userId = null;

        if ($user) {
            $userId = $user->id;
        } else {
            $admin = \App\Models\User::whereIn('role', ['Super', 'Admin'])->first();
            if ($admin) {
                $userId = $admin->id;
                $user = $admin;
            } else {
                $firstUser = \App\Models\User::first();
                if ($firstUser) {
                    $userId = $firstUser->id;
                    $user = $firstUser;
                } else {
                    return redirect()->route('login')
                        ->with('error', 'Erreur système: Aucun utilisateur trouvé dans la base de données. Veuillez contacter l\'administrateur.');
                }
            }
        }

        if (! $userId) {
            $userId = 1;
        }

        try {
            $validator = \Validator::make($request->all(), [
                'check_in' => 'required|date',
                'check_out' => 'required|date|after:check_in',
                'downPayment' => 'nullable|numeric|min:0',
                'person_count' => 'nullable|integer|min:1|max:'.$room->capacity,
                'payment_method' => 'nullable|string|in:cash,card,mobile_money',
            ], [
                'check_in.required' => 'La date d\'arrivée est obligatoire',
                'check_out.required' => 'La date de départ est obligatoire',
                'check_out.after' => 'La date de départ doit être après la date d\'arrivée',
                'person_count.max' => 'Le nombre de personnes ne peut pas dépasser la capacité de la chambre ('.$room->capacity.')',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $validated = $validator->validated();

            $checkIn = Carbon::parse($validated['check_in']);
            $checkOut = Carbon::parse($validated['check_out']);
            $days = $checkIn->diffInDays($checkOut);
            if ($days == 0) {
                $days = 1;
            }

            $totalPrice = $room->price * $days;
            $downPayment = $validated['downPayment'] ?? 0;
            $personCount = $validated['person_count'] ?? 1;
            $paymentMethod = $validated['payment_method'] ?? 'cash';

            // Vérifier l'acompte
            if ($downPayment > $totalPrice) {
                return redirect()->back()
                    ->with('error', 'L\'acompte ne peut pas dépasser le prix total')
                    ->withInput();
            }

            $isOccupied = $this->isRoomOccupied($room->id, $checkIn, $checkOut);

            if ($isOccupied) {
                return redirect()->back()
                    ->with('error', 'Cette chambre n\'est plus disponible pour les dates sélectionnées. Veuillez choisir d\'autres dates ou une autre chambre.')
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                // Données de la transaction
                $transactionData = [
                    'user_id' => $userId,
                    'customer_id' => $customer->id,
                    'room_id' => $room->id,
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'person_count' => $personCount,
                    'total_price' => $totalPrice,
                    'total_payment' => $downPayment,
                    'status' => 'reservation',
                    'notes' => sprintf(
                        'Réservation créée par %s | %d nuit(s) | %s FCFA/nuit | Acompte: %s FCFA | Méthode: %s',
                        $user->name ?? 'Système',
                        $days,
                        number_format($room->price, 0, ',', ' '),
                        number_format($downPayment, 0, ',', ' '),
                        $paymentMethod
                    ),
                    'checkin_notes' => json_encode([
                        'agent' => $user->name ?? 'Système',
                        'nights' => $days,
                        'price_per_night' => $room->price,
                        'room_type' => $room->type->name ?? 'Standard',
                        'payment_method' => $paymentMethod,
                        'down_payment' => $downPayment,
                        'total_amount' => $totalPrice,
                        'created_at' => now()->toDateTimeString(),
                    ]),
                ];

                $transaction = null;
                if ($transactionRepository && method_exists($transactionRepository, 'store')) {
                    try {
                        $transaction = $transactionRepository->store($request, $customer, $room);
                    } catch (\Exception $e) {
                        \Log::warning('Fallback création transaction: ' . $e->getMessage());
                        $transaction = Transaction::create($transactionData);
                    }
                } else {
                    $transaction = Transaction::create($transactionData);
                }

                // Vérifier que la transaction a bien été créée
                if (! $transaction) {
                    throw new \Exception('Échec de la création de la transaction');
                }

                // ============ CRÉATION DU PAIEMENT (si acompte) ============
                $payment = null;
                if ($downPayment > 0) {
                    try {
                        $paymentData = [
                            'user_id' => $userId,
                            'transaction_id' => $transaction->id,
                            'amount' => $downPayment,
                            'payment_method' => $paymentMethod,
                            'reference' => 'PAY-'.$transaction->id.'-'.time(),
                            'status' => 'completed',
                            'notes' => sprintf(
                                'Acompte réservation | Agent: %s | Client: %s | Chambre: %s | Nuits: %d',
                                $user->name ?? 'Système',
                                $customer->name,
                                $room->number,
                                $days
                            ),
                        ];

                        if ($paymentRepository) {
                            try {
                                if (method_exists($paymentRepository, 'create')) {
                                    $payment = $paymentRepository->create($paymentData);
                                } elseif (method_exists($paymentRepository, 'store')) {
                                    $mockRequest = new \Illuminate\Http\Request;
                                    $mockRequest->merge([
                                        'amount'         => $downPayment,
                                        'payment_method' => $paymentMethod,
                                        'notes'          => 'Acompte réservation',
                                        'reference'      => $paymentData['reference'],
                                    ]);
                                    $payment = $paymentRepository->store($mockRequest, $transaction, 'Acompte');
                                } else {
                                    $payment = \App\Models\Payment::create($paymentData);
                                }
                            } catch (\Exception $repoError) {
                                \Log::warning('Fallback création paiement: ' . $repoError->getMessage());
                                $payment = \App\Models\Payment::create($paymentData);
                            }
                        } else {
                            $payment = \App\Models\Payment::create($paymentData);
                        }

                    } catch (\Exception $e) {
                        \Log::warning('Erreur création paiement (non bloquant): ' . $e->getMessage());
                        // Continuer même si le paiement échoue - la réservation est déjà créée
                    }
                }

                // ============ MISE À JOUR STATUT CHAMBRE ============
                try {
                    $checkInDate = Carbon::parse($validated['check_in']);
                    $room->update([
                        'room_status_id' => $checkInDate->isPast() ? 2 : 3,
                    ]);
                } catch (\Exception $e) {
                    \Log::warning('Erreur mise à jour statut chambre: ' . $e->getMessage());
                }

                // ============ ENVOI DES NOTIFICATIONS ============
                $this->sendReservationNotifications($transaction, $payment, $user, $customer, $room, $days, $totalPrice, $downPayment, $paymentMethod);

                // ============ CONFIRMATION ============
                DB::commit();

                $successMessage = $this->buildSuccessMessageWithUser(
                    $transaction,
                    $customer,
                    $room,
                    $checkIn,
                    $checkOut,
                    $days,
                    $totalPrice,
                    $downPayment,
                    $user
                );

                return redirect()->route('transaction.show', $transaction)
                    ->with('success', $successMessage)
                    ->with('transaction_id', $transaction->id)
                    ->with('agent_name', $user->name ?? 'Système');

            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Erreur création réservation: ' . $e->getMessage(), [
                    'customer_id' => $customer->id,
                    'room_id'     => $room->id,
                ]);

                return redirect()->back()
                    ->with('error', 'Erreur lors de la création de la réservation: ' . $e->getMessage())
                    ->withInput();
            }

        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('QueryException réservation: ' . $e->getMessage(), [
                'sql'      => $e->getSql(),
                'bindings' => $e->getBindings(),
            ]);

            $errorMessage = 'Erreur de base de données lors de la réservation.';

            if (strpos($e->getMessage(), 'Column not found') !== false) {
                preg_match("/Column not found.*'([^']+)'/", $e->getMessage(), $matches);
                $column = $matches[1] ?? 'inconnue';
                $errorMessage = "Erreur: La colonne '{$column}' n'existe pas dans la table. Veuillez exécuter: ALTER TABLE transactions ADD COLUMN notes TEXT NULL;";
            } elseif (strpos($e->getMessage(), 'doesn\'t have a default value') !== false) {
                $field = $this->extractFieldName($e->getMessage());
                $errorMessage = "Erreur: Le champ '{$field}' est requis.";
            }

            return redirect()->back()
                ->with('error', $errorMessage)
                ->withInput();

        } catch (\Exception $e) {
            \Log::error('Erreur générale réservation: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Erreur lors de la réservation: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * ✅ NOUVELLE MÉTHODE CORRIGÉE : Envoyer les notifications après une réservation
     */
    private function sendReservationNotifications($transaction, $payment, $user, $customer, $room, $days, $totalPrice, $downPayment, $paymentMethod)
    {
        try {
            // Notifier les réceptionnistes et admins
            $staffUsers = User::whereIn('role', ['Receptionist', 'Admin', 'Super'])->get();
            
            $notificationCount = 0;

            foreach ($staffUsers as $staffUser) {
                try {
                    // Utiliser la notification avec les données complètes
                    $staffUser->notify(new \App\Notifications\NewRoomReservationDownPayment($transaction, $payment));
                    $notificationCount++;
                } catch (\Exception $e) {
                    \Log::warning('Erreur envoi notification staff: ' . $e->getMessage());
                }
            }

            // Notifier le client s'il a un compte
            if ($customer->user) {
                try {
                    $customer->user->notify(new \App\Notifications\NewRoomReservationDownPayment($transaction, $payment));
                    $notificationCount++;
                } catch (\Exception $e) {
                    \Log::warning('Erreur envoi notification client: ' . $e->getMessage());
                }
            }


        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi des notifications', [
                'transaction_id' => $transaction->id ?? 'N/A',
                'error' => $e->getMessage()
            ]);
        }
    }
    /**
     * Construire le message de succès avec l'utilisateur
     */
    private function buildSuccessMessageWithUser($transaction, $customer, $room, $checkIn, $checkOut, $days, $totalPrice, $downPayment, $user)
    {
        $message = '<div class="alert alert-success border-0">';
        $message .= '<div class="d-flex align-items-center mb-3">';
        $message .= '<i class="fas fa-check-circle fa-2x me-3 text-success"></i>';
        $message .= '<div>';
        $message .= '<h5 class="alert-heading mb-1">✅ Réservation confirmée !</h5>';
        $message .= '<p class="mb-0"><small>Réservée par <strong>'.$user->name.'</strong></small></p>';
        $message .= '</div>';
        $message .= '</div>';

        $message .= '<div class="row">';
        $message .= '<div class="col-md-6">';
        $message .= '<p><strong><i class="fas fa-user me-2"></i>Client:</strong> '.$customer->name.'</p>';
        $message .= '<p><strong><i class="fas fa-bed me-2"></i>Chambre:</strong> '.$room->number.' ('.($room->type->name ?? 'Standard').')</p>';
        $message .= '<p><strong><i class="fas fa-calendar-alt me-2"></i>Période:</strong> '.$checkIn->format('d/m/Y').' → '.$checkOut->format('d/m/Y').'</p>';
        $message .= '<p><strong><i class="fas fa-moon me-2"></i>Durée:</strong> '.$days.' nuit'.($days > 1 ? 's' : '').'</p>';
        $message .= '</div>';

        $message .= '<div class="col-md-6">';
        $message .= '<p><strong><i class="fas fa-receipt me-2"></i>Prix total:</strong> '.number_format($totalPrice, 0, ',', ' ').' FCFA</p>';

        if ($downPayment > 0) {
            $remaining = $totalPrice - $downPayment;
            $message .= '<p class="text-success"><strong><i class="fas fa-money-bill-wave me-2"></i>Acompte payé:</strong> '.number_format($downPayment, 0, ',', ' ').' FCFA</p>';
            if ($remaining > 0) {
                $message .= '<p class="text-warning"><strong><i class="fas fa-balance-scale me-2"></i>Solde à régler:</strong> '.number_format($remaining, 0, ',', ' ').' FCFA</p>';
            } else {
                $message .= '<p class="text-success"><strong><i class="fas fa-check-double me-2"></i>✅ Paiement complet</strong></p>';
            }
        } else {
            $message .= '<p class="text-info"><strong><i class="fas fa-clock me-2"></i>À régler à l\'arrivée:</strong> '.number_format($totalPrice, 0, ',', ' ').' FCFA</p>';
        }

        $message .= '</div>';
        $message .= '</div>';

        $message .= '<hr class="my-3">';
        $message .= '<div class="text-center">';
        $message .= '<small class="text-muted">';
        $message .= '<i class="fas fa-hashtag me-1"></i>Référence: #TRX-'.$transaction->id.' | ';
        $message .= '<i class="fas fa-user-circle me-1"></i>Agent: '.$user->name.' | ';
        $message .= '<i class="fas fa-calendar me-1"></i>Créé le: '.now()->format('d/m/Y H:i');
        $message .= '</small>';
        $message .= '</div>';
        $message .= '</div>';

        return $message;
    }

    /**
     * Extraire le nom du champ à partir du message d'erreur SQL
     */
    private function extractFieldName($errorMessage)
    {
        if (preg_match("/Field '([^']+)' doesn't have a default value/", $errorMessage, $matches)) {
            return $matches[1];
        }

        return 'inconnu';
    }

    /**
     * Vérifier si une chambre est occupée
     */
    private function isRoomOccupied($roomId, $checkIn, $checkOut)
    {
        return Transaction::where('room_id', $roomId)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out', [$checkIn, $checkOut])
                    ->orWhere(function ($q) use ($checkIn, $checkOut) {
                        $q->where('check_in', '<', $checkIn)
                            ->where('check_out', '>', $checkOut);
                    });
            })
            ->exists();
    }

    /**
     * Obtenir les IDs des chambres occupées
     */
    private function getOccupiedRoomID($stayFrom, $stayUntil)
    {
        return Transaction::where('status', '!=', 'cancelled')
            ->where(function ($query) use ($stayFrom, $stayUntil) {
                $query->where('check_in', '<', $stayUntil)
                    ->where('check_out', '>', $stayFrom);
            })
            ->pluck('room_id')
            ->unique();
    }

    /**
     * Rechercher un client par email (AJAX)
     */
    public function searchByEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $customers = Customer::where('email', $request->email)->get();

        if ($customers->isEmpty()) {
            return response()->json([
                'exists' => false,
                'message' => 'Aucun client trouvé avec cet email',
            ]);
        }

        $customerDetails = $customers->map(function ($customer) {
            return [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'reservation_count' => $customer->transactions()->count(),
            ];
        });

        return response()->json([
            'exists' => true,
            'customers' => $customerDetails,
            'message' => 'Trouvé '.$customers->count().' client(s) avec cet email',
        ]);
    }

    /**
     * Afficher les réservations d'un client
     */
    public function showCustomerReservations(Customer $customer)
    {
        $reservations = $customer->transactions()
            ->with(['room', 'room.type', 'payments'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('transaction.reservation.customer-reservations', [
            'customer' => $customer,
            'reservations' => $reservations,
        ]);
    }

    /**
     * Récupère les chambres occupées qui seront libérées aujourd'hui
     * Pour les afficher dans le formulaire de réservation
     */
    public function getRoomsBeingCheckedOutToday()
    {
        try {
            $today = Carbon::today();
            
            $checkoutsToday = Transaction::where('status', 'active')
                ->whereDate('check_out', $today)
                ->with(['room', 'room.type', 'customer'])
                ->orderBy('check_out_time', 'asc')
                ->get();
            
            $rooms = [];
            
            foreach ($checkoutsToday as $transaction) {
                $room = $transaction->room;
                if (!$room) continue;
                
                $checkoutTime = $transaction->check_out_time ?? '12:00';
                $checkoutTimeCarbon = Carbon::parse($checkoutTime);
                
                // Vérifier si le client actuel est toujours là
                $stillOccupied = $transaction->actual_check_out ? false : true;
                
                $rooms[] = [
                    'transaction_id' => $transaction->id,
                    'room' => $room,
                    'room_id' => $room->id,
                    'room_number' => $room->number,
                    'room_name' => $room->display_name,
                    'room_type' => $room->type->name ?? 'Standard',
                    'room_price' => $room->price,
                    'room_price_formatted' => $room->formatted_price,
                    'room_capacity' => $room->capacity,
                    'customer_id' => $transaction->customer_id,
                    'customer_name' => $transaction->customer->name ?? 'Inconnu',
                    'customer_phone' => $transaction->customer->phone ?? '',
                    'check_in' => $transaction->check_in->format('d/m/Y'),
                    'check_out' => $transaction->check_out->format('d/m/Y'),
                    'checkout_time' => $checkoutTime,
                    'checkout_time_formatted' => $checkoutTimeCarbon->format('H:i'),
                    'will_be_available_at' => $checkoutTimeCarbon->format('H:i'),
                    'minutes_until_available' => max(0, $checkoutTimeCarbon->diffInMinutes(now(), false)),
                    'is_available_now' => $checkoutTimeCarbon->lte(now()) && !$stillOccupied,
                    'still_occupied' => $stillOccupied,
                    'needs_cleaning' => $room->needsCleaning(),
                    'status_label' => $room->status_label,
                    'status_color' => $room->status_color,
                ];
            }
            
            // Séparer ceux qui sont déjà disponibles
            $availableNow = array_filter($rooms, fn($r) => $r['is_available_now']);
            $availableLater = array_filter($rooms, fn($r) => !$r['is_available_now']);
            
            return response()->json([
                'success' => true,
                'total' => count($rooms),
                'available_now' => count($availableNow),
                'available_later' => count($availableLater),
                'available_now_rooms' => array_values($availableNow),
                'available_later_rooms' => array_values($availableLater),
                'all_rooms' => $rooms,
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erreur récupération chambres à libérer:', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Vérifie si une chambre spécifique sera libérée aujourd'hui
     */
    public function checkRoomAvailabilityToday(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
        ]);
        
        $room = Room::find($request->room_id);
        
        // Vérifier si la chambre est occupée aujourd'hui
        $currentTransaction = Transaction::where('room_id', $room->id)
            ->where('status', 'active')
            ->whereDate('check_in', '<=', now())
            ->whereDate('check_out', '>=', now())
            ->first();
        
        if (!$currentTransaction) {
            return response()->json([
                'is_occupied' => false,
                'message' => 'Chambre disponible maintenant'
            ]);
        }
        
        // Chambre occupée, vérifier si elle part aujourd'hui
        $checkoutToday = $currentTransaction->check_out->isToday();
        
        if (!$checkoutToday) {
            return response()->json([
                'is_occupied' => true,
                'message' => 'Chambre occupée jusqu\'au ' . $currentTransaction->check_out->format('d/m/Y')
            ]);
        }
        
        // Elle part aujourd'hui
        $checkoutTime = $currentTransaction->check_out_time ?? '12:00';
        $checkoutTimeCarbon = Carbon::parse($checkoutTime);
        
        return response()->json([
            'is_occupied' => true,
            'is_checking_out_today' => true,
            'checkout_time' => $checkoutTime,
            'checkout_time_formatted' => $checkoutTimeCarbon->format('H:i'),
            'current_guest' => $currentTransaction->customer->name ?? 'Inconnu',
            'will_be_available_at' => $checkoutTimeCarbon->format('H:i'),
            'is_available_now' => $checkoutTimeCarbon->lte(now()),
            'minutes_until_available' => max(0, $checkoutTimeCarbon->diffInMinutes(now(), false)),
        ]);
    }

    /**
     * Crée une réservation en attente (pour chambre à libérer aujourd'hui)
     */
    public function createWaitingReservation(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'check_in_time' => 'nullable|date_format:H:i',
            'person_count' => 'required|integer|min:1',
            'downPayment' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|in:cash,card,mobile_money',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();
        $user = auth()->user();
        $room = Room::find($validated['room_id']);
        
        try {
            DB::beginTransaction();
            
            $checkIn = Carbon::parse($validated['check_in'])->setTime(12, 0, 0);
            $checkOut = Carbon::parse($validated['check_out'])->setTime(12, 0, 0);
            $days = $checkIn->diffInDays($checkOut);
            $totalPrice = $room->price * $days;
            
            // Créer la transaction avec statut "reserved_waiting"
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'customer_id' => $validated['customer_id'],
                'room_id' => $validated['room_id'],
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'check_in_time' => $validated['check_in_time'] ?? '14:00:00',
                'check_out_time' => '12:00:00',
                'status' => 'reserved_waiting',
                'person_count' => $validated['person_count'],
                'total_price' => $totalPrice,
                'total_payment' => $validated['downPayment'] ?? 0,
                'notes' => ($validated['notes'] ?? '') . ' | En attente du check-out du client actuel',
            ]);
            
            // Créer le paiement si acompte
            if (($validated['downPayment'] ?? 0) > 0) {
                Payment::create([
                    'user_id' => $user->id,
                    'transaction_id' => $transaction->id,
                    'amount' => $validated['downPayment'],
                    'payment_method' => $validated['payment_method'] ?? 'cash',
                    'reference' => 'WAIT-'.$transaction->id.'-'.time(),
                    'status' => 'completed',
                    'notes' => 'Acompte pour réservation en attente',
                ]);
            }
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Réservation en attente créée. Vous serez notifié quand la chambre sera disponible.',
                'transaction_id' => $transaction->id,
                'redirect' => route('transaction.show', $transaction)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur création réservation en attente:', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Affiche les chambres à libérer dans le formulaire de réservation
     */
    public function showAvailableRoomsWithCheckouts(Customer $customer, Request $request)
    {
        $stayFrom = $request->check_in ?? now()->format('Y-m-d');
        $stayUntil = $request->check_out ?? now()->addDays(1)->format('Y-m-d');
        
        // Chambres disponibles normalement
        $occupiedRoomId = $this->getOccupiedRoomID($stayFrom, $stayUntil);
        $availableRooms = $this->reservationRepository->getUnocuppiedroom(
            new Request(['check_in' => $stayFrom, 'check_out' => $stayUntil]), 
            $occupiedRoomId
        );
        
        // Chambres qui seront libérées aujourd'hui
        $checkoutsToday = Transaction::where('status', 'active')
            ->whereDate('check_out', Carbon::today())
            ->with(['room', 'customer'])
            ->get();
        
        $roomsBeingCheckedOut = [];
        foreach ($checkoutsToday as $checkout) {
            $room = $checkout->room;
            if (!$room) continue;
            
            $roomsBeingCheckedOut[] = [
                'room' => $room,
                'checkout_time' => $checkout->check_out_time ?? '12:00',
                'current_guest' => $checkout->customer->name ?? 'Inconnu',
                'will_be_available_at' => Carbon::parse($checkout->check_out_time ?? '12:00')->format('H:i'),
            ];
        }
        
        return view('transaction.reservation.choose-room-with-checkouts', [
            'customer' => $customer,
            'availableRooms' => $availableRooms,
            'roomsBeingCheckedOut' => $roomsBeingCheckedOut,
            'stayFrom' => $stayFrom,
            'stayUntil' => $stayUntil,
            'hasWaitingRooms' => count($roomsBeingCheckedOut) > 0,
        ]);
    }
}
