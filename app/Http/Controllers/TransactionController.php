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
        $checkInDay = Carbon::parse($transaction->check_in)->startOfDay(); // Jour d'arrivée
        $checkOutDay = Carbon::parse($transaction->check_out)->startOfDay(); // Jour de départ

        // Heures métier
        $checkInTime = $checkInDay->copy()->setTime(12, 0, 0);   // Check-in à 12h
        $checkOutDeadline = $checkOutDay->copy()->setTime(12, 0, 0); // Check-out à 12h (théorique)
        $checkOutLargess = $checkOutDay->copy()->setTime(14, 0, 0);   // Largesse jusqu'à 14h

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
                            'room_status_id' => self::STATUS_DIRTY, // 6 = À nettoyer
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
     * =====================================================
     * ✅ ACTION RAPIDE : MARQUER COMME ARRIVÉ
     * =====================================================
     */
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
     * =====================================================
     * ✅ ACTION RAPIDE : MARQUER COMME PARTI (AVEC DIRTY)
     * =====================================================
     */
    /**
     * ACTION RAPIDE : MARQUER COMME PARTI (AVEC DIRTY)
     */
    public function markAsDeparted(Transaction $transaction)
    {
        if (! $this->hasPermission(['Super', 'Admin', 'Receptionist'])) {
            abort(403, 'Accès non autorisé');
        }

        if ($transaction->status !== 'active') {
            return redirect()->back()->with('error',
                'Seul un client dans l\'hôtel peut être marqué comme parti.');
        }

        // Vérifier le paiement complet
        if (! $transaction->isFullyPaid()) {
            $remaining = $transaction->getRemainingPayment();
            $formattedRemaining = number_format($remaining, 0, ',', ' ') . ' CFA';

            return redirect()->back()->with('error',
                "❌ Paiement incomplet ! Solde restant: " . $formattedRemaining);
        }

        // =====================================================
        // VÉRIFICATION DES HEURES MÉTIER (12h - 14h)
        // =====================================================
        $now = Carbon::now();
        $checkOutDay = Carbon::parse($transaction->check_out)->startOfDay();
        $checkOutDeadline = $checkOutDay->copy()->setTime(12, 0, 0);   // Check-out théorique à 12h
        $checkOutLargess = $checkOutDay->copy()->setTime(14, 0, 0);    // Largesse jusqu'à 14h

        // Vérifier qu'on est bien le jour du départ
        if (!$now->isSameDay($checkOutDay)) {
            return redirect()->back()->with('error',
                "❌ Le départ ne peut être marqué que le jour prévu (" . $checkOutDay->format('d/m/Y') . "). " .
                "Si le client est encore là, veuillez prolonger le séjour.");
        }

        // Après 14h : trop tard, doit prolonger
        if ($now->gt($checkOutLargess)) {
            return redirect()->back()->with('error',
                "⚠️ Départ après 14h. La largesse de 2h est dépassée. " .
                "Veuillez prolonger le séjour d'une nuit supplémentaire.");
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

        try {
            DB::beginTransaction();

            $beforeState = $this->getTransactionState($transaction);

            $transaction->update([
                'status' => 'completed',
                'check_out_actual' => now(),
            ]);

            // =====================================================
            // Marquer la chambre comme DIRTY (SALE)
            // =====================================================
            if ($transaction->room) {
                $transaction->room->update([
                    'room_status_id' => self::STATUS_DIRTY, // 6 = À nettoyer
                    'needs_cleaning' => 1,
                    'updated_at' => now(),
                ]);

                Log::info("✅ DÉPART RAPIDE: Chambre {$transaction->room->number} marquée DIRTY à " . $now->format('H:i'));
            }

            if (auth()->user()->role === 'Receptionist') {
                $this->logReceptionistAction(
                    actionType: 'checkout',
                    actionSubtype: 'create',
                    actionable: $transaction,
                    actionData: [
                        'action' => 'quick_departure',
                        'time' => now()->format('H:i:s'),
                        'room' => $transaction->room->number ?? 'N/A',
                        'total_paid' => $transaction->getTotalPayment(),
                        'room_status' => 'dirty',
                        'departure_time' => $now->format('H:i'),
                        'within_largess' => ($now->gte($checkOutDeadline) && $now->lte($checkOutLargess)) ? 'yes' : 'no'
                    ],
                    beforeState: $beforeState,
                    afterState: $this->getTransactionState($transaction, true),
                    notes: 'Client marqué comme parti - Chambre marquée À NETTOYER' . $largessMessage
                );
            }

            DB::commit();

            $successMessage = "✅ Départ enregistré à " . $now->format('H:i') . $largessMessage . " ! " .
                            "Chambre " . $transaction->room->number . " marquée comme À NETTOYER. " .
                            "Housekeeping informé - Nettoyage requis.";

            return redirect()->back()->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur marquage parti:', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id,
            ]);

            return redirect()->back()->with('error',
                'Erreur: ' . $e->getMessage());
        }
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

        $checkInDateTime = Carbon::parse($transaction->check_in); // Déjà avec l'heure (12h)
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

    public function extend(Transaction $transaction)
    {
        if (! in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist'])) {
            abort(403, 'Accès non autorisé.');
        }

        if (! in_array($transaction->status, ['reservation', 'active'])) {
            return redirect()->route('transaction.show', $transaction)
                ->with('error', 'Seules les réservations et séjours en cours peuvent être prolongés.');
        }

        $currentCheckOut = Carbon::parse($transaction->check_out); // Déjà à 12h
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

    public function processExtend(Request $request, Transaction $transaction)
    {
        if (! in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist'])) {
            abort(403, 'Accès non autorisé.');
        }

        // Récupérer la date actuelle de check-out (déjà à 12h)
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

        // Forcer la nouvelle date de départ à 12h
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

            $oldCheckOut = $transaction->check_out->format('Y-m-d H:i:s');
            $oldTotalPrice = $transaction->total_price;
            $oldNights = Carbon::parse($transaction->check_in)->diffInDays($transaction->check_out);

            $additionalNights = $request->additional_nights;
            $roomPricePerNight = $transaction->room->price;
            $additionalPrice = $additionalNights * $roomPricePerNight;

            $transaction->update([
                'check_out' => $newCheckOut,
                'notes' => ($transaction->notes ? $transaction->notes."\n---\n" : '').
                        'Prolongation: '.now()->format('d/m/Y H:i').
                        ' - '.$additionalNights.' nuit(s) supplémentaire(s)'.
                        ($request->notes ? ' - '.$request->notes : ''),
            ]);

            $transaction->refresh();
            $newTotalPrice = $transaction->getTotalPrice();
            $expectedNewPrice = $oldTotalPrice + $additionalPrice;

            if (abs($newTotalPrice - $expectedNewPrice) > 1) {
                Log::warning("Incohérence prix prolongation transaction #{$transaction->id}", [
                    'old_price' => $oldTotalPrice,
                    'additional_price' => $additionalPrice,
                    'expected_new_price' => $expectedNewPrice,
                    'actual_new_price' => $newTotalPrice,
                ]);
                $transaction->total_price = $expectedNewPrice;
                $transaction->save();
                $newTotalPrice = $expectedNewPrice;
            }

            History::create([
                'transaction_id' => $transaction->id,
                'user_id' => auth()->id(),
                'action' => 'extend',
                'description' => 'Prolongation du séjour de '.$additionalNights.' nuit(s)',
                'old_values' => json_encode([
                    'check_out' => $oldCheckOut,
                    'total_price' => $oldTotalPrice,
                    'nights' => $oldNights,
                    'room_price_per_night' => $roomPricePerNight,
                ]),
                'new_values' => json_encode([
                    'check_out' => $transaction->check_out->format('Y-m-d H:i:s'),
                    'total_price' => $newTotalPrice,
                    'nights' => Carbon::parse($transaction->check_in)->diffInDays($transaction->check_out),
                    'room_price_per_night' => $roomPricePerNight,
                    'additional_nights' => $additionalNights,
                    'additional_price' => $additionalPrice,
                ]),
                'notes' => $request->notes,
            ]);

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
                    ],
                    beforeState: [
                        'check_out' => $oldCheckOut,
                        'total_price' => $oldTotalPrice,
                        'nights' => $oldNights,
                    ],
                    afterState: [
                        'check_out' => $transaction->check_out->format('Y-m-d H:i:s'),
                        'total_price' => $newTotalPrice,
                        'nights' => Carbon::parse($transaction->check_in)->diffInDays($transaction->check_out),
                        'notes' => $transaction->notes,
                    ],
                    notes: 'Prolongation de '.$additionalNights.' nuit(s) - '.
                        number_format($additionalPrice, 0, ',', ' ').' CFA'
                );
            }

            DB::commit();

            $message = '✅ Séjour prolongé avec succès !<br>';
            $message .= "<strong>+{$additionalNights} nuit(s)</strong> ajoutée(s) à ".
                    number_format($roomPricePerNight, 0, ',', ' ').' CFA/nuit<br>';
            $message .= '<strong>Supplément :</strong> '.
                    number_format($additionalPrice, 0, ',', ' ').' CFA<br>';
            $message .= 'Nouvelle date de départ : <strong>'.
                    $newCheckOut->format('d/m/Y H:i').'</strong><br>';
            $message .= '<strong>Ancien total :</strong> '.
                    number_format($oldTotalPrice, 0, ',', ' ').' CFA<br>';
            $message .= '<strong>Nouveau total :</strong> '.
                    number_format($newTotalPrice, 0, ',', ' ').' CFA';

            return redirect()->route('transaction.show', $transaction)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur prolongation séjour:', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id,
            ]);

            return redirect()->back()
                ->with('error', 'Erreur lors de la prolongation: '.$e->getMessage());
        }
    }
}