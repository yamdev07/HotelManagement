<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\History;
use App\Models\Payment;
use App\Models\ReceptionistAction;
use App\Models\ReceptionistSession;
use App\Models\Transaction;
use App\Models\Room;
use App\Repositories\Interface\TransactionRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;

class TransactionController extends Controller
{
    // Constantes pour les statuts des chambres (doivent correspondre à votre DB)
    const STATUS_AVAILABLE = 1;   // Disponible
    const STATUS_OCCUPIED = 2;    // Occupée
    const STATUS_MAINTENANCE = 3; // Maintenance
    const STATUS_RESERVED = 4;    // Réservée
    const STATUS_CLEANING = 5;    // En nettoyage
    const STATUS_DIRTY = 6;       // 👈 SALE / À NETTOYER

    public function __construct(
        private TransactionRepositoryInterface $transactionRepository
    ) {}

    /**
     * Afficher la liste des transactions
     */
    public function index(Request $request)
    {
        $transactions = $this->transactionRepository->getTransaction($request);
        $transactionsExpired = $this->transactionRepository->getTransactionExpired($request);

        return view('transaction.index', [
            'transactions' => $transactions,
            'transactionsExpired' => $transactionsExpired,
        ]);
    }

    /**
     * Afficher le formulaire de création d'une transaction
     */
    public function create()
    {
        return redirect()->route('transaction.reservation.createIdentity');
    }

    /**
     * Enregistrer une nouvelle transaction
     */
    public function store(Request $request)
    {
        return redirect()->route('transaction.index');
    }

    /**
     * Afficher les détails d'une transaction
     */
    public function show(Transaction $transaction)
    {
        try {
            $payments = $transaction->payments()->orderBy('created_at', 'desc')->get();
        } catch (\Exception $e) {
            $payments = collect([]);
            Log::error('Erreur récupération paiements:', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
            ]);
        }

        $checkIn = Carbon::parse($transaction->check_in);
        $checkOut = Carbon::parse($transaction->check_out);
        $nights = $checkIn->diffInDays($checkOut);

        $totalPrice = $transaction->getTotalPrice();
        $totalPayment = $transaction->getTotalPayment();
        $remaining = $totalPrice - $totalPayment;
        $isFullyPaid = $remaining <= 0;

        $status = $transaction->status;
        $isExpired = $checkOut->isPast();

        $canCancel = $this->canCancelReservation($transaction);

        $transaction->load(['customer.user', 'room.type', 'user']);

        return view('transaction.show', compact(
            'transaction', 'payments', 'nights', 'totalPrice',
            'totalPayment', 'remaining', 'isExpired', 'isFullyPaid',
            'status', 'canCancel'
        ));
    }

    /**
     * Afficher le formulaire d'édition d'une transaction
     */
    public function edit(Transaction $transaction)
    {
        if (! $this->hasPermission(['Super', 'Admin', 'Receptionist'])) {
            abort(403, 'Accès non autorisé.');
        }

        $checkOutDate = Carbon::parse($transaction->check_out);
        $isExpired = $checkOutDate->isPast();

        if ($isExpired || in_array($transaction->status, ['cancelled', 'completed', 'no_show'])) {
            return redirect()->route('transaction.show', $transaction)
                ->with('error', 'Impossible de modifier une réservation terminée, annulée ou no show.');
        }

        $transaction->load(['customer.user', 'room.type', 'room.roomStatus']);

        // ============ NOUVEAU : Récupérer les chambres disponibles ============
        $currentRoomId = $transaction->room_id;
        $checkIn = $transaction->check_in;
        $checkOut = $transaction->check_out;

        // Chambres occupées pour la période
        $occupiedRoomIds = Transaction::whereNotIn('status', ['cancelled', 'completed', 'no_show'])
            ->where('id', '!=', $transaction->id) // Exclure la réservation actuelle
            ->where(function($query) use ($checkIn, $checkOut) {
                $query->where(function($q) use ($checkIn, $checkOut) {
                    $q->where('check_in', '<', $checkOut)
                    ->where('check_out', '>', $checkIn);
                });
            })
            ->pluck('room_id')
            ->toArray();

        // Toutes les chambres
        $allRooms = Room::with('type', 'roomStatus')->get();

        // Séparer les chambres disponibles et occupées
        $availableRooms = $allRooms->filter(function($room) use ($occupiedRoomIds, $currentRoomId) {
            return !in_array($room->id, $occupiedRoomIds) || $room->id == $currentRoomId;
        });

        $occupiedRooms = $allRooms->filter(function($room) use ($occupiedRoomIds, $currentRoomId) {
            return in_array($room->id, $occupiedRoomIds) && $room->id != $currentRoomId;
        });

        return view('transaction.edit', compact(
            'transaction', 
            'availableRooms', 
            'occupiedRooms',
            'currentRoomId'
        ));
    }

    /**
     * Mettre à jour une transaction existante
     */
    public function update(Request $request, Transaction $transaction)
    {
        if (! $this->hasPermission(['Super', 'Admin', 'Receptionist'])) {
            abort(403, 'Accès non autorisé');
        }

        if (! $this->canModifyTransaction($transaction)) {
            return redirect()->route('transaction.show', $transaction)
                ->with('error', 'Cette réservation ne peut plus être modifiée.');
        }

        // ============ NOUVELLE VALIDATION AVEC room_id ============
        $validator = Validator::make($request->all(), [
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'room_id' => 'required|exists:rooms,id',  // ← NOUVEAU
            'notes' => 'nullable|string|max:500',
        ], [
            'check_in_date.required' => 'La date d\'arrivée est requise',
            'check_out_date.required' => 'La date de départ est requise',
            'check_out_date.after' => 'La date de départ doit être après la date d\'arrivée',
            'room_id.required' => 'La chambre est requise',
            'room_id.exists' => 'La chambre sélectionnée n\'existe pas',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // ============ FORCER LES HEURES À 12h ============
        $checkIn = Carbon::parse($request->check_in_date)->setTime(12, 0, 0);
        $checkOut = Carbon::parse($request->check_out_date)->setTime(12, 0, 0);

        // ============ VÉRIFIER DISPONIBILITÉ DE LA NOUVELLE CHAMBRE ============
        if (! $this->isRoomAvailable($request->room_id, $checkIn, $checkOut, $transaction->id)) {
            return redirect()->back()
                ->with('error', 'Cette chambre n\'est pas disponible pour les dates sélectionnées.')
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // ============ ÉTAT AVANT MODIFICATION ============
            $beforeState = [
                'room_id' => $transaction->room_id,
                'room_number' => $transaction->room->number,
                'check_in' => $transaction->check_in->format('Y-m-d H:i:s'),
                'check_out' => $transaction->check_out->format('Y-m-d H:i:s'),
                'total_price' => $transaction->total_price,
                'notes' => $transaction->notes,
            ];

            $oldCheckIn = Carbon::parse($transaction->check_in);
            $oldCheckOut = Carbon::parse($transaction->check_out);
            $oldNights = $oldCheckIn->diffInDays($oldCheckOut);
            $oldRoomId = $transaction->room_id;
            $oldRoomPrice = $transaction->room->price;
            $oldTotalPrice = $transaction->total_price;

            // ============ NOUVELLE CHAMBRE ============
            $newRoom = Room::find($request->room_id);
            $newRoomPrice = $newRoom->price;
            
            // ============ RECALCUL DU PRIX ============
            $newNights = $checkIn->diffInDays($checkOut);
            $newTotalPrice = $newRoomPrice * $newNights;

            // ============ MISE À JOUR ============
            $transaction->update([
                'room_id' => $request->room_id,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'total_price' => $newTotalPrice,
                'notes' => $request->notes ?? $transaction->notes,
            ]);

            $transaction->refresh();

            // ============ HISTORIQUE ============
            $changes = [];
            $description = 'Modification de la réservation';
            
            if ($oldRoomId != $request->room_id) {
                $changes[] = 'chambre: ' . $transaction->room->number . ' → ' . $newRoom->number;
            }
            if ($oldCheckIn->format('Y-m-d') != $checkIn->format('Y-m-d')) {
                $changes[] = 'arrivée: ' . $oldCheckIn->format('d/m/Y') . ' → ' . $checkIn->format('d/m/Y');
            }
            if ($oldCheckOut->format('Y-m-d') != $checkOut->format('Y-m-d')) {
                $changes[] = 'départ: ' . $oldCheckOut->format('d/m/Y') . ' → ' . $checkOut->format('d/m/Y');
            }
            
            if (!empty($changes)) {
                $description = 'Modification: ' . implode(', ', $changes);
            }

            History::create([
                'transaction_id' => $transaction->id,
                'user_id' => auth()->id(),
                'action' => 'update',
                'description' => $description,
                'old_values' => json_encode($beforeState),
                'new_values' => json_encode([
                    'room_id' => $request->room_id,
                    'room_number' => $newRoom->number,
                    'check_in' => $checkIn->format('Y-m-d H:i:s'),
                    'check_out' => $checkOut->format('Y-m-d H:i:s'),
                    'total_price' => $newTotalPrice,
                    'nights' => $newNights,
                    'room_price_per_night' => $newRoomPrice,
                    'notes' => $transaction->notes,
                ]),
                'notes' => $request->notes,
            ]);

            // ============ LOG RÉCEPTIONNISTE ============
            if (auth()->user()->role === 'Receptionist') {
                $this->logReceptionistAction(
                    actionType: 'reservation',
                    actionSubtype: 'update',
                    actionable: $transaction,
                    actionData: [
                        'old' => [
                            'room' => $transaction->room->number,
                            'check_in' => $oldCheckIn->format('d/m/Y'),
                            'check_out' => $oldCheckOut->format('d/m/Y'),
                            'nights' => $oldNights,
                            'price' => $oldTotalPrice,
                        ],
                        'new' => [
                            'room' => $newRoom->number,
                            'check_in' => $checkIn->format('d/m/Y'),
                            'check_out' => $checkOut->format('d/m/Y'),
                            'nights' => $newNights,
                            'price' => $newTotalPrice,
                        ],
                    ],
                    beforeState: $beforeState,
                    afterState: [
                        'room_id' => $request->room_id,
                        'check_in' => $checkIn->format('Y-m-d H:i:s'),
                        'check_out' => $checkOut->format('Y-m-d H:i:s'),
                        'total_price' => $newTotalPrice,
                        'notes' => $transaction->notes,
                    ],
                    notes: 'Modification réservation'
                );
            }

            DB::commit();

            // ============ MESSAGE DE SUCCÈS ============
            $priceChange = $newTotalPrice - $oldTotalPrice;
            $message = "✅ Réservation #{$transaction->id} mise à jour avec succès.<br>";
            
            if ($oldRoomId != $request->room_id) {
                $message .= "<strong>Chambre:</strong> {$transaction->room->number} → {$newRoom->number}<br>";
            }
            
            $message .= '<strong>Anciennes dates:</strong> ' .
                    $oldCheckIn->format('d/m/Y') . ' → ' . $oldCheckOut->format('d/m/Y') .
                    " ({$oldNights} nuit(s))<br>";
            $message .= '<strong>Nouvelles dates:</strong> ' .
                    $checkIn->format('d/m/Y') . ' → ' . $checkOut->format('d/m/Y') .
                    " ({$newNights} nuit(s))<br>";
            $message .= '<strong>Ancien total:</strong> ' .
                    number_format($oldTotalPrice, 0, ',', ' ') . ' CFA<br>';
            $message .= '<strong>Nouveau total:</strong> ' .
                    number_format($newTotalPrice, 0, ',', ' ') . ' CFA<br>';

            if ($priceChange != 0) {
                $changeType = $priceChange > 0 ? 'majoration' : 'réduction';
                $message .= "<strong>{$changeType}:</strong> " .
                        ($priceChange > 0 ? '+' : '') .
                        number_format($priceChange, 0, ',', ' ') . ' CFA<br>';

                if ($priceChange < 0) {
                    $message .= "<div class='alert alert-warning mt-2'>⚠️ Le prix a diminué. Vérifiez les paiements.</div>";
                }
            }

            return redirect()->route('transaction.show', $transaction)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur modification transaction:', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id,
            ]);

            return redirect()->back()
                ->with('error', 'Erreur lors de la modification: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer une transaction
     */
    public function destroy(Transaction $transaction)
    {
        try {
            if (! in_array(auth()->user()->role, ['Super'])) {
                abort(403, 'Accès non autorisé. Seuls les Super Admins peuvent supprimer.');
            }

            $transactionId = $transaction->id;
            $customerName = $transaction->customer->name;

            DB::beginTransaction();

            $deletedData = [
                'transaction' => $transaction->toArray(),
                'payments' => $transaction->payments->toArray(),
                'deleted_by' => auth()->id(),
                'deleted_at' => now()->format('Y-m-d H:i:s'),
            ];

            Payment::where('transaction_id', $transaction->id)->delete();
            $transaction->delete();

            $room = $transaction->room;
            if ($room && $room->room_status_id == 2) {
                $otherTransactions = Transaction::where('room_id', $room->id)
                    ->where('id', '!=', $transactionId)
                    ->where('check_out', '>', now())
                    ->exists();

                if (! $otherTransactions) {
                    $room->update(['room_status_id' => 1]);
                }
            }

            DB::commit();

            Log::warning('Transaction supprimée définitivement', $deletedData);

            return redirect()->route('transaction.index')
                ->with('success', "Réservation #{$transactionId} pour {$customerName} supprimée définitivement.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur suppression transaction:', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id,
            ]);

            return redirect()->route('transaction.index')
                ->with('error', 'Erreur lors de la suppression: '.$e->getMessage());
        }
    }

    /**
     * =====================================================
     * ✅ MÉTHODE PRINCIPALE : MISE À JOUR DU STATUT
     * =====================================================
     */
    /**
     * Mettre à jour le statut d'une transaction
     */
    public function updateStatus(Request $request, Transaction $transaction)
    {
        if (! $this->hasPermission(['Super', 'Admin', 'Receptionist'])) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Accès non autorisé'], 403);
            }
            abort(403, 'Accès non autorisé.');
        }

        $request->validate([
            'status' => 'required|in:reservation,active,completed,cancelled,no_show',
            'cancel_reason' => 'nullable|string|max:500',
            'user_role' => 'nullable|string',
        ]);

        $oldStatus = $transaction->status;
        $newStatus = $request->status;

        // =====================================================
        // VÉRIFICATION DES HEURES MÉTIER (12h - 14h)
        // =====================================================
        $now = Carbon::now();
        $checkInDay = Carbon::parse($transaction->check_in)->startOfDay(); 
        $checkOutDay = Carbon::parse($transaction->check_out)->startOfDay(); 

        // Heures métier
        $checkInTime = $checkInDay->copy()->setTime(12, 0, 0);   
        $checkOutDeadline = $checkOutDay->copy()->setTime(12, 0, 0); 
        $checkOutLargess = $checkOutDay->copy()->setTime(14, 0, 0);   

        // --- Vérification pour le passage en "active" (arrivée) ---
        if ($newStatus === 'active') {
            // Vérifier qu'on est bien le jour de l'arrivée
            if (!$now->isSameDay($checkInDay)) {
                $errorMsg = "❌ L'arrivée ne peut être marquée que le jour prévu (" . $checkInDay->format('d/m/Y') . ").";
                
                if ($request->ajax()) {
                    return response()->json(['error' => $errorMsg], 422);
                }
                return redirect()->back()->with('error', $errorMsg);
            }

            // Vérifier qu'on est après 12h
            if ($now->lt($checkInTime)) {
                $minutes = $now->diffInMinutes($checkInTime, false);
                $heures = floor($minutes / 60);
                $minutesRestantes = $minutes % 60;
                
                $errorMsg = sprintf(
                    "⏳ Check-in possible à partir de 12h. Encore %d heures et %d minutes à attendre.",
                    $heures,
                    $minutesRestantes
                );
                
                if ($request->ajax()) {
                    return response()->json(['error' => $errorMsg], 422);
                }
                return redirect()->back()->with('error', $errorMsg);
            }
            
            // Après 12h, autorisé
            Log::info("✅ Arrivée autorisée à " . $now->format('H:i') . " pour la transaction #" . $transaction->id);
        }

        // --- Vérification pour le passage en "completed" (départ) ---
        if ($newStatus === 'completed') {
            // Vérifier qu'on est bien le jour du départ
            if (!$now->isSameDay($checkOutDay)) {
                $errorMsg = "❌ Le départ ne peut être marqué que le jour prévu (" . $checkOutDay->format('d/m/Y') . "). " .
                        "Si le client est encore là, veuillez prolonger le séjour.";
                
                if ($request->ajax()) {
                    return response()->json(['error' => $errorMsg], 422);
                }
                return redirect()->back()->with('error', $errorMsg);
            }

            // Après 14h : trop tard, doit prolonger
            if ($now->gt($checkOutLargess)) {
                $errorMsg = "⚠️ Départ après 14h. La largesse de 2h est dépassée. " .
                        "Veuillez prolonger le séjour d'une nuit supplémentaire.";
                
                if ($request->ajax()) {
                    return response()->json(['error' => $errorMsg, 'require_extension' => true], 422);
                }
                return redirect()->back()->with('error', $errorMsg);
            }

            // Entre 12h et 14h : largesse accordée (on loggue)
            if ($now->gte($checkOutDeadline) && $now->lte($checkOutLargess)) {
                Log::info("✅ Largesse accordée - Départ entre 12h et 14h", [
                    'transaction_id' => $transaction->id,
                    'heure_depart' => $now->format('H:i'),
                    'client' => $transaction->customer->name,
                    'chambre' => $transaction->room->number ?? 'N/A'
                ]);
            }
            
            // Avant 12h : trop tôt
            if ($now->lt($checkOutDeadline)) {
                $minutes = $now->diffInMinutes($checkOutDeadline, false);
                
                $errorMsg = sprintf(
                    "⏳ Check-out possible à partir de 12h. Encore %d minutes à attendre.",
                    ceil($minutes)
                );
                
                if ($request->ajax()) {
                    return response()->json(['error' => $errorMsg], 422);
                }
                return redirect()->back()->with('error', $errorMsg);
            }
        }

        // Vérification paiement pour "completed"
        if ($newStatus === 'completed' && ! $transaction->isFullyPaid()) {
            $remaining = $transaction->getRemainingPayment();
            $formattedRemaining = number_format($remaining, 0, ',', ' ') . ' CFA';

            if ($request->ajax()) {
                return response()->json([
                    'error' => 'Paiement incomplet',
                    'message' => 'Impossible de marquer comme terminé. Solde restant: ' . $formattedRemaining,
                    'remaining' => $remaining,
                ], 422);
            }

            return redirect()->back()->with('error',
                "❌ Paiement incomplet ! Solde restant: " . $formattedRemaining
            );
        }

        // Vérification pour retour à "reservation"
        if ($newStatus === 'reservation' && Carbon::parse($transaction->check_in)->isPast()) {
            $errorMsg = 'Impossible de revenir à "Réservation", la date d\'arrivée est passée.';

            if ($request->ajax()) {
                return response()->json(['error' => $errorMsg], 422);
            }

            return redirect()->back()->with('error', $errorMsg);
        }

        // Vérification raison pour annulation
        if ($newStatus === 'cancelled' && empty($request->cancel_reason)) {
            $errorMsg = 'Une raison est obligatoire pour l\'annulation.';

            if ($request->ajax()) {
                return response()->json(['error' => $errorMsg], 422);
            }

            return redirect()->back()->with('error', $errorMsg);
        }

        try {
            DB::beginTransaction();

            $beforeState = $this->getTransactionState($transaction);
            $updateData = ['status' => $newStatus];

            switch ($newStatus) {
                case 'active':
                    $updateData['check_in_actual'] = now();

                    if ($transaction->room) {
                        $transaction->room->update(['room_status_id' => self::STATUS_OCCUPIED]);
                        Log::info("Arrivée: Chambre {$transaction->room->number} marquée OCCUPÉE");

                        if (auth()->user()->role === 'Receptionist') {
                            $this->logReceptionistAction(
                                actionType: 'checkin',
                                actionSubtype: 'create',
                                actionable: $transaction,
                                actionData: [
                                    'check_in_actual' => now()->format('Y-m-d H:i:s'),
                                    'room_number' => $transaction->room->number,
                                    'customer_name' => $transaction->customer->name,
                                    'room_status' => 'occupied',
                                ],
                                beforeState: $beforeState,
                                afterState: $this->getTransactionState($transaction, true),
                                notes: 'Client marqué comme arrivé à l\'hôtel'
                            );
                        }
                    }
                    break;

                case 'completed':
                    if (! $transaction->isFullyPaid()) {
                        DB::rollBack();
                        $remaining = $transaction->getRemainingPayment();
                        $formattedRemaining = number_format($remaining, 0, ',', ' ') . ' CFA';

                        return redirect()->back()->with('error',
                            "Erreur de sécurité: Paiement incomplet. Solde: " . $formattedRemaining);
                    }

                    $updateData['check_out_actual'] = now();

                    // =====================================================
                    // Marquer la chambre comme DIRTY (SALE)
                    // =====================================================
                    if ($transaction->room) {
                        $transaction->room->update([
                            'room_status_id' => self::STATUS_DIRTY, 
                            'needs_cleaning' => 1,
                            'updated_at' => now(),
                        ]);

                        Log::info("✅ DÉPART: Chambre {$transaction->room->number} marquée DIRTY");

                        if (auth()->user()->role === 'Receptionist') {
                            $this->logReceptionistAction(
                                actionType: 'checkout',
                                actionSubtype: 'create',
                                actionable: $transaction,
                                actionData: [
                                    'check_out_actual' => now()->format('Y-m-d H:i:s'),
                                    'room_number' => $transaction->room->number,
                                    'total_paid' => $transaction->getTotalPayment(),
                                    'payment_status' => 'complet',
                                    'room_status' => 'dirty',
                                    'departure_time' => now()->format('H:i'),
                                    'within_largess' => (now()->gte($checkOutDeadline) && now()->lte($checkOutLargess)) ? 'yes' : 'no'
                                ],
                                beforeState: $beforeState,
                                afterState: $this->getTransactionState($transaction, true),
                                notes: 'Client marqué comme parti - Chambre marquée À NETTOYER'
                            );
                        }
                    }
                    break;

                case 'cancelled':
                    $updateData['cancelled_at'] = now();
                    $updateData['cancelled_by'] = auth()->id();
                    $updateData['cancel_reason'] = $request->cancel_reason;

                    if ($transaction->room && $transaction->room->room_status_id == self::STATUS_OCCUPIED) {
                        $transaction->room->update(['room_status_id' => self::STATUS_AVAILABLE]);
                        Log::info("Annulation: Chambre {$transaction->room->number} libérée");
                    }

                    $totalPaid = $transaction->getTotalPayment();
                    if ($totalPaid > 0) {
                        Payment::create([
                            'transaction_id' => $transaction->id,
                            'price' => -$totalPaid,
                            'payment_method' => 'refund',
                            'reference' => 'REFUND-' . $transaction->id . '-' . time(),
                            'status' => 'completed',
                            'notes' => 'Remboursement annulation' .
                                    ($request->cancel_reason ? ": " . $request->cancel_reason : ''),
                            'created_by' => auth()->id(),
                        ]);
                    }

                    if (auth()->user()->role === 'Receptionist') {
                        $this->logReceptionistAction(
                            actionType: 'reservation',
                            actionSubtype: 'cancel',
                            actionable: $transaction,
                            actionData: [
                                'cancel_reason' => $request->cancel_reason,
                                'refund_amount' => $totalPaid,
                                'cancelled_by' => auth()->user()->name,
                            ],
                            beforeState: $beforeState,
                            afterState: $this->getTransactionState($transaction, true),
                            notes: 'Réservation annulée'
                        );
                    }
                    break;
            }

            $transaction->update($updateData);
            DB::commit();

            Log::info('Statut transaction modifié', [
                'transaction_id' => $transaction->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'changed_by' => auth()->id(),
                'customer' => $transaction->customer->name,
                'room' => $transaction->room->number ?? 'N/A',
            ]);

            $message = $this->getStatusChangeMessage($oldStatus, $newStatus);

            if ($newStatus === 'completed') {
                $largessMessage = "";
                if ($now->gte($checkOutDeadline) && $now->lte($checkOutLargess)) {
                    $largessMessage = " (largesse de 2h accordée)";
                }
                
                session()->flash('departure_success', [
                    'title' => '✅ Départ enregistré - Chambre à nettoyer',
                    'message' => 'Client marqué comme parti' . $largessMessage . '. Chambre marquée "À NETTOYER". Housekeeping informé.',
                    'transaction_id' => $transaction->id,
                    'room_number' => $transaction->room->number ?? 'N/A',
                    'customer_name' => $transaction->customer->name,
                ]);
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'new_status' => $newStatus,
                    'new_status_label' => $this->getStatusLabel($newStatus),
                ]);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur mise à jour statut:', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id,
            ]);

            $errorMsg = 'Erreur lors de la mise à jour du statut';

            if ($request->ajax()) {
                return response()->json(['error' => $errorMsg], 500);
            }

            return redirect()->back()->with('error', $errorMsg);
        }
    }

    /**
     * ACTION RAPIDE : MARQUER COMME ARRIVÉ
     */
    public function markAsArrived(Transaction $transaction)
    {
        if (! $this->hasPermission(['Super', 'Admin', 'Receptionist'])) {
            abort(403, 'Accès non autorisé');
        }

        if ($transaction->status !== 'reservation') {
            return redirect()->back()->with('error',
                'Seule une réservation peut être marquée comme arrivée.');
        }

        // =====================================================
        // VÉRIFICATION DES HEURES MÉTIER (12h)
        // =====================================================
        $now = Carbon::now();
        $checkInDay = Carbon::parse($transaction->check_in)->startOfDay();
        $checkInTime = $checkInDay->copy()->setTime(12, 0, 0);

        // Vérifier qu'on est bien le jour de l'arrivée
        if (!$now->isSameDay($checkInDay)) {
            return redirect()->back()->with('error',
                "❌ L'arrivée ne peut être marquée que le jour prévu (" . $checkInDay->format('d/m/Y') . ").");
        }

        // Vérifier qu'on est après 12h
        if ($now->lt($checkInTime)) {
            $minutes = $now->diffInMinutes($checkInTime, false);
            $heures = floor($minutes / 60);
            $minutesRestantes = $minutes % 60;
            
            return redirect()->back()->with('error',
                sprintf("⏳ Check-in possible à partir de 12h. Encore %d heures et %d minutes à attendre.",
                    $heures, $minutesRestantes));
        }

        try {
            DB::beginTransaction();

            $beforeState = $this->getTransactionState($transaction);

            $transaction->update([
                'status' => 'active',
                'check_in_actual' => now(),
            ]);

            if ($transaction->room) {
                $transaction->room->update(['room_status_id' => self::STATUS_OCCUPIED]);
                Log::info("Arrivée rapide: Chambre {$transaction->room->number} marquée OCCUPÉE à " . $now->format('H:i'));
            }

            if (auth()->user()->role === 'Receptionist') {
                $this->logReceptionistAction(
                    actionType: 'checkin',
                    actionSubtype: 'create',
                    actionable: $transaction,
                    actionData: [
                        'action' => 'quick_arrival',
                        'time' => now()->format('H:i:s'),
                        'room' => $transaction->room->number ?? 'N/A',
                        'arrival_time' => $now->format('H:i'),
                    ],
                    beforeState: $beforeState,
                    afterState: $this->getTransactionState($transaction, true),
                    notes: 'Client marqué comme arrivé via bouton rapide'
                );
            }

            DB::commit();

            return redirect()->back()->with('success',
                "✅ Client marqué comme arrivé à " . $now->format('H:i') . " ! " .
                "La chambre <strong>{$transaction->room->number}</strong> est maintenant occupée."
            );

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur marquage arrivé:', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id,
            ]);

            return redirect()->back()->with('error',
                'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * ACTION RAPIDE : MARQUER COMME PARTI (AVEC DIRTY)
     */
    public function markAsDeparted(Request $request, Transaction $transaction)
    {
        if (! $this->hasPermission(['Super', 'Admin', 'Receptionist'])) {
            abort(403, 'Accès non autorisé');
        }

        // ✅ VÉRIFICATION QUE LA MÉTHODE canBeMarkedAsDeparted EXISTE DANS LE MODÈLE
        if (!method_exists($transaction, 'canBeMarkedAsDeparted')) {
            // Fallback à la vérification manuelle si la méthode n'existe pas
            $canDepart = $this->manualCheckCanDepart($transaction);
        } else {
            $canDepart = $transaction->canBeMarkedAsDeparted();
        }
        
        if (!$canDepart['can_depart']) {
            $errorMessage = "❌ " . $canDepart['reason'];
            
            if (isset($canDepart['details']['amount_due'])) {
                $errorMessage .= " - Montant dû: " . number_format($canDepart['details']['amount_due'], 0, ',', ' ') . " CFA";
            } elseif (isset($canDepart['details']['remaining'])) {
                $errorMessage .= " - Reste: " . number_format($canDepart['details']['remaining'], 0, ',', ' ') . " CFA";
            } elseif (isset($canDepart['details']['is_pending'])) {
                $errorMessage .= " - Paiement en attente (ID: " . ($canDepart['details']['pending_payment_id'] ?? '') . ")";
            }
            
            return redirect()->back()->with('error', $errorMessage);
        }

        // =====================================================
        // VÉRIFICATION DES HEURES MÉTIER (12h - 14h) + LATE CHECKOUT
        // =====================================================
        $now = Carbon::now();
        $checkOutDay = Carbon::parse($transaction->check_out)->startOfDay();
        $checkOutDeadline = $checkOutDay->copy()->setTime(12, 0, 0);   
        $checkOutLargess = $checkOutDay->copy()->setTime(14, 0, 0);    
        $lateCheckoutEnd = $checkOutDay->copy()->setTime(20, 0, 0);

        // Vérifier qu'on est bien le jour du départ
        if (!$now->isSameDay($checkOutDay)) {
            return redirect()->back()->with('error',
                "❌ Le départ ne peut être marqué que le jour prévu (" . $checkOutDay->format('d/m/Y') . "). " .
                "Si le client est encore là, veuillez prolonger le séjour.");
        }

        // ✅ GESTION DU LATE CHECKOUT
        $isOverride = $request->has('override') && $request->override == 1;

        // CAS 1: C'est un late checkout (entre 14h et 20h) → AUTORISÉ
        if ($transaction->late_checkout && $now->gt($checkOutLargess) && $now->lt($lateCheckoutEnd)) {
            // Vérifier une dernière fois que le paiement est bien fait
            if ($transaction->late_checkout_fee > 0) {
                $latePayment = $transaction->completedPayments()
                    ->where(function($query) {
                        $query->where('reference', 'like', 'LATE-%')
                            ->orWhere('description', 'like', '%Late checkout%');
                    })
                    ->where('amount', '>=', $transaction->late_checkout_fee)
                    ->first();

                if (!$latePayment) {
                    return redirect()->back()->with('error',
                        "❌ Supplément late checkout non payé !\n" .
                        "Montant: " . number_format($transaction->late_checkout_fee, 0, ',', ' ') . " CFA");
                }
            }
            
            Log::info("✅ Late checkout autorisé", [
                'transaction_id' => $transaction->id,
                'heure_depart' => $now->format('H:i'),
                'heure_prevue' => $transaction->expected_checkout_time
            ]);
        }
        // CAS 2: Après 14h mais PAS de late checkout → INTERDIT, sauf dérogation
        elseif ($now->gt($checkOutLargess) && !$isOverride) {
            return redirect()->back()->with('error',
                "⚠️ Départ après 14h. La largesse de 2h est dépassée.\n" .
                "Options:\n" .
                "- Prolonger d'une nuit (bouton Prolonger)\n" .
                "- Activer un late checkout (bouton Late checkout)\n" .
                "- Utiliser une dérogation (avec raison)");
        }
        // CAS 3: Après 20h → INTERDIT dans tous les cas
        elseif ($now->gte($lateCheckoutEnd)) {
            return redirect()->back()->with('error',
                "⚠️ Départ après 20h. Veuillez prolonger le séjour d'une nuit supplémentaire.");
        }

        // Si c'est une dérogation après 14h, vérifier la raison
        if ($isOverride && $now->gt($checkOutLargess)) {
            $request->validate([
                'override_reason' => 'required|string|max:500',
            ]);
            
            Log::info('✅ DÉROGATION ACCORDÉE - Départ après 14h', [
                'transaction_id' => $transaction->id,
                'heure_depart' => $now->format('H:i'),
                'raison' => $request->override_reason,
                'autorise_par' => auth()->user()->name
            ]);
        }

        // Avant 12h : trop tôt
        if ($now->lt($checkOutDeadline)) {
            $minutes = $now->diffInMinutes($checkOutDeadline, false);
            
            return redirect()->back()->with('error',
                sprintf("⏳ Check-out possible à partir de 12h. Encore %d minutes à attendre.",
                    ceil($minutes)));
        }

        // Entre 12h et 14h : largesse accordée
        $largessMessage = "";
        if ($now->gte($checkOutDeadline) && $now->lte($checkOutLargess)) {
            $largessMessage = " (largesse de 2h accordée)";
            Log::info("✅ Largesse accordée - Départ rapide entre 12h et 14h", [
                'transaction_id' => $transaction->id,
                'heure_depart' => $now->format('H:i')
            ]);
        }

        // Entre 14h et 20h avec late checkout
        if ($transaction->late_checkout && $now->gt($checkOutLargess) && $now->lt($lateCheckoutEnd)) {
            $largessMessage = " (late checkout)";
        }

        try {
            DB::beginTransaction();

            $beforeState = $this->getTransactionState($transaction);

            // ✅ Mettre à jour le statut de la transaction
            $transaction->update([
                'status' => 'completed',
                'check_out_actual' => now(),
            ]);

            // ✅ Marquer la chambre comme DIRTY (SALE)
            if ($transaction->room) {
                $transaction->room->update([
                    'room_status_id' => self::STATUS_DIRTY, 
                    'needs_cleaning' => 1,
                    'updated_at' => now(),
                ]);

                Log::info("✅ DÉPART RAPIDE: Chambre {$transaction->room->number} marquée DIRTY à " . $now->format('H:i'));
            }

            // ✅ Si c'était un late checkout, marquer tous les paiements en attente comme complétés
            if ($transaction->late_checkout && $transaction->late_checkout_fee > 0) {
                $pendingLatePayments = $transaction->payments()
                    ->where(function($query) {
                        $query->where('reference', 'like', 'LATE-%')
                            ->orWhere('description', 'like', '%Late checkout%');
                    })
                    ->where('status', 'pending')
                    ->get();

                foreach ($pendingLatePayments as $pendingPayment) {
                    $pendingPayment->markAsCompleted(auth()->id());
                    Log::info("✅ Paiement late checkout marqué comme payé automatiquement", [
                        'payment_id' => $pendingPayment->id,
                        'transaction_id' => $transaction->id
                    ]);
                }
            }

            // ✅ Log réceptionniste
            if (auth()->user()->role === 'Receptionist') {
                $actionData = [
                    'action' => 'quick_departure',
                    'time' => now()->format('H:i:s'),
                    'room' => $transaction->room->number ?? 'N/A',
                    'total_paid' => $transaction->getTotalPayment(),
                    'room_status' => 'dirty',
                    'departure_time' => $now->format('H:i'),
                    'within_largess' => ($now->gte($checkOutDeadline) && $now->lte($checkOutLargess)) ? 'yes' : 'no',
                    'is_late_checkout' => $transaction->late_checkout ? true : false,
                ];
                
                // Ajouter les infos de dérogation si applicable
                if ($isOverride && $now->gt($checkOutLargess)) {
                    $actionData['is_override'] = true;
                    $actionData['override_reason'] = $request->override_reason;
                }

                $this->logReceptionistAction(
                    actionType: 'checkout',
                    actionSubtype: 'create',
                    actionable: $transaction,
                    actionData: $actionData,
                    beforeState: $beforeState,
                    afterState: $this->getTransactionState($transaction, true),
                    notes: 'Client marqué comme parti - Chambre marquée À NETTOYER' . $largessMessage
                );
            }

            DB::commit();

            // ✅ Message personnalisé selon le type de départ
            if ($isOverride && $now->gt($checkOutLargess)) {
                $successMessage = "✅ DÉROGATION ACCORDÉE - Départ enregistré à " . $now->format('H:i') . " !<br>" .
                                "Raison: " . $request->override_reason . "<br>" .
                                "Chambre " . $transaction->room->number . " marquée comme À NETTOYER.";
            } elseif ($transaction->late_checkout && $now->gt($checkOutLargess)) {
                $successMessage = "✅ Late checkout effectué à " . $now->format('H:i') . " !<br>" .
                                "Chambre " . $transaction->room->number . " marquée comme À NETTOYER.<br>" .
                                "Supplément de " . number_format($transaction->late_checkout_fee, 0, ',', ' ') . " CFA encaissé.";
            } else {
                $successMessage = "✅ Départ enregistré à " . $now->format('H:i') . $largessMessage . " !<br>" .
                                "Chambre " . $transaction->room->number . " marquée comme À NETTOYER. " .
                                "Housekeeping informé - Nettoyage requis.";
            }

            return redirect()->back()->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur marquage parti:', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error',
                'Erreur lors du départ: ' . $e->getMessage());
        }
    }

    /**
     * Méthode de secours pour vérifier manuellement si le départ est possible
     */
    private function manualCheckCanDepart(Transaction $transaction): array
    {
        $result = [
            'can_depart' => false,
            'reason' => '',
            'details' => []
        ];
        
        if ($transaction->status !== 'active') {
            $result['reason'] = 'Le client n\'est pas dans l\'hôtel';
            return $result;
        }
        
        // Vérifier le late checkout
        if ($transaction->late_checkout && $transaction->late_checkout_fee > 0) {
            $latePayment = $transaction->completedPayments()
                ->where(function($query) {
                    $query->where('reference', 'like', 'LATE-%')
                        ->orWhere('description', 'like', '%Late checkout%');
                })
                ->where('amount', '>=', $transaction->late_checkout_fee)
                ->first();
            
            if (!$latePayment) {
                // Vérifier s'il y a un paiement en attente
                $pendingPayment = $transaction->payments()
                    ->where(function($query) {
                        $query->where('reference', 'like', 'LATE-%')
                            ->orWhere('description', 'like', '%Late checkout%');
                    })
                    ->where('status', 'pending')
                    ->first();
                
                if ($pendingPayment) {
                    $result['reason'] = 'Supplément late checkout en attente de paiement';
                    $result['details'] = [
                        'is_pending' => true,
                        'pending_payment_id' => $pendingPayment->id,
                        'amount_due' => $pendingPayment->amount
                    ];
                } else {
                    $result['reason'] = 'Supplément late checkout non payé';
                    $result['details'] = [
                        'amount_due' => $transaction->late_checkout_fee
                    ];
                }
                return $result;
            }
        }
        
        // Vérifier le paiement global
        if (!$transaction->isFullyPaid()) {
            $result['reason'] = 'Paiement incomplet';
            $result['details']['remaining'] = $transaction->getRemainingPayment();
            return $result;
        }
        
        $result['can_depart'] = true;
        return $result;
    }
    /**
     * =====================================================
     * ✅ UTILITAIRE : MARQUER UNE CHAMBRE COMME DIRTY
     * =====================================================
     */
    private function markRoomAsDirty(Room $room, ?Transaction $transaction = null): bool
    {
        try {
            $room->update([
                'room_status_id' => self::STATUS_DIRTY,
                'needs_cleaning' => 1,
                'updated_at' => now(),
            ]);

            if (Schema::hasColumn('rooms', 'last_cleaned_at')) {
                $room->update(['last_cleaned_at' => null]);
            }

            Log::info("🧹 Housekeeping: Chambre {$room->number} marquée sale (DIRTY)", [
                'room_id' => $room->id,
                'transaction_id' => $transaction?->id,
                'customer' => $transaction?->customer?->name,
                'marked_by' => auth()->user()->name,
                'marked_at' => now()->format('Y-m-d H:i:s'),
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur marquage chambre sale:', [
                'room_id' => $room->id,
                'room_number' => $room->number,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Annuler une réservation
     */
    public function cancel(Request $request, Transaction $transaction)
    {
        try {
            if (! $this->hasPermission(['Super', 'Admin', 'Receptionist'])) {
                return redirect()->back()->with('error', 'Accès non autorisé.');
            }

            if (! $this->canCancelReservation($transaction)) {
                return redirect()->back()->with('error',
                    'Cette réservation ne peut pas être annulée.');
            }

            if ($request->has('cancel_reason') && strlen($request->cancel_reason) > 500) {
                return redirect()->back()->with('error',
                    'La raison ne doit pas dépasser 500 caractères.');
            }

            DB::beginTransaction();

            $beforeState = $this->getTransactionState($transaction);

            $transaction->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_by' => auth()->id(),
                'cancel_reason' => $request->cancel_reason,
            ]);

            $room = $transaction->room;
            if ($room && $room->room_status_id == self::STATUS_OCCUPIED) {
                $room->update(['room_status_id' => self::STATUS_AVAILABLE]);
                Log::info("Annulation: Chambre {$room->number} libérée");
            }

            $totalPaid = $transaction->getTotalPayment();
            if ($totalPaid > 0) {
                Payment::create([
                    'transaction_id' => $transaction->id,
                    'price' => -$totalPaid,
                    'payment_method' => 'refund',
                    'reference' => 'REFUND-'.$transaction->id.'-'.time(),
                    'status' => 'completed',
                    'notes' => 'Remboursement annulation'.
                            ($request->cancel_reason ? " - {$request->cancel_reason}" : ''),
                    'created_by' => auth()->id(),
                ]);
            }

            if (auth()->user()->role === 'Receptionist') {
                $this->logReceptionistAction(
                    actionType: 'reservation',
                    actionSubtype: 'cancel',
                    actionable: $transaction,
                    actionData: [
                        'cancel_reason' => $request->cancel_reason,
                        'refund_amount' => $totalPaid,
                    ],
                    beforeState: $beforeState,
                    afterState: $this->getTransactionState($transaction, true),
                    notes: 'Réservation annulée via bouton annulation'
                );
            }

            DB::commit();

            $message = "Réservation #{$transaction->id} annulée.";
            if ($request->cancel_reason) {
                $message .= " Raison: {$request->cancel_reason}";
            }

            return redirect()->route('transaction.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur annulation:', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id,
            ]);

            return redirect()->back()->with('error',
                'Erreur lors de l\'annulation.');
        }
    }

    /**
     * Restaurer une réservation annulée
     */
    public function restore(Transaction $transaction)
    {
        try {
            if (! $this->hasPermission(['Super', 'Admin'])) {
                abort(403, 'Accès non autorisé');
            }

            if ($transaction->status != 'cancelled') {
                return redirect()->back()->with('error',
                    'Cette réservation n\'est pas annulée.');
            }

            DB::beginTransaction();

            $transaction->update([
                'status' => 'reservation',
                'cancelled_at' => null,
                'cancelled_by' => null,
                'cancel_reason' => null,
            ]);

            Payment::where('transaction_id', $transaction->id)
                ->where('payment_method', 'refund')
                ->delete();

            DB::commit();

            return redirect()->route('transaction.show', $transaction)
                ->with('success', "Réservation #{$transaction->id} restaurée.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur restauration:', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id,
            ]);

            return redirect()->back()->with('error',
                'Erreur lors de la restauration.');
        }
    }

    // =====================================================
    // MÉTHODES UTILITAIRES (inchangées)
    // =====================================================

    private function hasPermission(array $allowedRoles): bool
    {
        return in_array(auth()->user()->role, $allowedRoles);
    }

    private function canModifyTransaction(Transaction $transaction): bool
    {
        $checkOutDate = Carbon::parse($transaction->check_out);
        $isExpired = $checkOutDate->isPast();
        $notAllowedStatus = ['cancelled', 'completed', 'no_show'];

        return ! $isExpired && ! in_array($transaction->status, $notAllowedStatus);
    }

    /**
     * Vérifier si une réservation peut être annulée
     */
    private function canCancelReservation(Transaction $transaction): bool
    {
        if ($transaction->status == 'cancelled') {
            return false;
        }

        $checkInDateTime = Carbon::parse($transaction->check_in); 
        $now = Carbon::now();

        // Si la date d'arrivée est passée, on ne peut pas annuler
        if ($now->gt($checkInDateTime)) {
            return false;
        }

        // Moins de 2h avant l'arrivée (12h), on bloque l'annulation
        $hoursBeforeCheckIn = $now->diffInHours($checkInDateTime, false);
        if ($hoursBeforeCheckIn < 2 && $hoursBeforeCheckIn > 0) {
            Log::info('❌ Annulation impossible - Moins de 2h avant check-in', [
                'heures_restantes' => $hoursBeforeCheckIn,
                'check_in' => $checkInDateTime->format('d/m/Y H:i')
            ]);
            return false;
        }

        return true;
    }
    /**
     * Vérifier si une chambre est disponible (avec prise en compte des heures)
     */
    private function isRoomAvailable($roomId, $checkIn, $checkOut, $excludeTransactionId = null): bool
    {
        // S'assurer que les dates sont des objets Carbon avec les heures à 12h
        $requestCheckIn = Carbon::parse($checkIn)->setTime(12, 0, 0);
        $requestCheckOut = Carbon::parse($checkOut)->setTime(12, 0, 0);

        \Log::info('🔍 Vérification disponibilité avec heures:', [
            'room_id' => $roomId,
            'check_in' => $requestCheckIn->format('d/m/Y H:i'),
            'check_out' => $requestCheckOut->format('d/m/Y H:i')
        ]);

        $existingReservations = Transaction::where('room_id', $roomId)
            ->whereNotIn('status', ['cancelled', 'completed', 'no_show'])
            ->when($excludeTransactionId, function ($query) use ($excludeTransactionId) {
                $query->where('id', '!=', $excludeTransactionId);
            })
            ->get();

        foreach ($existingReservations as $reservation) {
            $resCheckIn = Carbon::parse($reservation->check_in);
            $resCheckOut = Carbon::parse($reservation->check_out);

            // Vérifier si les périodes se chevauchent
            if (
                ($requestCheckIn < $resCheckOut && $requestCheckOut > $resCheckIn)
            ) {
                Log::info('❌ Conflit de réservation détecté', [
                    'room_id' => $roomId,
                    'nouvelle_periode' => $requestCheckIn->format('d/m/Y H:i').' → '.$requestCheckOut->format('d/m/Y H:i'),
                    'reservation_existante' => [
                        'id' => $reservation->id,
                        'periode' => $resCheckIn->format('d/m/Y H:i').' → '.$resCheckOut->format('d/m/Y H:i'),
                        'status' => $reservation->status,
                    ],
                ]);

                return false;
            }
        }

        Log::info('✅ Chambre disponible', [
            'room_id' => $roomId,
            'periode' => $requestCheckIn->format('d/m/Y H:i').' → '.$requestCheckOut->format('d/m/Y H:i')
        ]);

        return true;
    }

    private function getTransactionState(Transaction $transaction, $refresh = false): array
    {
        if ($refresh) {
            $transaction->refresh();
        }

        return [
            'status' => $transaction->status,
            'check_in' => $transaction->check_in->format('Y-m-d'),
            'check_out' => $transaction->check_out->format('Y-m-d'),
            'check_in_actual' => $transaction->check_in_actual?->format('Y-m-d H:i:s'),
            'check_out_actual' => $transaction->check_out_actual?->format('Y-m-d H:i:s'),
            'cancelled_at' => $transaction->cancelled_at?->format('Y-m-d H:i:s'),
            'cancel_reason' => $transaction->cancel_reason,
            'total_price' => $transaction->getTotalPrice(),
            'total_paid' => $transaction->getTotalPayment(),
            'room_status' => $transaction->room->room_status_id ?? null,
            'room_number' => $transaction->room->number ?? 'N/A',
        ];
    }

    private function getStatusChangeMessage($oldStatus, $newStatus): string
    {
        $messages = [
            'reservation' => [
                'active' => '✅ Client marqué comme arrivé',
                'cancelled' => '❌ Réservation annulée',
                'no_show' => '👤 Client marqué comme No Show',
            ],
            'active' => [
                'completed' => '✅ Client marqué comme parti - Chambre à nettoyer',
                'cancelled' => '❌ Séjour annulé',
            ],
            'completed' => [
                'active' => '🔄 Séjour réactivé',
                'cancelled' => '❌ Séjour annulé',
            ],
        ];

        return $messages[$oldStatus][$newStatus]
            ?? "Statut changé de '{$this->getStatusLabel($oldStatus)}' à '{$this->getStatusLabel($newStatus)}'";
    }

    private function getStatusLabel($status): string
    {
        $labels = [
            'reservation' => 'Réservation',
            'active' => 'Dans l\'hôtel',
            'completed' => 'Terminé',
            'cancelled' => 'Annulée',
            'no_show' => 'No Show',
        ];

        return $labels[$status] ?? $status;
    }

    private function logReceptionistAction(
        string $actionType,
        string $actionSubtype,
        $actionable,
        array $actionData = [],
        array $beforeState = [],
        array $afterState = [],
        string $notes = ''
    ): void {
        try {
            $session = ReceptionistSession::firstOrCreate(
                [
                    'user_id' => auth()->id(),
                    'date' => now()->format('Y-m-d'),
                ],
                [
                    'started_at' => now(),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]
            );

            ReceptionistAction::create([
                'session_id' => $session->id,
                'user_id' => auth()->id(),
                'action_type' => $actionType,
                'action_subtype' => $actionSubtype,
                'actionable_type' => get_class($actionable),
                'actionable_id' => $actionable->id,
                'action_data' => $actionData,
                'before_state' => $beforeState,
                'after_state' => $afterState,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'notes' => $notes,
                'performed_at' => now(),
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur enregistrement action réceptionniste:', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);
        }
    }

    // =====================================================
    // MÉTHODES AJAX, EXPORT, PROLONGATION (inchangées)
    // =====================================================

    public function checkIfCanComplete(Transaction $transaction)
    {
        $canComplete = $transaction->isFullyPaid();
        $remaining = $transaction->getRemainingPayment();

        return response()->json([
            'can_complete' => $canComplete,
            'remaining' => $remaining,
            'formatted_remaining' => number_format($remaining, 0, ',', ' ').' CFA',
            'payment_rate' => $transaction->getPaymentRate(),
            'is_check_out_past' => $transaction->check_out->isPast(),
        ]);
    }

    public function checkPaymentStatus(Transaction $transaction)
    {
        return response()->json([
            'is_fully_paid' => $transaction->isFullyPaid(),
            'remaining_balance' => $transaction->getRemainingPayment(),
            'formatted_remaining' => number_format($transaction->getRemainingPayment(), 0, ',', ' ').' CFA',
            'can_check_out' => $transaction->isFullyPaid() && $transaction->status === 'active',
        ]);
    }

    public function invoice(Transaction $transaction)
    {
        $payments = $transaction->payments()->orderBy('created_at')->get();

        if ($payments->isEmpty()) {
            return redirect()->route('transaction.payment.create', $transaction)
                ->with('error', 'Aucun paiement trouvé.');
        }

        $lastPayment = $payments->last();

        return redirect()->route('payment.invoice', $lastPayment->id);
    }

    public function history(Transaction $transaction)
    {
        return view('transaction.history', [
            'transaction' => $transaction,
        ]);
    }

    public function myReservations(Request $request)
    {
        if (auth()->user()->role === 'Customer') {
            $customer = Customer::where('user_id', auth()->id())->first();

            if (! $customer) {
                return redirect()->route('dashboard.index')
                    ->with('error', 'Profil client non trouvé.');
            }

            $transactions = Transaction::where('customer_id', $customer->id)
                ->with(['room', 'room.type', 'room.roomStatus', 'payments'])
                ->orderBy('check_in', 'desc')
                ->paginate(10);

            $transactionsExpired = Transaction::where('customer_id', $customer->id)
                ->where('check_out', '<', now())
                ->with(['room', 'room.type', 'room.roomStatus', 'payments'])
                ->orderBy('check_out', 'desc')
                ->paginate(10);
        } else {
            $transactions = $this->transactionRepository->getTransaction($request);
            $transactionsExpired = $this->transactionRepository->getTransactionExpired($request);
        }

        return view('transaction.my-reservations', [
            'transactions' => $transactions,
            'transactionsExpired' => $transactionsExpired,
            'isCustomer' => auth()->user()->role === 'Customer',
        ]);
    }

    public function showDetails(Request $request, $id)
    {
        $transaction = Transaction::with(['customer.user', 'room.type', 'payments'])
            ->findOrFail($id);

        return view('transaction.details-modal', compact('transaction'));
    }

    public function checkAvailability(Request $request, Transaction $transaction)
    {
        $request->validate([
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        $available = $this->isRoomAvailable(
            $transaction->room_id,
            $request->check_in,
            $request->check_out,
            $transaction->id
        );

        return response()->json([
            'available' => $available,
            'message' => $available ?
                'Chambre disponible' :
                'Chambre non disponible pour ces dates',
        ]);
    }

    public function export(Request $request, $type = 'pdf')
    {
        return redirect()->route('transaction.index')
            ->with('info', 'Fonction d\'exportation à implémenter');
    }

    /**
     * Afficher le formulaire de prolongation
     */
    public function extend(Transaction $transaction)
    {
        if (! in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist'])) {
            abort(403, 'Accès non autorisé.');
        }

        if (! in_array($transaction->status, ['reservation', 'active'])) {
            return redirect()->route('transaction.show', $transaction)
                ->with('error', 'Seules les réservations et séjours en cours peuvent être prolongés.');
        }

        $currentCheckOut = Carbon::parse($transaction->check_out);
        $today = Carbon::now();

        // Suggérer une prolongation avec maintien de l'heure à 12h
        if ($currentCheckOut->isPast()) {
            $suggestedDate = $today->copy()->setTime(12, 0, 0)->addDay();
        } else {
            $suggestedDate = $currentCheckOut->copy()->addDay();
        }

        $transaction->load(['customer.user', 'room.type', 'room.roomStatus']);

        return view('transaction.extend', compact('transaction', 'suggestedDate'));
    }

    /**
     * Traiter la prolongation
     */
    public function processExtend(Request $request, Transaction $transaction)
    {
        if (! in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist'])) {
            abort(403, 'Accès non autorisé.');
        }

        // Récupérer la date actuelle de check-out
        $currentCheckOut = Carbon::parse($transaction->check_out);
        
        $validator = Validator::make($request->all(), [
            'new_check_out' => 'required|date|after:'.$currentCheckOut->format('Y-m-d'),
            'additional_nights' => 'required|integer|min:1|max:30',
            'notes' => 'nullable|string|max:500',
        ], [
            'new_check_out.required' => 'La nouvelle date de départ est requise',
            'new_check_out.after' => 'La nouvelle date de départ doit être après le ' . $currentCheckOut->format('d/m/Y'),
            'additional_nights.required' => 'Le nombre de nuits supplémentaires est requis',
            'additional_nights.min' => 'Vous devez ajouter au moins 1 nuit',
            'additional_nights.max' => 'Vous ne pouvez pas ajouter plus de 30 nuits',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // ✅ FORCER LA NOUVELLE DATE DE DÉPART À 12h00 (heure normale)
        $newCheckOut = Carbon::parse($request->new_check_out)->setTime(12, 0, 0);

        // Vérifier la disponibilité
        if (! $this->isRoomAvailable(
            $transaction->room_id, 
            $transaction->check_in->format('Y-m-d'), 
            $newCheckOut->format('Y-m-d'), 
            $transaction->id
        )) {
            return redirect()->back()
                ->with('error', 'Cette chambre n\'est pas disponible pour la période de prolongation.')
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // ✅ SAUVEGARDER LES ANCIENNES VALEURS
            $oldCheckOut = $transaction->check_out->format('Y-m-d H:i:s');
            $oldTotalPrice = $transaction->total_price;
            $oldNights = Carbon::parse($transaction->check_in)->diffInDays($transaction->check_out);
            $oldLateCheckout = $transaction->late_checkout;
            $oldExpectedTime = $transaction->expected_checkout_time;
            $oldLateFee = $transaction->late_checkout_fee;

            $additionalNights = $request->additional_nights;
            $roomPricePerNight = $transaction->room->price;
            $additionalPrice = $additionalNights * $roomPricePerNight;

            // ✅ PRÉPARER LES NOTES DE BASE
            $notes = $transaction->notes ?? '';
            $baseNote = "\n---\nProlongation: " . now()->format('d/m/Y H:i') . 
                        " - " . $additionalNights . " nuit(s) supplémentaire(s)" .
                        ($request->notes ? ' - ' . $request->notes : '');

            // ✅ PRÉPARER LES DONNÉES DE MISE À JOUR
            // RETOUR À LA NORMALE : 12h00, pas de late checkout
            $updateData = [
                'check_out' => $newCheckOut,                    
                'late_checkout' => false,                        
                'expected_checkout_time' => '12:00:00',          
                'late_checkout_fee' => null,                     
                'notes' => $notes . $baseNote,
            ];

            // ✅ SI C'ÉTAIT UN LATE CHECKOUT, AJOUTER UNE NOTE EXPLICATIVE
            if ($oldLateCheckout) {
                $updateData['notes'] .= "\n[" . now()->format('d/m/Y H:i') . "] ✅ LATE CHECKOUT ANNULÉ AUTOMATIQUEMENT - Retour à l'heure normale (12h00)";
            }

            // ✅ METTRE À JOUR LA TRANSACTION
            $transaction->update($updateData);

            // ✅ FORCER LE RAFRAÎCHISSEMENT
            $transaction->refresh();
            
            // ✅ RECALCULER LE PRIX TOTAL
            $newTotalPrice = $transaction->getTotalPrice();
            $expectedNewPrice = $oldTotalPrice + $additionalPrice;

            // ✅ CORRIGER LE PRIX SI NÉCESSAIRE
            if (abs($newTotalPrice - $expectedNewPrice) > 1) {
                Log::info("Correction prix prolongation transaction #{$transaction->id}", [
                    'old_price' => $oldTotalPrice,
                    'additional_price' => $additionalPrice,
                    'expected_new_price' => $expectedNewPrice,
                    'actual_new_price' => $newTotalPrice,
                ]);
                $transaction->total_price = $expectedNewPrice;
                $transaction->save();
                $newTotalPrice = $expectedNewPrice;
                $transaction->refresh();
            }

            // ✅ CRÉER L'HISTORIQUE COMPLET
            History::create([
                'transaction_id' => $transaction->id,
                'user_id' => auth()->id(),
                'action' => 'extend',
                'description' => 'Prolongation du séjour de '.$additionalNights.' nuit(s)',
                'old_values' => json_encode([
                    'check_out' => $oldCheckOut,
                    'total_price' => $oldTotalPrice,
                    'nights' => $oldNights,
                    'late_checkout' => $oldLateCheckout,
                    'expected_checkout_time' => $oldExpectedTime,
                    'late_checkout_fee' => $oldLateFee,
                    'room_price_per_night' => $roomPricePerNight,
                ]),
                'new_values' => json_encode([
                    'check_out' => $transaction->check_out->format('Y-m-d H:i:s'),
                    'total_price' => $newTotalPrice,
                    'nights' => Carbon::parse($transaction->check_in)->diffInDays($transaction->check_out),
                    'late_checkout' => false,
                    'expected_checkout_time' => '12:00:00',
                    'late_checkout_fee' => null,
                    'room_price_per_night' => $roomPricePerNight,
                    'additional_nights' => $additionalNights,
                    'additional_price' => $additionalPrice,
                ]),
                'notes' => $request->notes,
            ]);

            // ✅ LOG RÉCEPTIONNISTE
            if (auth()->user()->role === 'Receptionist') {
                $this->logReceptionistAction(
                    actionType: 'reservation',
                    actionSubtype: 'extend',
                    actionable: $transaction,
                    actionData: [
                        'additional_nights' => $additionalNights,
                        'additional_price' => $additionalPrice,
                        'new_check_out' => $newCheckOut->format('d/m/Y H:i'),
                        'old_check_out' => $oldCheckOut,
                        'room_price_per_night' => $roomPricePerNight,
                        'late_checkout_removed' => $oldLateCheckout ? true : false,
                        'old_late_time' => $oldExpectedTime,
                        'old_late_fee' => $oldLateFee,
                    ],
                    beforeState: [
                        'check_out' => $oldCheckOut,
                        'total_price' => $oldTotalPrice,
                        'nights' => $oldNights,
                        'late_checkout' => $oldLateCheckout,
                        'expected_checkout_time' => $oldExpectedTime,
                        'late_checkout_fee' => $oldLateFee,
                    ],
                    afterState: [
                        'check_out' => $transaction->check_out->format('Y-m-d H:i:s'),
                        'total_price' => $newTotalPrice,
                        'nights' => Carbon::parse($transaction->check_in)->diffInDays($transaction->check_out),
                        'late_checkout' => false,
                        'expected_checkout_time' => '12:00:00',
                        'late_checkout_fee' => null,
                        'notes' => $transaction->notes,
                    ],
                    notes: 'Prolongation de '.$additionalNights.' nuit(s) - '.
                        number_format($additionalPrice, 0, ',', ' ').' CFA' .
                        ($oldLateCheckout ? ' (Late checkout annulé)' : '')
                );
            }

            DB::commit();

            // ✅ CONSTRUIRE LE MESSAGE DE SUCCÈS
            $message = '✅ <strong>Séjour prolongé avec succès !</strong><br>';
            $message .= "<strong>+{$additionalNights} nuit(s)</strong> ajoutée(s) à " .
                    number_format($roomPricePerNight, 0, ',', ' ') . ' CFA/nuit<br>';
            $message .= '<strong>Supplément :</strong> ' .
                    number_format($additionalPrice, 0, ',', ' ') . ' CFA<br>';
            $message .= 'Nouvelle date de départ : <strong>' .
                    $newCheckOut->format('d/m/Y H:i') . '</strong><br>';
            
            if ($oldLateCheckout) {
                $message .= '<br><div style="background-color: #17a2b8; color: white; padding: 10px; border-radius: 5px; margin: 10px 0;">';
                $message .= '🔄 <strong>Late checkout automatiquement annulé</strong><br>';
                $message .= 'Ancienne heure : ' . $oldExpectedTime . ' - Supplément de ' . 
                            number_format($oldLateFee, 0, ',', ' ') . ' FCFA supprimé<br>';
                $message .= 'Retour à l\'heure normale : <strong>12h00</strong>';
                $message .= '</div>';
            }
            
            $message .= '<strong>Ancien total :</strong> ' .
                    number_format($oldTotalPrice, 0, ',', ' ') . ' CFA<br>';
            $message .= '<strong>Nouveau total :</strong> ' .
                    number_format($newTotalPrice, 0, ',', ' ') . ' CFA';

            // ✅ METTRE À JOUR LE STATUT DES PAIEMENTS
            $transaction->updatePaymentStatus();

            return redirect()->route('transaction.show', $transaction)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur prolongation séjour:', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Erreur lors de la prolongation: ' . $e->getMessage());
        }
    }
    /**
     * Traiter un late checkout (départ après 14h)
     */
    public function lateCheckout(Request $request, Transaction $transaction)
    {
        try {
            $request->validate([
                'late_checkout_time' => 'required|date_format:H:i|after:14:00|before_or_equal:20:00',
                'late_fee' => 'required|numeric|min:0',
                'payment_method' => 'required|string|in:cash,card,mobile_money,bank_transfer,fedapay',
                'notes' => 'nullable|string|max:500',
            ]);

            // Vérifier que la transaction est active
            if ($transaction->status != 'active') {
                return back()->with('error', 'Seules les réservations actives peuvent bénéficier d\'un late checkout.');
            }

            // Vérifier que le départ est aujourd'hui
            if (!$transaction->check_out->isToday()) {
                return back()->with('error', 'Le late checkout n\'est possible que pour les départs d\'aujourd\'hui.');
            }

            // Vérifier que le late checkout n'est pas déjà enregistré
            if ($transaction->late_checkout) {
                return back()->with('error', 'Un late checkout est déjà enregistré pour cette réservation.');
            }

            DB::beginTransaction();

            try {
                // Sauvegarder l'ancienne heure pour l'historique
                $oldCheckOut = $transaction->check_out->format('Y-m-d H:i:s');
                $oldTotalPrice = $transaction->total_price;
                $oldNights = $transaction->getNightsAttribute();

                // Créer la nouvelle date de départ avec l'heure choisie
                $newCheckOut = Carbon::parse($transaction->check_out->format('Y-m-d') . ' ' . $request->late_checkout_time . ':00');
                
                // Préparer les notes
                $notes = $transaction->checkout_notes ?? '';
                $newNote = "\n[" . now()->format('d/m/Y H:i') . "] ✅ LATE CHECKOUT - Départ reporté à {$request->late_checkout_time}";
                
                // Mettre à jour la transaction
                $transaction->update([
                    'late_checkout' => true,
                    'expected_checkout_time' => $request->late_checkout_time,
                    'check_out' => $newCheckOut,
                    'total_price' => $transaction->total_price + $request->late_fee,
                    'late_checkout_fee' => $request->late_fee,
                    'checkout_notes' => $notes . $newNote . ($request->notes ? " - " . $request->notes : ""),
                ]);

                // ✅ CRÉER LE PAIEMENT POUR LE SUPPLÉMENT - SANS LES COLONNES MANQUANTES
                $payment = null;
                if ($request->late_fee > 0) {
                    $payment = Payment::create([
                        'transaction_id' => $transaction->id,
                        'customer_id' => $transaction->customer_id,
                        'amount' => $request->late_fee,
                        'payment_method' => $request->payment_method,
                        'status' => Payment::STATUS_COMPLETED, // ✅ COMPLETED directement
                        'reference' => 'LATE-' . $transaction->id . '-' . time(),
                        'description' => 'Supplément late checkout du ' . now()->format('d/m/Y') . 
                                    ' - Départ à ' . $request->late_checkout_time,
                        'created_by' => auth()->id(),
                        'user_id' => auth()->id(),
                        // ✅ On enlève payment_date, verified_at, verified_by
                    ]);

                    Log::info("✅ Paiement late checkout créé et marqué comme payé", [
                        'transaction_id' => $transaction->id,
                        'payment_id' => $payment->id,
                        'amount' => $request->late_fee,
                        'status' => 'completed'
                    ]);
                }

                // ✅ CRÉER UN HISTORIQUE
                History::create([
                    'transaction_id' => $transaction->id,
                    'user_id' => auth()->id(),
                    'action' => 'late_checkout',
                    'description' => 'Late checkout enregistré - Départ à ' . $request->late_checkout_time,
                    'old_values' => json_encode([
                        'check_out' => $oldCheckOut,
                        'total_price' => $oldTotalPrice,
                        'nights' => $oldNights,
                    ]),
                    'new_values' => json_encode([
                        'check_out' => $newCheckOut->format('Y-m-d H:i:s'),
                        'total_price' => $transaction->total_price,
                        'nights' => $transaction->getNightsAttribute(),
                        'late_checkout_time' => $request->late_checkout_time,
                        'late_fee' => $request->late_fee,
                        'payment_id' => $payment?->id,
                        'payment_status' => 'completed',
                    ]),
                    'notes' => $request->notes,
                ]);

                // ✅ METTRE À JOUR LE STATUT DE LA TRANSACTION
                $transaction->updatePaymentStatus();
                $transaction->refresh();

                DB::commit();

                // Log activité
                activity()
                    ->performedOn($transaction)
                    ->causedBy(auth()->user())
                    ->withProperties([
                        'late_time' => $request->late_checkout_time,
                        'fee' => $request->late_fee,
                        'payment_method' => $request->payment_method,
                        'payment_id' => $payment?->id,
                        'payment_status' => 'completed',
                        'old_check_out' => $oldCheckOut,
                        'new_check_out' => $newCheckOut->format('Y-m-d H:i:s'),
                        'notes' => $request->notes
                    ])
                    ->log('Late checkout enregistré et payé');

                // ✅ CONSTRUIRE LE MESSAGE DE SUCCÈS
                $message = '<div class="alert alert-success" style="border-left: 4px solid #10b981;">';
                $message .= '<h5 class="mb-3"><i class="fas fa-check-circle text-success me-2"></i> Late checkout enregistré et payé avec succès !</h5>';
                $message .= '<div class="mb-2"><strong>Nouvelle heure de départ :</strong> ' . $request->late_checkout_time . '</div>';
                
                if ($request->late_fee > 0) {
                    $message .= '<div class="mb-2"><strong>Supplément :</strong> ' . number_format($request->late_fee, 0, ',', ' ') . ' FCFA</div>';
                    $message .= '<div class="mb-2"><strong>Méthode :</strong> ' . ucfirst($request->payment_method) . '</div>';
                    $message .= '<div class="alert alert-success mt-2 mb-0 p-2" style="background-color: #d4edda;">';
                    $message .= '<i class="fas fa-check-circle me-2 text-success"></i>';
                    $message .= '<strong>Paiement effectué</strong> - Le client peut maintenant partir.';
                    $message .= '</div>';
                } else {
                    $message .= '<div class="alert alert-info mt-2 mb-0 p-2">';
                    $message .= '<i class="fas fa-info-circle me-2"></i>';
                    $message .= 'Aucun supplément facturé (largesse exceptionnelle)';
                    $message .= '</div>';
                }
                $message .= '</div>';

                return redirect()->back()->with('success', $message);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Erreur lors de la création du late checkout:', [
                    'error' => $e->getMessage(),
                    'transaction_id' => $transaction->id,
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            \Log::error('Late checkout error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du late checkout: ' . $e->getMessage());
        }
    }

    /**
     * =====================================================
     * ✅ EARLY CHECKOUT - Départ anticipé (VERSION CORRIGÉE AVEC SESSION)
     * =====================================================
     */
    public function earlyCheckout(Request $request, Transaction $transaction)
    {
        if (! $this->hasPermission(['Super', 'Admin', 'Receptionist'])) {
            abort(403, 'Accès non autorisé.');
        }

        // Vérifier que la transaction est active
        if ($transaction->status !== 'active') {
            return redirect()->back()->with('error', 
                '❌ Seul un séjour en cours peut être marqué comme early checkout.');
        }

        // Vérifier qu'on est avant la date prévue
        $today = Carbon::today();
        $scheduledCheckOut = Carbon::parse($transaction->check_out)->startOfDay();
        
        if (!$today->lt($scheduledCheckOut)) {
            return redirect()->back()->with('error', 
                '❌ Early checkout ne peut être utilisé que pour un départ avant la date prévue. ' .
                'Utilisez "Check-out normal" pour aujourd\'hui ou "Late checkout" pour après 14h.');
        }

        $request->validate([
            'early_checkout_reason' => 'nullable|string|max:500',
            'refund_policy' => 'nullable|in:full,partial,none',
            'refund_amount' => 'nullable|numeric|min:0|max:' . $transaction->getTotalPayment(),
            'payment_method' => 'required_if:refund_amount,>0|in:cash,card,mobile_money,bank_transfer',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // =====================================================
            // 0. RÉCUPÉRER LA SESSION ACTIVE
            // =====================================================
            $activeSession = \App\Models\CashierSession::where('user_id', auth()->id())
                ->where('status', 'active')
                ->first();

            if (!$activeSession) {
                return redirect()->back()->with('error', 
                    '❌ Vous devez avoir une session de caisse active pour effectuer un early checkout.');
            }

            // =====================================================
            // 1. SAUVEGARDER L'ÉTAT AVANT
            // =====================================================
            $beforeState = [
                'status' => $transaction->status,
                'check_in' => $transaction->check_in->format('Y-m-d H:i:s'),
                'check_out' => $transaction->check_out->format('Y-m-d H:i:s'),
                'total_price' => $transaction->total_price,
                'total_paid' => $transaction->getTotalPayment(),
                'room_status' => $transaction->room->room_status_id ?? null,
                'nights_planned' => $transaction->getDateDifferenceWithPlural(),
            ];

            $plannedCheckOut = Carbon::parse($transaction->check_out);
            
            // ✅ FORCER LES TYPES NUMÉRIQUES
            $plannedNights = (int) $transaction->getDateDifferenceWithPlural();
            $actualNights = (int) Carbon::parse($transaction->check_in)->diffInDays($today);
            
            // =====================================================
            // 2. CALCULER LE NOUVEAU PRIX
            // =====================================================
            $roomPrice = (float) $transaction->room->price;
            $newTotalPrice = $roomPrice * $actualNights;
            $oldTotalPrice = (float) $transaction->total_price;
            $priceDifference = $oldTotalPrice - $newTotalPrice;
            
            $totalPaid = (float) $transaction->getTotalPayment();
            $refundAmount = 0;
            
            // =====================================================
            // 3. DÉTERMINER LA POLITIQUE DE REMBOURSEMENT
            // =====================================================
            $refundPolicy = $request->refund_policy ?? 'none';
            $refundReason = '';
            
            switch ($refundPolicy) {
                case 'full':
                    $refundAmount = (float) min($priceDifference, $totalPaid);
                    $refundReason = 'Remboursement intégral selon politique d\'annulation';
                    break;
                    
                case 'partial':
                    $refundAmount = (float) ($request->refund_amount ?? 0);
                    $refundReason = 'Remboursement partiel selon politique d\'annulation';
                    break;
                    
                case 'none':
                    $refundAmount = 0;
                    $refundReason = 'Aucun remboursement selon politique d\'annulation';
                    break;
            }

            // =====================================================
            // 4. CRÉER LE REMBOURSEMENT SI NÉCESSAIRE (AVEC SESSION)
            // =====================================================
            if ($refundAmount > 0) {
                $description = 'Remboursement early checkout - ' . 
                            $actualNights . ' nuit(s) sur ' . $plannedNights . ' prévue(s)';
                
                if ($request->early_checkout_reason) {
                    $description .= ' - Raison: ' . $request->early_checkout_reason;
                }
                
                $refundPayment = Payment::create([
                    'transaction_id' => $transaction->id,
                    'customer_id' => $transaction->customer_id,
                    'user_id' => auth()->id(),
                    'created_by' => auth()->id(),
                    'cashier_session_id' => $activeSession->id, // ✅ LIEN CRUCIAL VERS LA SESSION
                    'amount' => -$refundAmount,
                    'payment_method' => $request->payment_method,
                    'status' => Payment::STATUS_COMPLETED,
                    'reference' => 'REFUND-EARLY-' . $transaction->id . '-' . time(),
                    'description' => $description,
                ]);

                // ✅ METTRE À JOUR LES TOTAUX DE LA SESSION
                $activeSession->refunds_total = ($activeSession->refunds_total ?? 0) + $refundAmount;
                $activeSession->current_balance -= $refundAmount;
                
                // ✅ METTRE À JOUR cash_out SI C'EST UN REMBOURSEMENT EN ESPÈCES
                if ($request->payment_method == 'cash') {
                    $activeSession->cash_out = ($activeSession->cash_out ?? 0) + $refundAmount;
                }
                
                $activeSession->save();

                Log::info("💰 Remboursement early checkout créé et lié à la session", [
                    'transaction_id' => $transaction->id,
                    'amount' => $refundAmount,
                    'payment_id' => $refundPayment->id,
                    'session_id' => $activeSession->id,
                ]);
            }

            // =====================================================
            // 5. METTRE À JOUR LA TRANSACTION
            // =====================================================
            $notes = $transaction->notes ?? '';
            $newNote = "\n[" . now()->format('d/m/Y H:i') . "] ✅ EARLY CHECKOUT - Départ anticipé de " . 
                    ($plannedNights - $actualNights) . " nuit(s)";
            
            if ($refundAmount > 0) {
                $newNote .= " - Remboursé: " . number_format($refundAmount, 0, ',', ' ') . " CFA";
            }

            $transaction->update([
                'status' => 'completed',
                'check_out_actual' => now(),
                'total_price' => $newTotalPrice,
                'early_checkout' => true,
                'early_checkout_reason' => $request->early_checkout_reason,
                'early_checkout_refund' => $refundAmount,
                'notes' => $notes . $newNote . ($request->notes ? ' - ' . $request->notes : ''),
                'cashier_session_id' => $activeSession->id, // ✅ LIEN VERS LA SESSION
            ]);

            // =====================================================
            // 6. MARQUER LA CHAMBRE COMME DIRTY
            // =====================================================
            if ($transaction->room) {
                $this->markRoomAsDirty($transaction->room, $transaction);
                
                Log::info("🧹 EARLY CHECKOUT: Chambre {$transaction->room->number} marquée DIRTY", [
                    'room_id' => $transaction->room->id,
                    'transaction_id' => $transaction->id,
                ]);
            }

            // =====================================================
            // 7. CRÉER L'HISTORIQUE
            // =====================================================
            History::create([
                'transaction_id' => $transaction->id,
                'user_id' => auth()->id(),
                'action' => 'early_checkout',
                'description' => 'Départ anticipé de ' . ($plannedNights - $actualNights) . ' nuit(s)',
                'old_values' => json_encode($beforeState),
                'new_values' => json_encode([
                    'status' => 'completed',
                    'check_out_actual' => now()->format('Y-m-d H:i:s'),
                    'total_price' => $newTotalPrice,
                    'nights_actual' => $actualNights,
                    'early_checkout' => true,
                    'early_checkout_reason' => $request->early_checkout_reason,
                    'refund_amount' => $refundAmount,
                    'refund_policy' => $refundPolicy,
                    'room_status' => self::STATUS_DIRTY,
                    'cashier_session_id' => $activeSession->id,
                ]),
                'notes' => $request->notes,
            ]);

            // =====================================================
            // 8. LOG RÉCEPTIONNISTE
            // =====================================================
            if (auth()->user()->role === 'Receptionist') {
                $this->logReceptionistAction(
                    actionType: 'checkout',
                    actionSubtype: 'early_checkout',
                    actionable: $transaction,
                    actionData: [
                        'early_checkout_reason' => $request->early_checkout_reason,
                        'nights_planned' => $plannedNights,
                        'nights_actual' => $actualNights,
                        'nights_short' => $plannedNights - $actualNights,
                        'refund_amount' => $refundAmount,
                        'refund_policy' => $refundPolicy,
                        'old_total_price' => $oldTotalPrice,
                        'new_total_price' => $newTotalPrice,
                        'price_difference' => $priceDifference,
                        'cashier_session_id' => $activeSession->id,
                    ],
                    beforeState: $beforeState,
                    afterState: [
                        'status' => 'completed',
                        'check_out_actual' => now()->format('Y-m-d H:i:s'),
                        'total_price' => $newTotalPrice,
                        'early_checkout' => true,
                        'early_checkout_reason' => $request->early_checkout_reason,
                        'refund_amount' => $refundAmount,
                        'cashier_session_id' => $activeSession->id,
                    ],
                    notes: 'Early checkout - Départ anticipé'
                );
            }

            DB::commit();

            // =====================================================
            // 9. CONSTRUIRE LE MESSAGE DE SUCCÈS
            // =====================================================
            $message = '<div class="alert alert-success" style="border-left: 4px solid #17a2b8;">';
            $message .= '<h5 class="mb-3"><i class="fas fa-clock me-2 text-info"></i> Early checkout enregistré avec succès !</h5>';
            
            $message .= '<div class="mb-2"><strong>Client :</strong> ' . $transaction->customer->name . '</div>';
            $message .= '<div class="mb-2"><strong>Chambre :</strong> ' . $transaction->room->number . '</div>';
            $message .= '<div class="mb-2"><strong>Départ anticipé :</strong> ' . ($plannedNights - $actualNights) . ' nuit(s) avant la date prévue</div>';
            $message .= '<div class="mb-2"><strong>Nuités effectives :</strong> ' . $actualNights . ' / ' . $plannedNights . '</div>';
            
            $message .= '<hr>';
            $message .= '<div class="mb-2"><strong>Ancien total :</strong> ' . number_format($oldTotalPrice, 0, ',', ' ') . ' CFA</div>';
            $message .= '<div class="mb-2"><strong>Nouveau total :</strong> ' . number_format($newTotalPrice, 0, ',', ' ') . ' CFA</div>';
            
            if ($refundAmount > 0) {
                $message .= '<div class="alert alert-info mt-2 mb-0 p-2">';
                $message .= '<i class="fas fa-undo-alt me-2"></i>';
                $message .= '<strong>Remboursement :</strong> ' . number_format($refundAmount, 0, ',', ' ') . ' CFA par ' . $request->payment_method;
                $message .= '<br><small>' . $refundReason . '</small>';
                $message .= '</div>';
            } else {
                $message .= '<div class="alert alert-warning mt-2 mb-0 p-2">';
                $message .= '<i class="fas fa-exclamation-triangle me-2"></i>';
                $message .= '<strong>Aucun remboursement</strong> selon la politique d\'annulation';
                $message .= '</div>';
            }
            
            $message .= '<div class="mt-2"><i class="fas fa-broom me-2 text-success"></i> Chambre marquée <strong>À NETTOYER</strong></div>';
            $message .= '</div>';

            return redirect()->route('transaction.show', $transaction)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Erreur early checkout:', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Erreur lors du early checkout: ' . $e->getMessage());
        }
    }
    /**
     * Vérifier si un early checkout est possible
     */
    public function checkEarlyCheckoutPossibility(Transaction $transaction)
    {
        if ($transaction->status !== 'active') {
            return [
                'possible' => false,
                'reason' => 'Le client n\'est pas actuellement dans l\'hôtel'
            ];
        }

        $today = Carbon::today();
        $scheduledCheckOut = Carbon::parse($transaction->check_out)->startOfDay();
        
        if (!$today->lt($scheduledCheckOut)) {
            return [
                'possible' => false,
                'reason' => 'La date de départ prévue est aujourd\'hui ou déjà passée'
            ];
        }

        $plannedNights = $transaction->getDateDifferenceWithPlural();
        $actualNights = Carbon::parse($transaction->check_in)->diffInDays($today);
        $nightsShort = $plannedNights - $actualNights;

        $totalPaid = $transaction->getTotalPayment();
        $roomPrice = $transaction->room->price;
        $newTotalPrice = $roomPrice * $actualNights;
        $potentialRefund = max(0, $totalPaid - $newTotalPrice);

        return [
            'possible' => true,
            'details' => [
                'planned_check_out' => $transaction->check_out->format('d/m/Y'),
                'today' => $today->format('d/m/Y'),
                'planned_nights' => $plannedNights,
                'actual_nights' => $actualNights,
                'nights_short' => $nightsShort,
                'total_paid' => $totalPaid,
                'new_total_price' => $newTotalPrice,
                'potential_refund' => $potentialRefund,
                'room_price_per_night' => $roomPrice,
            ]
        ];
    }
}