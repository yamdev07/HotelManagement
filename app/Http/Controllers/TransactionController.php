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

        return view('transaction.edit', compact('transaction'));
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

        $validator = Validator::make($request->all(), [
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'notes' => 'nullable|string|max:500',
        ], [
            'check_in.required' => 'La date d\'arrivée est requise',
            'check_out.required' => 'La date de départ est requise',
            'check_out.after' => 'La date de départ doit être après la date d\'arrivée',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if (! $this->isRoomAvailable($transaction->room_id, $request->check_in, $request->check_out, $transaction->id)) {
            return redirect()->back()
                ->with('error', 'Cette chambre est déjà réservée pour les dates sélectionnées.')
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $beforeState = [
                'check_in' => $transaction->check_in->format('Y-m-d H:i:s'),
                'check_out' => $transaction->check_out->format('Y-m-d H:i:s'),
                'total_price' => $transaction->total_price,
                'notes' => $transaction->notes,
            ];

            $oldCheckIn = Carbon::parse($transaction->check_in);
            $oldCheckOut = Carbon::parse($transaction->check_out);
            $oldNights = $oldCheckIn->diffInDays($oldCheckOut);
            $oldTotalPrice = $transaction->total_price;

            $transaction->update([
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'notes' => $request->notes ?? $transaction->notes,
            ]);

            $transaction->refresh();
            $newTotalPrice = $transaction->getTotalPrice();
            $transaction->total_price = $newTotalPrice;
            $transaction->save();

            $newCheckIn = Carbon::parse($transaction->check_in);
            $newCheckOut = Carbon::parse($transaction->check_out);
            $newNights = $newCheckIn->diffInDays($newCheckOut);

            History::create([
                'transaction_id' => $transaction->id,
                'user_id' => auth()->id(),
                'action' => 'date_change',
                'description' => 'Modification des dates : '.
                                $oldNights.' nuit(s) → '.$newNights.' nuit(s)',
                'old_values' => json_encode([
                    'check_in' => $beforeState['check_in'],
                    'check_out' => $beforeState['check_out'],
                    'total_price' => $oldTotalPrice,
                    'nights' => $oldNights,
                    'room_price_per_night' => $transaction->room->price ?? 0,
                ]),
                'new_values' => json_encode([
                    'check_in' => $transaction->check_in->format('Y-m-d H:i:s'),
                    'check_out' => $transaction->check_out->format('Y-m-d H:i:s'),
                    'total_price' => $newTotalPrice,
                    'nights' => $newNights,
                    'room_price_per_night' => $transaction->room->price ?? 0,
                    'calculated_at' => now()->format('Y-m-d H:i:s'),
                ]),
                'notes' => $request->notes ?? 'Modification des dates de séjour',
            ]);

            if (auth()->user()->role === 'Receptionist') {
                $this->logReceptionistAction(
                    actionType: 'reservation',
                    actionSubtype: 'update',
                    actionable: $transaction,
                    actionData: [
                        'old_dates' => [
                            'check_in' => $beforeState['check_in'],
                            'check_out' => $beforeState['check_out'],
                            'nights' => $oldNights,
                            'price' => $oldTotalPrice,
                        ],
                        'new_dates' => [
                            'check_in' => $transaction->check_in->format('Y-m-d H:i:s'),
                            'check_out' => $transaction->check_out->format('Y-m-d H:i:s'),
                            'nights' => $newNights,
                            'price' => $newTotalPrice,
                        ],
                    ],
                    beforeState: $beforeState,
                    afterState: [
                        'check_in' => $transaction->check_in->format('Y-m-d H:i:s'),
                        'check_out' => $transaction->check_out->format('Y-m-d H:i:s'),
                        'total_price' => $newTotalPrice,
                        'notes' => $transaction->notes,
                    ],
                    notes: 'Modification des dates de réservation avec recalcul du prix'
                );
            }

            DB::commit();

            $priceChange = $newTotalPrice - $oldTotalPrice;
            $priceChangeFormatted = number_format(abs($priceChange), 0, ',', ' ').' CFA';

            $message = "✅ Réservation #{$transaction->id} mise à jour avec succès.<br>";
            $message .= '<strong>Anciennes dates:</strong> '.
                    Carbon::parse($beforeState['check_in'])->format('d/m/Y').' → '.
                    Carbon::parse($beforeState['check_out'])->format('d/m/Y').
                    " ({$oldNights} nuit(s))<br>";
            $message .= '<strong>Nouvelles dates:</strong> '.
                    $newCheckIn->format('d/m/Y').' → '.
                    $newCheckOut->format('d/m/Y').
                    " ({$newNights} nuit(s))<br>";
            $message .= '<strong>Ancien total:</strong> '.
                    number_format($oldTotalPrice, 0, ',', ' ').' CFA<br>';
            $message .= '<strong>Nouveau total:</strong> '.
                    number_format($newTotalPrice, 0, ',', ' ').' CFA<br>';

            if ($priceChange != 0) {
                $changeType = $priceChange > 0 ? 'majoration' : 'réduction';
                $message .= "<strong>{$changeType}:</strong> ".
                        ($priceChange > 0 ? '+' : '').
                        number_format($priceChange, 0, ',', ' ').' CFA<br>';

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
                ->with('error', 'Erreur lors de la modification: '.$e->getMessage());
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

        // ✅ VÉRIFICATION DES DATES POUR CHANGEMENT DE STATUT
        $today = Carbon::today();
        $checkInDate = Carbon::parse($transaction->check_in)->startOfDay();
        $checkOutDate = Carbon::parse($transaction->check_out)->startOfDay();

        // Bloquer "active" avant date d'arrivée
        if ($newStatus === 'active' && $today->lt($checkInDate)) {
            $daysUntil = $today->diffInDays($checkInDate);
            $errorMsg = "⏳ Date d'arrivée non atteinte ! " .
                        "Arrivée prévue le " . $checkInDate->format('d/m/Y') . ". " .
                        ($daysUntil > 0 ? "Encore " . $daysUntil . " jour(s) à attendre." : "");
            
            if ($request->ajax()) {
                return response()->json(['error' => $errorMsg], 422);
            }
            return redirect()->back()->with('error', $errorMsg);
        }

        // Bloquer "completed" avant date de départ
        if ($newStatus === 'completed' && $today->lt($checkOutDate)) {
            $daysUntil = $today->diffInDays($checkOutDate);
            $errorMsg = "⏳ Date de départ non atteinte ! " .
                        "Départ prévu le " . $checkOutDate->format('d/m/Y') . ". " .
                        ($daysUntil > 0 ? "Encore " . $daysUntil . " jour(s) de séjour." : "Départ prévu aujourd'hui.");
            
            if ($request->ajax()) {
                return response()->json(['error' => $errorMsg], 422);
            }
            return redirect()->back()->with('error', $errorMsg);
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
                    // ✅ CORRECTION MAJEURE : Marquer la chambre comme DIRTY (SALE)
                    // =====================================================
                    if ($transaction->room) {
                        $transaction->room->update([
                            'room_status_id' => self::STATUS_DIRTY, // 6 = À nettoyer
                            'needs_cleaning' => 1,
                            'updated_at' => now(),
                        ]);

                        Log::info("✅ DÉPART (updateStatus): Chambre {$transaction->room->number} marquée DIRTY");

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
                session()->flash('departure_success', [
                    'title' => '✅ Départ enregistré - Chambre à nettoyer',
                    'message' => 'Client marqué comme parti. Chambre marquée "À NETTOYER". Housekeeping informé.',
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
    public function markAsArrived(Transaction $transaction)
    {
        if (! $this->hasPermission(['Super', 'Admin', 'Receptionist'])) {
            abort(403, 'Accès non autorisé');
        }

        if ($transaction->status !== 'reservation') {
            return redirect()->back()->with('error',
                'Seule une réservation peut être marquée comme arrivée.');
        }

        // ✅ VÉRIFICATION DE LA DATE D'ARRIVÉE
        $today = Carbon::today();
        $checkInDate = Carbon::parse($transaction->check_in)->startOfDay();
        
        if ($today->lt($checkInDate)) {
            $daysUntil = $today->diffInDays($checkInDate);
            $message = "⏳ Date d'arrivée non atteinte ! " .
                    "Arrivée prévue le " . $checkInDate->format('d/m/Y') . ". ";
            
            if ($daysUntil > 0) {
                $message .= "Encore " . $daysUntil . " jour(s) à attendre.";
            } else {
                $message .= "Arrivée prévue aujourd'hui.";
            }
            
            return redirect()->back()->with('error', $message);
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
                Log::info("Arrivée rapide: Chambre {$transaction->room->number} marquée OCCUPÉE");
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
                    ],
                    beforeState: $beforeState,
                    afterState: $this->getTransactionState($transaction, true),
                    notes: 'Client marqué comme arrivé via bouton rapide'
                );
            }

            DB::commit();

            return redirect()->back()->with('success',
                "✅ Client marqué comme arrivé ! La chambre <strong>{$transaction->room->number}</strong> est maintenant occupée."
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
    public function markAsDeparted(Transaction $transaction)
    {
        if (! $this->hasPermission(['Super', 'Admin', 'Receptionist'])) {
            abort(403, 'Accès non autorisé');
        }

        if ($transaction->status !== 'active') {
            return redirect()->back()->with('error',
                'Seul un client dans l\'hôtel peut être marqué comme parti.');
        }

        // ✅ VÉRIFICATION DE LA DATE DE DÉPART
        $today = Carbon::today();
        $checkOutDate = Carbon::parse($transaction->check_out)->startOfDay();
        
        if ($today->lt($checkOutDate)) {
            $daysUntil = $today->diffInDays($checkOutDate);
            $message = "⏳ Date de départ non atteinte ! " .
                    "Départ prévu le " . $checkOutDate->format('d/m/Y') . ". ";
            
            if ($daysUntil > 0) {
                $message .= "Encore " . $daysUntil . " jour(s) de séjour.";
            } else {
                $message .= "Départ prévu aujourd'hui.";
            }
            
            return redirect()->back()->with('error', $message);
        }

        if (! $transaction->isFullyPaid()) {
            $remaining = $transaction->getRemainingPayment();
            $formattedRemaining = number_format($remaining, 0, ',', ' ') . ' CFA';

            return redirect()->back()->with('error',
                "❌ Paiement incomplet ! Solde restant: " . $formattedRemaining);
        }

        try {
            DB::beginTransaction();

            $beforeState = $this->getTransactionState($transaction);

            $transaction->update([
                'status' => 'completed',
                'check_out_actual' => now(),
            ]);

            // =====================================================
            // ✅ CORRECTION MAJEURE : Marquer la chambre comme DIRTY (SALE)
            // =====================================================
            if ($transaction->room) {
                $transaction->room->update([
                    'room_status_id' => self::STATUS_DIRTY, // 6 = À nettoyer
                    'needs_cleaning' => 1,
                    'updated_at' => now(),
                ]);

                Log::info("✅ DÉPART RAPIDE: Chambre {$transaction->room->number} marquée DIRTY");
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
                    ],
                    beforeState: $beforeState,
                    afterState: $this->getTransactionState($transaction, true),
                    notes: 'Client marqué comme parti - Chambre marquée À NETTOYER'
                );
            }

            DB::commit();

            $successMessage = "✅ Départ enregistré avec succès ! " .
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

    private function canCancelReservation(Transaction $transaction): bool
    {
        if ($transaction->status == 'cancelled') {
            return false;
        }

        $checkInDate = Carbon::parse($transaction->check_in);
        $now = Carbon::now();

        if ($checkInDate->isPast()) {
            return false;
        }

        $hoursBeforeCheckIn = $now->diffInHours($checkInDate, false);
        if ($hoursBeforeCheckIn < 2 && $hoursBeforeCheckIn > 0) {
            return false;
        }

        return true;
    }

    private function isRoomAvailable($roomId, $checkIn, $checkOut, $excludeTransactionId = null): bool
    {
        $requestCheckIn = Carbon::parse($checkIn);
        $requestCheckOut = Carbon::parse($checkOut);

        $existingReservations = Transaction::where('room_id', $roomId)
            ->whereNotIn('status', ['cancelled', 'completed', 'no_show'])
            ->when($excludeTransactionId, function ($query) use ($excludeTransactionId) {
                $query->where('id', '!=', $excludeTransactionId);
            })
            ->get();

        foreach ($existingReservations as $reservation) {
            $resCheckIn = Carbon::parse($reservation->check_in);
            $resCheckOut = Carbon::parse($reservation->check_out);

            if (
                ($requestCheckIn >= $resCheckIn && $requestCheckIn < $resCheckOut) ||
                ($requestCheckOut > $resCheckIn && $requestCheckOut <= $resCheckOut) ||
                ($requestCheckIn <= $resCheckIn && $requestCheckOut >= $resCheckOut)
            ) {
                Log::info('Conflit de réservation détecté', [
                    'room_id' => $roomId,
                    'nouvelle_periode' => $requestCheckIn->format('Y-m-d').' à '.$requestCheckOut->format('Y-m-d'),
                    'reservation_existante' => [
                        'id' => $reservation->id,
                        'periode' => $resCheckIn->format('Y-m-d').' à '.$resCheckOut->format('Y-m-d'),
                        'status' => $reservation->status,
                    ],
                ]);

                return false;
            }
        }

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

        $currentCheckOut = Carbon::parse($transaction->check_out);
        $today = Carbon::now();

        $suggestedDate = $currentCheckOut->isPast() ? $today->copy()->addDay() : $currentCheckOut->copy()->addDay();

        $transaction->load(['customer.user', 'room.type', 'room.roomStatus']);

        return view('transaction.extend', compact('transaction', 'suggestedDate'));
    }

    public function processExtend(Request $request, Transaction $transaction)
    {
        if (! in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist'])) {
            abort(403, 'Accès non autorisé.');
        }

        $validator = Validator::make($request->all(), [
            'new_check_out' => 'required|date|after:'.$transaction->check_out->format('Y-m-d'),
            'additional_nights' => 'required|integer|min:1|max:30',
            'notes' => 'nullable|string|max:500',
        ], [
            'new_check_out.required' => 'La nouvelle date de départ est requise',
            'new_check_out.after' => 'La nouvelle date de départ doit être après la date actuelle ('.$transaction->check_out->format('d/m/Y').')',
            'additional_nights.required' => 'Le nombre de nuits supplémentaires est requis',
            'additional_nights.min' => 'Vous devez ajouter au moins 1 nuit',
            'additional_nights.max' => 'Vous ne pouvez pas ajouter plus de 30 nuits',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $newCheckOut = $request->new_check_out;

        if (! $this->isRoomAvailable($transaction->room_id, $transaction->check_in->format('Y-m-d'), $newCheckOut, $transaction->id)) {
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
                        'new_check_out' => $newCheckOut,
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
                    Carbon::parse($newCheckOut)->format('d/m/Y').'</strong><br>';
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