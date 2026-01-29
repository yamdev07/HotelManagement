<?php

namespace App\Http\Controllers;

use App\Events\NewReservationEvent;
use App\Events\RefreshDashboardEvent;
use App\Helpers\Helper;
use App\Http\Requests\ChooseRoomRequest;
use App\Models\Customer;
use App\Models\Room;
use App\Models\Transaction;
use App\Repositories\Interface\CustomerRepositoryInterface;
use App\Repositories\Interface\PaymentRepositoryInterface;
use App\Repositories\Interface\ReservationRepositoryInterface;
use App\Repositories\Interface\TransactionRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
            'info' => 'Same email can be used for multiple reservations. If customer exists, information will be updated.'
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
            
            $message = 'Informations client mises à jour : ' . $customer->name;
        } else {
            // Récupérer l'utilisateur connecté
            $user = auth()->user();
            if (!$user) {
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
            $message = 'Nouveau client créé par ' . $user->name . ' : ' . $customer->name;
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
        TransactionRepositoryInterface $transactionRepository = null,
        PaymentRepositoryInterface $paymentRepository = null
    ) {
        \Log::info('🚀 ============ DÉBUT RÉSERVATION ============');
        \Log::info('📋 Client: ' . $customer->id . ' - ' . $customer->name);
        \Log::info('🏨 Chambre: ' . $room->id . ' - ' . $room->number);
        \Log::info('📅 Dates: ' . ($request->check_in ?? 'N/A') . ' → ' . ($request->check_out ?? 'N/A'));
        \Log::info('💰 Acompte: ' . ($request->downPayment ?? 0) . ' FCFA');
        
        // ============ DEBUG COMPLET UTILISATEUR ============
        \Log::info('🔍 DEBUG UTILISATEUR CONNECTÉ:');
        \Log::info('auth()->check(): ' . (auth()->check() ? 'true' : 'false'));
        \Log::info('auth()->id(): ' . auth()->id());
        
        if (auth()->user()) {
            \Log::info('auth()->user():', [
                'id' => auth()->user()->id,
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'role' => auth()->user()->role,
            ]);
        } else {
            \Log::warning('⚠️ auth()->user() retourne NULL');
        }
        
        \Log::info('Session ID: ' . session()->getId());
        
        // ============ GESTION UTILISATEUR CONNECTÉ ============
        $user = auth()->user();
        $userId = null;
        
        if ($user) {
            $userId = $user->id;
            \Log::info('✅ Utilisateur connecté trouvé:');
            \Log::info('   ID: ' . $userId);
            \Log::info('   Nom: ' . $user->name);
            \Log::info('   Email: ' . $user->email);
            \Log::info('   Rôle: ' . $user->role);
        } else {
            \Log::warning('⚠️ Aucun utilisateur connecté, recherche d\'un admin...');
            
            // Rechercher un admin
            $admin = \App\Models\User::whereIn('role', ['Super', 'Admin'])->first();
            if ($admin) {
                $userId = $admin->id;
                $user = $admin;
                \Log::info('✅ Admin trouvé pour substitution:');
                \Log::info('   ID: ' . $userId);
                \Log::info('   Nom: ' . $admin->name);
                \Log::info('   Email: ' . $admin->email);
                \Log::info('   Rôle: ' . $admin->role);
            } else {
                // Prendre le premier utilisateur
                $firstUser = \App\Models\User::first();
                if ($firstUser) {
                    $userId = $firstUser->id;
                    $user = $firstUser;
                    \Log::info('✅ Premier utilisateur trouvé pour substitution:');
                    \Log::info('   ID: ' . $userId);
                    \Log::info('   Nom: ' . $firstUser->name);
                    \Log::info('   Email: ' . $firstUser->email);
                    \Log::info('   Rôle: ' . $firstUser->role);
                } else {
                    \Log::error('❌ AUCUN UTILISATEUR DANS LA BASE DE DONNÉES');
                    return redirect()->route('login')
                        ->with('error', 'Erreur système: Aucun utilisateur trouvé dans la base de données. Veuillez contacter l\'administrateur.');
                }
            }
        }
        
        // S'assurer que userId n'est jamais null
        if (!$userId) {
            \Log::error('❌ userId est NULL après toutes les vérifications');
            $userId = 1; // Fallback absolu
            \Log::info('🔧 userId forcé à: ' . $userId);
        }
        
        \Log::info('🔑 User ID final qui sera utilisé: ' . $userId);
        \Log::info('👤 Nom utilisateur final: ' . ($user->name ?? 'Inconnu'));
        
        try {
            // ============ VALIDATION ============
            \Log::info('🔵 Validation des données...');
            
            $validator = \Validator::make($request->all(), [
                'check_in' => 'required|date',
                'check_out' => 'required|date|after:check_in',
                'downPayment' => 'nullable|numeric|min:0',
                'person_count' => 'nullable|integer|min:1|max:' . $room->capacity,
                'payment_method' => 'nullable|string|in:cash,card,mobile_money',
            ], [
                'check_in.required' => 'La date d\'arrivée est obligatoire',
                'check_out.required' => 'La date de départ est obligatoire',
                'check_out.after' => 'La date de départ doit être après la date d\'arrivée',
                'person_count.max' => 'Le nombre de personnes ne peut pas dépasser la capacité de la chambre (' . $room->capacity . ')',
            ]);
            
            if ($validator->fails()) {
                \Log::error('❌ Validation échouée:', $validator->errors()->toArray());
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            
            $validated = $validator->validated();
            
            // ============ CALCULS ============
            \Log::info('🔵 Calcul des durées et prix...');
            
            $checkIn = Carbon::parse($validated['check_in']);
            $checkOut = Carbon::parse($validated['check_out']);
            $days = $checkIn->diffInDays($checkOut);
            if ($days == 0) $days = 1;
            
            $totalPrice = $room->price * $days;
            $downPayment = $validated['downPayment'] ?? 0;
            $personCount = $validated['person_count'] ?? 1;
            $paymentMethod = $validated['payment_method'] ?? 'cash';
            
            \Log::info("📊 Détails calculés:");
            \Log::info("   - Nuits: {$days} jour(s)");
            \Log::info("   - Personnes: {$personCount}");
            \Log::info("   - Prix total: " . number_format($totalPrice, 0, ',', ' ') . " FCFA");
            \Log::info("   - Acompte: " . number_format($downPayment, 0, ',', ' ') . " FCFA");
            \Log::info("   - Méthode de paiement: {$paymentMethod}");
            
            // Vérifier l'acompte
            if ($downPayment > $totalPrice) {
                \Log::warning("❌ Acompte trop élevé: {$downPayment} > {$totalPrice}");
                return redirect()->back()
                    ->with('error', 'L\'acompte ne peut pas dépasser le prix total')
                    ->withInput();
            }
            
            // ============ VÉRIFIER DISPONIBILITÉ ============
            \Log::info('🔵 Vérification disponibilité chambre...');
            $isOccupied = $this->isRoomOccupied($room->id, $checkIn, $checkOut);
            
            if ($isOccupied) {
                \Log::error('❌ Chambre déjà occupée pour ces dates');
                return redirect()->back()
                    ->with('error', 'Cette chambre n\'est plus disponible pour les dates sélectionnées. Veuillez choisir d\'autres dates ou une autre chambre.')
                    ->withInput();
            }
            
            // ============ CRÉATION DE LA TRANSACTION ============
            \Log::info('🔵 Création de la réservation...');
            
            DB::beginTransaction();
            
            try {
                // ============ VÉRIFIER/AJOUTER COLONNE NOTES ============
                \Log::info('🔧 Vérification colonne notes dans transactions...');
                try {
                    // Vérifier si la colonne notes existe
                    $columns = DB::select("SHOW COLUMNS FROM transactions LIKE 'notes'");
                    if (empty($columns)) {
                        DB::statement('ALTER TABLE transactions ADD COLUMN notes TEXT NULL');
                        \Log::info('✅ Colonne notes ajoutée à la table transactions');
                    } else {
                        \Log::info('✅ Colonne notes existe déjà');
                    }
                } catch (\Exception $e) {
                    \Log::warning('⚠️ Erreur vérification colonne notes: ' . $e->getMessage());
                }
                
                // ============ CRÉATION TRANSACTION ============
                \Log::info('🔵 Création de la transaction avec colonnes existantes...');
                
                // Données avec SEULEMENT les colonnes qui existent dans votre table transactions
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
                
                \Log::info('📋 Données transaction (colonnes existantes):', $transactionData);
                
                // Créer la transaction
                $transaction = null;
                if ($transactionRepository && method_exists($transactionRepository, 'store')) {
                    try {
                        $transaction = $transactionRepository->store($request, $customer, $room);
                        \Log::info('✅ Transaction créée via Repository - ID: ' . $transaction->id);
                    } catch (\Exception $e) {
                        \Log::warning('⚠️ Erreur avec repository, création directe: ' . $e->getMessage());
                        $transaction = Transaction::create($transactionData);
                        \Log::info('✅ Transaction créée directement (fallback) - ID: ' . $transaction->id);
                    }
                } else {
                    $transaction = Transaction::create($transactionData);
                    \Log::info('✅ Transaction créée directement - ID: ' . $transaction->id);
                }
                
                // Vérifier que la transaction a bien été créée
                if (!$transaction) {
                    throw new \Exception('Échec de la création de la transaction');
                }
                
                // ============ CRÉATION DU PAIEMENT (si acompte) ============
                if ($downPayment > 0) {
                    \Log::info("💰 Création du paiement: " . number_format($downPayment, 0, ',', ' ') . " FCFA");
                    
                    try {
                        $paymentData = [
                            'user_id' => $userId,
                            'transaction_id' => $transaction->id,
                            'amount' => $downPayment,
                            'payment_method' => $paymentMethod,
                            'reference' => 'PAY-' . $transaction->id . '-' . time(),
                            'status' => 'completed',
                            'notes' => sprintf(
                                'Acompte réservation | Agent: %s | Client: %s | Chambre: %s | Nuits: %d',
                                $user->name ?? 'Système',
                                $customer->name,
                                $room->number,
                                $days
                            ),
                        ];
                        
                        \Log::info('📋 Données paiement (colonnes existantes):', $paymentData);
                        
                        // Essayer avec le repository
                        if ($paymentRepository) {
                            try {
                                if (method_exists($paymentRepository, 'create')) {
                                    $payment = $paymentRepository->create($paymentData);
                                    \Log::info('✅ Paiement créé via create() - ID: ' . ($payment->id ?? 'N/A'));
                                } elseif (method_exists($paymentRepository, 'store')) {
                                    $mockRequest = new \Illuminate\Http\Request();
                                    $mockRequest->merge([
                                        'amount' => $downPayment,
                                        'payment_method' => $paymentMethod,
                                        'notes' => 'Acompte réservation',
                                        'reference' => $paymentData['reference'],
                                    ]);
                                    $payment = $paymentRepository->store($mockRequest, $transaction, 'Acompte');
                                    \Log::info('✅ Paiement créé via store() - ID: ' . ($payment->id ?? 'N/A'));
                                } else {
                                    $payment = \App\Models\Payment::create($paymentData);
                                    \Log::info('✅ Paiement créé directement (repository non fonctionnel) - ID: ' . $payment->id);
                                }
                            } catch (\Exception $repoError) {
                                \Log::warning('⚠️ Erreur avec repository, création directe: ' . $repoError->getMessage());
                                $payment = \App\Models\Payment::create($paymentData);
                                \Log::info('✅ Paiement créé directement (fallback) - ID: ' . $payment->id);
                            }
                        } else {
                            $payment = \App\Models\Payment::create($paymentData);
                            \Log::info('✅ Paiement créé directement (sans repo) - ID: ' . $payment->id);
                        }
                        
                    } catch (\Exception $e) {
                        \Log::warning('⚠️ Erreur création paiement: ' . $e->getMessage());
                        \Log::warning('⚠️ Détail: ' . $e->getFile() . ':' . $e->getLine());
                        // Continuer même si le paiement échoue - la réservation est déjà créée
                    }
                }
                
                // ============ MISE À JOUR STATUT CHAMBRE ============
                try {
                    $roomColumns = DB::select("SHOW COLUMNS FROM rooms LIKE 'room_status_id'");
                    if (!empty($roomColumns)) {
                        $room->update(['room_status_id' => 2]); // 2 = Réservée
                        \Log::info('✅ Statut chambre mis à jour: Réservée');
                    } else {
                        \Log::info('ℹ️ Colonne room_status_id non trouvée dans la table rooms');
                    }
                } catch (\Exception $e) {
                    \Log::warning('⚠️ Erreur mise à jour statut chambre: ' . $e->getMessage());
                }
                
                // ============ ÉVÉNEMENTS ============
                try {
                    if (class_exists(NewReservationEvent::class)) {
                        event(new NewReservationEvent($transaction, $user->name ?? 'Système'));
                        \Log::info('✅ Événement NewReservationEvent envoyé');
                    }
                    if (class_exists(RefreshDashboardEvent::class)) {
                        event(new RefreshDashboardEvent());
                        \Log::info('✅ Événement RefreshDashboardEvent envoyé');
                    }
                } catch (\Exception $e) {
                    \Log::warning('⚠️ Erreur lors de l\'envoi des événements: ' . $e->getMessage());
                }
                
                // ============ CONFIRMATION ============
                DB::commit();
                \Log::info('✅ Transaction BDD confirmée avec succès');
                
                // ============ MESSAGE DE SUCCÈS PERSONNALISÉ ============
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
                
                \Log::info('🎊 RÉSERVATION RÉUSSIE par ' . ($user->name ?? 'Système') . ' - ID: ' . $transaction->id);
                \Log::info('🚀 ============ FIN PROCESSUS RÉSERVATION ============');
                
                // ============ REDIRECTION ============
                return redirect()->route('transaction.show', $transaction)
                    ->with('success', $successMessage)
                    ->with('transaction_id', $transaction->id)
                    ->with('agent_name', $user->name ?? 'Système');
                    
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('❌ Erreur pendant la transaction BDD: ' . $e->getMessage());
                \Log::error('❌ Stack trace: ' . $e->getTraceAsString());
                
                if ($e instanceof \Illuminate\Database\QueryException) {
                    \Log::error('❌ SQL Error Code: ' . $e->getCode());
                    \Log::error('❌ SQL Error Message: ' . $e->getMessage());
                    \Log::error('❌ SQL Query: ' . $e->getSql());
                    \Log::error('❌ SQL Bindings: ' . json_encode($e->getBindings()));
                    
                    if (strpos($e->getMessage(), 'Column not found') !== false) {
                        preg_match("/Column not found.*'([^']+)'/", $e->getMessage(), $matches);
                        $column = $matches[1] ?? 'inconnue';
                        \Log::error('❌ Colonne manquante: ' . $column);
                    }
                }
                
                return redirect()->back()
                    ->with('error', 'Erreur lors de la création de la réservation: ' . $e->getMessage())
                    ->withInput();
            }
            
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('❌ Erreur base de données: ' . $e->getMessage());
            \Log::error('❌ SQL Error Code: ' . $e->getCode());
            \Log::error('❌ SQL Query: ' . $e->getSql());
            \Log::error('❌ SQL Bindings: ' . json_encode($e->getBindings()));
            
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
            \Log::error('❌ Erreur générale réservation: ' . $e->getMessage());
            \Log::error('❌ Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Erreur lors de la réservation: ' . $e->getMessage())
                ->withInput();
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
        $message .= '<p class="mb-0"><small>Réservée par <strong>' . $user->name . '</strong></small></p>';
        $message .= '</div>';
        $message .= '</div>';
        
        $message .= '<div class="row">';
        $message .= '<div class="col-md-6">';
        $message .= '<p><strong><i class="fas fa-user me-2"></i>Client:</strong> ' . $customer->name . '</p>';
        $message .= '<p><strong><i class="fas fa-bed me-2"></i>Chambre:</strong> ' . $room->number . ' (' . ($room->type->name ?? 'Standard') . ')</p>';
        $message .= '<p><strong><i class="fas fa-calendar-alt me-2"></i>Période:</strong> ' . $checkIn->format('d/m/Y') . ' → ' . $checkOut->format('d/m/Y') . '</p>';
        $message .= '<p><strong><i class="fas fa-moon me-2"></i>Durée:</strong> ' . $days . ' nuit' . ($days > 1 ? 's' : '') . '</p>';
        $message .= '</div>';
        
        $message .= '<div class="col-md-6">';
        $message .= '<p><strong><i class="fas fa-receipt me-2"></i>Prix total:</strong> ' . number_format($totalPrice, 0, ',', ' ') . ' FCFA</p>';
        
        if ($downPayment > 0) {
            $remaining = $totalPrice - $downPayment;
            $message .= '<p class="text-success"><strong><i class="fas fa-money-bill-wave me-2"></i>Acompte payé:</strong> ' . number_format($downPayment, 0, ',', ' ') . ' FCFA</p>';
            if ($remaining > 0) {
                $message .= '<p class="text-warning"><strong><i class="fas fa-balance-scale me-2"></i>Solde à régler:</strong> ' . number_format($remaining, 0, ',', ' ') . ' FCFA</p>';
            } else {
                $message .= '<p class="text-success"><strong><i class="fas fa-check-double me-2"></i>✅ Paiement complet</strong></p>';
            }
        } else {
            $message .= '<p class="text-info"><strong><i class="fas fa-clock me-2"></i>À régler à l\'arrivée:</strong> ' . number_format($totalPrice, 0, ',', ' ') . ' FCFA</p>';
        }
        
        $message .= '</div>';
        $message .= '</div>';
        
        $message .= '<hr class="my-3">';
        $message .= '<div class="text-center">';
        $message .= '<small class="text-muted">';
        $message .= '<i class="fas fa-hashtag me-1"></i>Référence: #TRX-' . $transaction->id . ' | ';
        $message .= '<i class="fas fa-user-circle me-1"></i>Agent: ' . $user->name . ' | ';
        $message .= '<i class="fas fa-calendar me-1"></i>Créé le: ' . now()->format('d/m/Y H:i');
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
            ->where(function($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in', [$checkIn, $checkOut])
                      ->orWhereBetween('check_out', [$checkIn, $checkOut])
                      ->orWhere(function($q) use ($checkIn, $checkOut) {
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
        return Transaction::where(function($query) use ($stayFrom, $stayUntil) {
                $query->where([['check_in', '<=', $stayFrom], ['check_out', '>=', $stayUntil]])
                      ->orWhere([['check_in', '>=', $stayFrom], ['check_in', '<=', $stayUntil]])
                      ->orWhere([['check_out', '>=', $stayFrom], ['check_out', '<=', $stayUntil]]);
            })
            ->where('status', '!=', 'cancelled')
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
                'message' => 'Aucun client trouvé avec cet email'
            ]);
        }
        
        $customerDetails = $customers->map(function($customer) {
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
            'message' => 'Trouvé ' . $customers->count() . ' client(s) avec cet email'
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
}