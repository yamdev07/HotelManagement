<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Room;
use App\Models\Payment;
use App\Models\ReceptionistAction;
use App\Models\ReceptionistSession;
use App\Models\History;
use App\Repositories\Interface\TransactionRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function __construct(
        private TransactionRepositoryInterface $transactionRepository
    ) {}

    /**
     * Afficher la liste des transactions
     */
    public function index(Request $request)
    {
        // Récupérer les transactions ACTIVES (pas annulées, pas terminées)
        $transactions = $this->transactionRepository->getTransaction($request);
        
        // Récupérer les transactions EXPIRÉES ou ANCIENNES (incluant les annulées)
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
        // Cette méthode est gérée par TransactionRoomReservationController
        return redirect()->route('transaction.reservation.createIdentity');
    }

    /**
     * Enregistrer une nouvelle transaction
     */
    public function store(Request $request)
    {
        // La création est gérée par TransactionRoomReservationController
        return redirect()->route('transaction.index');
    }

    /**
     * Afficher les détails d'une transaction
     */
    public function show(Transaction $transaction)
    {
        try {
            // Récupérer les paiements
            $payments = $transaction->payments()->orderBy('created_at', 'desc')->get();
        } catch (\Exception $e) {
            $payments = collect([]);
            Log::error('Erreur récupération paiements:', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage()
            ]);
        }
        
        // Calculer le nombre de nuits
        $checkIn = Carbon::parse($transaction->check_in);
        $checkOut = Carbon::parse($transaction->check_out);
        $nights = $checkIn->diffInDays($checkOut);
        
        // Calculer les totaux
        $totalPrice = $transaction->getTotalPrice();
        $totalPayment = $transaction->getTotalPayment();
        $remaining = $totalPrice - $totalPayment;
        $isFullyPaid = $remaining <= 0;
        
        // Déterminer le statut
        $status = $transaction->status;
        $isExpired = $checkOut->isPast();
        
        // Vérifier si la réservation peut être annulée
        $canCancel = $this->canCancelReservation($transaction);
        
        // Récupérer le client et la chambre
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
        // Vérifier les permissions - INCLUS LES RECEPTIONNISTES
        if (!$this->hasPermission(['Super', 'Admin', 'Receptionist'])) {
            abort(403, 'Accès non autorisé.');
        }
        
        // Vérifier si la transaction peut être modifiée
        $checkOutDate = Carbon::parse($transaction->check_out);
        $isExpired = $checkOutDate->isPast();
        
        if ($isExpired || in_array($transaction->status, ['cancelled', 'completed', 'no_show'])) {
            return redirect()->route('transaction.show', $transaction)
                ->with('error', 'Impossible de modifier une réservation terminée, annulée ou no show.');
        }
        
        // Charger les relations nécessaires
        $transaction->load(['customer.user', 'room.type', 'room.roomStatus']);
        
        return view('transaction.edit', compact('transaction'));
    }

    /**
     * Mettre à jour une transaction existante
     */
    public function update(Request $request, Transaction $transaction)
    {
        // Vérifier les permissions - INCLUS LES RECEPTIONNISTES
        if (!$this->hasPermission(['Super', 'Admin', 'Receptionist'])) {
            abort(403, 'Accès non autorisé');
        }
        
        // Vérifier si la transaction peut être modifiée
        if (!$this->canModifyTransaction($transaction)) {
            return redirect()->route('transaction.show', $transaction)
                ->with('error', 'Cette réservation ne peut plus être modifiée.');
        }
        
        // Validation
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
        
        // Vérifier la disponibilité de la chambre
        if (!$this->isRoomAvailable($transaction->room_id, $request->check_in, $request->check_out, $transaction->id)) {
            return redirect()->back()
                ->with('error', 'Cette chambre est déjà réservée pour les dates sélectionnées.')
                ->withInput();
        }
        
        try {
            DB::beginTransaction();
            
            // Sauvegarder l'état avant modification
            $beforeState = [
                'check_in' => $transaction->check_in->format('Y-m-d'),
                'check_out' => $transaction->check_out->format('Y-m-d'),
                'total_price' => $transaction->getTotalPrice(),
                'notes' => $transaction->notes
            ];
            
            // Mettre à jour
            $transaction->update([
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'notes' => $request->notes ?? $transaction->notes,
            ]);
            
            // Enregistrer l'action si réceptionniste
            if (auth()->user()->role === 'Receptionist') {
                $this->logReceptionistAction(
                    actionType: 'reservation',
                    actionSubtype: 'update',
                    actionable: $transaction,
                    actionData: [
                        'old_dates' => $beforeState,
                        'new_dates' => [
                            'check_in' => $transaction->check_in->format('Y-m-d'),
                            'check_out' => $transaction->check_out->format('Y-m-d')
                        ]
                    ],
                    beforeState: $beforeState,
                    afterState: [
                        'check_in' => $transaction->check_in->format('Y-m-d'),
                        'check_out' => $transaction->check_out->format('Y-m-d'),
                        'total_price' => $transaction->getTotalPrice(),
                        'notes' => $transaction->notes
                    ],
                    notes: 'Modification des dates de réservation'
                );
            }
            
            DB::commit();
            
            // Calculer le changement de prix
            $oldPrice = $transaction->getTotalPrice();
            $transaction->refresh();
            $newPrice = $transaction->getTotalPrice();
            
            $message = "Réservation #{$transaction->id} mise à jour avec succès.";
            if ($oldPrice != $newPrice) {
                $oldPriceFormatted = number_format($oldPrice, 0, ',', ' ') . ' CFA';
                $newPriceFormatted = number_format($newPrice, 0, ',', ' ') . ' CFA';
                $message .= " Nouveau total: {$newPriceFormatted} (ancien: {$oldPriceFormatted})";
            }
            
            return redirect()->route('transaction.show', $transaction)
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur modification transaction:', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id
            ]);
            
            return redirect()->back()
                ->with('error', 'Erreur lors de la modification: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer une transaction
     */
    /**
 * Supprimer une transaction
 */
    public function destroy(Transaction $transaction)
    {
        try {
            // Seuls Super Admin peuvent supprimer
            if (!in_array(auth()->user()->role, ['Super'])) {
                abort(403, 'Accès non autorisé. Seuls les Super Admins peuvent supprimer.');
            }
            
            $transactionId = $transaction->id;
            $customerName = $transaction->customer->name;
            
            DB::beginTransaction();
            
            // Sauvegarder pour logs
            $deletedData = [
                'transaction' => $transaction->toArray(),
                'payments' => $transaction->payments->toArray(),
                'deleted_by' => auth()->id(),
                'deleted_at' => now()->format('Y-m-d H:i:s')
            ];
            
            // Supprimer les paiements
            Payment::where('transaction_id', $transaction->id)->delete();
            
            // Supprimer la transaction
            $transaction->delete();
            
            // Libérer la chambre si nécessaire
            $room = $transaction->room;
            if ($room && $room->room_status_id == 2) {
                $otherTransactions = Transaction::where('room_id', $room->id)
                    ->where('id', '!=', $transactionId)
                    ->where('check_out', '>', now())
                    ->exists();
                
                if (!$otherTransactions) {
                    $room->update(['room_status_id' => 1]);
                }
            }
            
            DB::commit();
            
            // Log la suppression
            Log::warning('Transaction supprimée définitivement', $deletedData);
            
            return redirect()->route('transaction.index')
                ->with('success', "Réservation #{$transactionId} pour {$customerName} supprimée définitivement.");
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur suppression transaction:', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id
            ]);
            
            // CORRECTION ICI : Supprimer les parenthèses après transaction.index
            return redirect()->route('transaction.index')
                ->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Mettre à jour le statut d'une transaction (METHODE PRINCIPALE)
     */
    public function updateStatus(Request $request, Transaction $transaction)
    {
        // Vérifier les permissions - INCLUS LES RECEPTIONNISTES
        if (!$this->hasPermission(['Super', 'Admin', 'Receptionist'])) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Accès non autorisé'], 403);
            }
            abort(403, 'Accès non autorisé.');
        }
        
        // Validation
        $request->validate([
            'status' => 'required|in:reservation,active,completed,cancelled,no_show',
            'cancel_reason' => 'nullable|string|max:500',
            'user_role' => 'nullable|string'
        ]);
        
        $oldStatus = $transaction->status;
        $newStatus = $request->status;
        
        // Vérifications spécifiques selon le statut
        if ($newStatus === 'completed') {
            if (!$transaction->isFullyPaid()) {
                $remaining = $transaction->getRemainingPayment();
                $formattedRemaining = number_format($remaining, 0, ',', ' ') . ' CFA';
                
                if ($request->ajax()) {
                    return response()->json([
                        'error' => 'Paiement incomplet',
                        'message' => 'Impossible de marquer comme terminé. Solde restant: ' . $formattedRemaining,
                        'remaining' => $remaining
                    ], 422);
                }
                
                return redirect()->back()->with('error', 
                    "❌ Paiement incomplet ! Solde restant: {$formattedRemaining}"
                );
            }
        }
        
        // Blocage du retour à réservation si date passée
        if ($newStatus === 'reservation' && Carbon::parse($transaction->check_in)->isPast()) {
            $errorMsg = 'Impossible de revenir à "Réservation", la date d\'arrivée est passée.';
            
            if ($request->ajax()) {
                return response()->json(['error' => $errorMsg], 422);
            }
            
            return redirect()->back()->with('error', $errorMsg);
        }
        
        // Raison obligatoire pour annulation
        if ($newStatus === 'cancelled' && empty($request->cancel_reason)) {
            $errorMsg = 'Une raison est obligatoire pour l\'annulation.';
            
            if ($request->ajax()) {
                return response()->json(['error' => $errorMsg], 422);
            }
            
            return redirect()->back()->with('error', $errorMsg);
        }
        
        try {
            DB::beginTransaction();
            
            // Sauvegarder l'état avant modification
            $beforeState = $this->getTransactionState($transaction);
            
            // Préparer les données de mise à jour
            $updateData = ['status' => $newStatus];
            
            // Gérer les transitions spécifiques
            switch ($newStatus) {
                case 'active':
                    // Client arrive
                    $updateData['check_in_actual'] = now();
                    
                    // Marquer la chambre comme occupée
                    if ($transaction->room) {
                        $transaction->room->update(['room_status_id' => 2]);
                        
                        // Enregistrer l'action réceptionniste
                        if (auth()->user()->role === 'Receptionist') {
                            $this->logReceptionistAction(
                                actionType: 'checkin',
                                actionSubtype: 'create',
                                actionable: $transaction,
                                actionData: [
                                    'check_in_actual' => now()->format('Y-m-d H:i:s'),
                                    'room_number' => $transaction->room->number,
                                    'customer_name' => $transaction->customer->name
                                ],
                                beforeState: $beforeState,
                                afterState: $this->getTransactionState($transaction, true),
                                notes: 'Client marqué comme arrivé à l\'hôtel'
                            );
                        }
                    }
                    break;
                    
                case 'completed':
                    // Client part - vérification sécurité
                    if (!$transaction->isFullyPaid()) {
                        DB::rollBack();
                        $remaining = $transaction->getRemainingPayment();
                        $formattedRemaining = number_format($remaining, 0, ',', ' ') . ' CFA';
                        
                        return redirect()->back()->with('error', 
                            "Erreur de sécurité: Paiement incomplet. Solde: {$formattedRemaining}"
                        );
                    }
                    
                    $updateData['check_out_actual'] = now();
                    
                    // Libérer la chambre
                    if ($transaction->room) {
                        $transaction->room->update(['room_status_id' => 1]);
                        
                        // Enregistrer l'action réceptionniste
                        if (auth()->user()->role === 'Receptionist') {
                            $this->logReceptionistAction(
                                actionType: 'checkout',
                                actionSubtype: 'create',
                                actionable: $transaction,
                                actionData: [
                                    'check_out_actual' => now()->format('Y-m-d H:i:s'),
                                    'room_number' => $transaction->room->number,
                                    'total_paid' => $transaction->getTotalPayment(),
                                    'payment_status' => 'complet'
                                ],
                                beforeState: $beforeState,
                                afterState: $this->getTransactionState($transaction, true),
                                notes: 'Client marqué comme parti - Séjour terminé'
                            );
                        }
                    }
                    break;
                    
                case 'cancelled':
                    // Annulation
                    $updateData['cancelled_at'] = now();
                    $updateData['cancelled_by'] = auth()->id();
                    $updateData['cancel_reason'] = $request->cancel_reason;
                    
                    // Libérer la chambre si occupée
                    if ($transaction->room && $transaction->room->room_status_id == 2) {
                        $transaction->room->update(['room_status_id' => 1]);
                    }
                    
                    // Créer remboursement si paiements existants
                    $totalPaid = $transaction->getTotalPayment();
                    if ($totalPaid > 0) {
                        Payment::create([
                            'transaction_id' => $transaction->id,
                            'price' => -$totalPaid,
                            'payment_method' => 'refund',
                            'reference' => 'REFUND-' . $transaction->id . '-' . time(),
                            'status' => 'completed',
                            'notes' => 'Remboursement annulation' . 
                                    ($request->cancel_reason ? ": {$request->cancel_reason}" : ''),
                            'created_by' => auth()->id(),
                        ]);
                    }
                    
                    // Enregistrer l'action réceptionniste
                    if (auth()->user()->role === 'Receptionist') {
                        $this->logReceptionistAction(
                            actionType: 'reservation',
                            actionSubtype: 'cancel',
                            actionable: $transaction,
                            actionData: [
                                'cancel_reason' => $request->cancel_reason,
                                'refund_amount' => $totalPaid,
                                'cancelled_by' => auth()->user()->name
                            ],
                            beforeState: $beforeState,
                            afterState: $this->getTransactionState($transaction, true),
                            notes: 'Réservation annulée'
                        );
                    }
                    break;
            }
            
            // Mettre à jour la transaction
            $transaction->update($updateData);
            
            DB::commit();
            
            // Journalisation
            Log::info('Statut transaction modifié', [
                'transaction_id' => $transaction->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'changed_by' => auth()->id(),
                'customer' => $transaction->customer->name,
                'room' => $transaction->room->number ?? 'N/A'
            ]);
            
            // Réponse
            $message = $this->getStatusChangeMessage($oldStatus, $newStatus);
            
            if ($newStatus === 'completed') {
                session()->flash('departure_success', [
                    'title' => 'Départ enregistré',
                    'message' => 'Client marqué comme parti. Chambre libérée.',
                    'transaction_id' => $transaction->id
                ]);
            }
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'new_status' => $newStatus,
                    'new_status_label' => $this->getStatusLabel($newStatus)
                ]);
            }
            
            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur mise à jour statut:', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id
            ]);
            
            $errorMsg = 'Erreur lors de la mise à jour du statut';
            
            if ($request->ajax()) {
                return response()->json(['error' => $errorMsg], 500);
            }
            
            return redirect()->back()->with('error', $errorMsg);
        }
    }

    /**
     * ACTION RAPIDE: Marquer comme arrivé (bouton spécifique)
     */
    public function markAsArrived(Transaction $transaction)
    {
        // Vérifier permissions
        if (!$this->hasPermission(['Super', 'Admin', 'Receptionist'])) {
            abort(403, 'Accès non autorisé');
        }
        
        // Vérifier que c'est une réservation
        if ($transaction->status !== 'reservation') {
            return redirect()->back()->with('error', 
                'Seule une réservation peut être marquée comme arrivée.');
        }
        
        try {
            DB::beginTransaction();
            
            // Sauvegarder état avant
            $beforeState = $this->getTransactionState($transaction);
            
            // Mettre à jour
            $transaction->update([
                'status' => 'active',
                'check_in_actual' => now()
            ]);
            
            // Marquer chambre comme occupée
            if ($transaction->room) {
                $transaction->room->update(['room_status_id' => 2]);
            }
            
            // Enregistrer action réceptionniste
            if (auth()->user()->role === 'Receptionist') {
                $this->logReceptionistAction(
                    actionType: 'checkin',
                    actionSubtype: 'create',
                    actionable: $transaction,
                    actionData: [
                        'action' => 'quick_arrival',
                        'time' => now()->format('H:i:s'),
                        'room' => $transaction->room->number ?? 'N/A'
                    ],
                    beforeState: $beforeState,
                    afterState: $this->getTransactionState($transaction, true),
                    notes: 'Client marqué comme arrivé via bouton rapide'
                );
            }
            
            DB::commit();
            
            return redirect()->back()->with('success', 
                "✅ Client marqué comme arrivé ! La chambre {$transaction->room->number} est maintenant occupée.");
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur marquage arrivé:', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id
            ]);
            
            return redirect()->back()->with('error', 
                'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * ACTION RAPIDE: Marquer comme parti (bouton spécifique)
     */
    public function markAsDeparted(Transaction $transaction)
    {
        // Vérifier permissions
        if (!$this->hasPermission(['Super', 'Admin', 'Receptionist'])) {
            abort(403, 'Accès non autorisé');
        }
        
        // Vérifier que le client est dans l'hôtel
        if ($transaction->status !== 'active') {
            return redirect()->back()->with('error', 
                'Seul un client dans l\'hôtel peut être marqué comme parti.');
        }
        
        // Vérifier paiement
        if (!$transaction->isFullyPaid()) {
            $remaining = $transaction->getRemainingPayment();
            $formattedRemaining = number_format($remaining, 0, ',', ' ') . ' CFA';
            
            return redirect()->back()->with('error', 
                "❌ Paiement incomplet ! Solde restant: {$formattedRemaining}");
        }
        
        try {
            DB::beginTransaction();
            
            // Sauvegarder état avant
            $beforeState = $this->getTransactionState($transaction);
            
            // Mettre à jour
            $transaction->update([
                'status' => 'completed',
                'check_out_actual' => now()
            ]);
            
            // Libérer la chambre
            if ($transaction->room) {
                $transaction->room->update(['room_status_id' => 1]);
            }
            
            // Enregistrer action réceptionniste
            if (auth()->user()->role === 'Receptionist') {
                $this->logReceptionistAction(
                    actionType: 'checkout',
                    actionSubtype: 'create',
                    actionable: $transaction,
                    actionData: [
                        'action' => 'quick_departure',
                        'time' => now()->format('H:i:s'),
                        'room' => $transaction->room->number ?? 'N/A',
                        'total_paid' => $transaction->getTotalPayment()
                    ],
                    beforeState: $beforeState,
                    afterState: $this->getTransactionState($transaction, true),
                    notes: 'Client marqué comme parti via bouton rapide'
                );
            }
            
            DB::commit();
            
            return redirect()->back()->with('success', 
                "✅ Client marqué comme parti ! La chambre {$transaction->room->number} est maintenant disponible.");
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur marquage parti:', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id
            ]);
            
            return redirect()->back()->with('error', 
                'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Annuler une réservation (ancienne méthode)
     */
    public function cancel(Request $request, Transaction $transaction)
    {
        try {
            // Vérifier permissions
            if (!$this->hasPermission(['Super', 'Admin', 'Receptionist'])) {
                return redirect()->back()->with('error', 'Accès non autorisé.');
            }
            
            // Vérifier si annulable
            if (!$this->canCancelReservation($transaction)) {
                return redirect()->back()->with('error', 
                    'Cette réservation ne peut pas être annulée.');
            }
            
            // Validation raison
            if ($request->has('cancel_reason') && strlen($request->cancel_reason) > 500) {
                return redirect()->back()->with('error', 
                    'La raison ne doit pas dépasser 500 caractères.');
            }
            
            DB::beginTransaction();
            
            // Sauvegarder état avant
            $beforeState = $this->getTransactionState($transaction);
            
            // Mettre à jour
            $transaction->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_by' => auth()->id(),
                'cancel_reason' => $request->cancel_reason,
            ]);
            
            // Libérer chambre si occupée
            $room = $transaction->room;
            if ($room && $room->room_status_id == 2) {
                $room->update(['room_status_id' => 1]);
            }
            
            // Remboursement si paiements
            $totalPaid = $transaction->getTotalPayment();
            if ($totalPaid > 0) {
                Payment::create([
                    'transaction_id' => $transaction->id,
                    'price' => -$totalPaid,
                    'payment_method' => 'refund',
                    'reference' => 'REFUND-' . $transaction->id . '-' . time(),
                    'status' => 'completed',
                    'notes' => 'Remboursement annulation' . 
                            ($request->cancel_reason ? " - {$request->cancel_reason}" : ''),
                    'created_by' => auth()->id(),
                ]);
            }
            
            // Enregistrer action réceptionniste
            if (auth()->user()->role === 'Receptionist') {
                $this->logReceptionistAction(
                    actionType: 'reservation',
                    actionSubtype: 'cancel',
                    actionable: $transaction,
                    actionData: [
                        'cancel_reason' => $request->cancel_reason,
                        'refund_amount' => $totalPaid
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
                'transaction_id' => $transaction->id
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
            // Seuls Super/Admin peuvent restaurer
            if (!$this->hasPermission(['Super', 'Admin'])) {
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
            
            // Supprimer remboursement
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
                'transaction_id' => $transaction->id
            ]);
            
            return redirect()->back()->with('error', 
                'Erreur lors de la restauration.');
        }
    }

    /**
     * ========================================
     * METHODES UTILITAIRES
     * ========================================
     */

    /**
     * Vérifier les permissions
     */
    private function hasPermission(array $allowedRoles): bool
    {
        return in_array(auth()->user()->role, $allowedRoles);
    }

    /**
     * Vérifier si une transaction peut être modifiée
     */
    private function canModifyTransaction(Transaction $transaction): bool
    {
        $checkOutDate = Carbon::parse($transaction->check_out);
        $isExpired = $checkOutDate->isPast();
        $notAllowedStatus = ['cancelled', 'completed', 'no_show'];
        
        return !$isExpired && !in_array($transaction->status, $notAllowedStatus);
    }

    /**
     * Vérifier si une réservation peut être annulée
     */
    private function canCancelReservation(Transaction $transaction): bool
    {
        if ($transaction->status == 'cancelled') {
            return false;
        }
        
        $checkInDate = Carbon::parse($transaction->check_in);
        $now = Carbon::now();
        
        // Pas d'annulation si date d'arrivée passée
        if ($checkInDate->isPast()) {
            return false;
        }
        
        // Pas d'annulation moins de 2h avant arrivée
        $hoursBeforeCheckIn = $now->diffInHours($checkInDate, false);
        if ($hoursBeforeCheckIn < 2 && $hoursBeforeCheckIn > 0) {
            return false;
        }
        
        return true;
    }

    /**
     * Vérifier disponibilité chambre
     */
    private function isRoomAvailable($roomId, $checkIn, $checkOut, $excludeTransactionId = null): bool
    {
        $query = Transaction::where('room_id', $roomId)
            ->where('status', '!=', 'cancelled')
            ->where(function($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in', [$checkIn, $checkOut])
                      ->orWhereBetween('check_out', [$checkIn, $checkOut])
                      ->orWhere(function($q) use ($checkIn, $checkOut) {
                          $q->where('check_in', '<', $checkIn)
                            ->where('check_out', '>', $checkOut);
                      });
            });
        
        if ($excludeTransactionId) {
            $query->where('id', '!=', $excludeTransactionId);
        }
        
        return !$query->exists();
    }

    /**
     * Obtenir l'état d'une transaction
     */
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
            'room_number' => $transaction->room->number ?? 'N/A'
        ];
    }

    /**
     * Obtenir le message de changement de statut
     */
    private function getStatusChangeMessage($oldStatus, $newStatus): string
    {
        $messages = [
            'reservation' => [
                'active' => 'Client marqué comme arrivé',
                'cancelled' => 'Réservation annulée',
                'no_show' => 'Client marqué comme No Show',
            ],
            'active' => [
                'completed' => 'Client marqué comme parti',
                'cancelled' => 'Séjour annulé',
            ],
            'completed' => [
                'active' => 'Séjour réactivé',
                'cancelled' => 'Séjour annulé',
            ],
        ];
        
        return $messages[$oldStatus][$newStatus] 
            ?? "Statut changé de '{$this->getStatusLabel($oldStatus)}' à '{$this->getStatusLabel($newStatus)}'";
    }

    /**
     * Obtenir le label d'un statut
     */
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

    /**
     * Enregistrer une action réceptionniste
     */
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
            // Trouver ou créer une session pour le réceptionniste
            $session = ReceptionistSession::firstOrCreate(
                [
                    'user_id' => auth()->id(),
                    'date' => now()->format('Y-m-d')
                ],
                [
                    'started_at' => now(),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]
            );
            
            // Créer l'action
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
                'performed_at' => now()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur enregistrement action réceptionniste:', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
        }
    }

    /**
     * ========================================
     * METHODES AJAX ET API
     * ========================================
     */

    /**
     * Vérifier si peut être marqué comme terminé
     */
    public function checkIfCanComplete(Transaction $transaction)
    {
        $canComplete = $transaction->isFullyPaid();
        $remaining = $transaction->getRemainingPayment();
        
        return response()->json([
            'can_complete' => $canComplete,
            'remaining' => $remaining,
            'formatted_remaining' => number_format($remaining, 0, ',', ' ') . ' CFA',
            'payment_rate' => $transaction->getPaymentRate(),
            'is_check_out_past' => $transaction->check_out->isPast()
        ]);
    }

    /**
     * Vérifier statut paiement
     */
    public function checkPaymentStatus(Transaction $transaction)
    {
        return response()->json([
            'is_fully_paid' => $transaction->isFullyPaid(),
            'remaining_balance' => $transaction->getRemainingPayment(),
            'formatted_remaining' => number_format($transaction->getRemainingPayment(), 0, ',', ' ') . ' CFA',
            'can_check_out' => $transaction->isFullyPaid() && $transaction->status === 'active'
        ]);
    }

    /**
     * Générer une facture
     */
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

    /**
     * Historique des modifications
     */
    public function history(Transaction $transaction)
    {
        return view('transaction.history', [
            'transaction' => $transaction,
        ]);
    }

    /**
     * Mes réservations (pour clients)
     */
    public function myReservations(Request $request)
    {
        if (auth()->user()->role === 'Customer') {
            $customer = Customer::where('user_id', auth()->id())->first();
            
            if (!$customer) {
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

    /**
     * Détails en format compact (modal)
     */
    public function showDetails(Request $request, $id)
    {
        $transaction = Transaction::with(['customer.user', 'room.type', 'payments'])
            ->findOrFail($id);
            
        return view('transaction.details-modal', compact('transaction'));
    }

    /**
     * Vérifier disponibilité pour modification
     */
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
                'Chambre non disponible pour ces dates'
        ]);
    }

    /**
     * Exporter les transactions
     */
    public function export(Request $request, $type = 'pdf')
    {
        $transactions = $this->transactionRepository->getTransaction($request);
        $transactionsExpired = $this->transactionRepository->getTransactionExpired($request);
        
        return redirect()->route('transaction.index')
            ->with('info', 'Fonction d\'exportation à implémenter');
    }

    /**
     * Prolonger une réservation
     */
    public function extend(Transaction $transaction)
    {
        // Vérifier les permissions
        if (!in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist'])) {
            abort(403, 'Accès non autorisé.');
        }
        
        // Vérifier si la réservation peut être prolongée
        if (!in_array($transaction->status, ['reservation', 'active'])) {
            return redirect()->route('transaction.show', $transaction)
                ->with('error', 'Seules les réservations et séjours en cours peuvent être prolongés.');
        }
        
        // Vérifier si la chambre est disponible pour prolongation
        $currentCheckOut = Carbon::parse($transaction->check_out);
        $today = Carbon::now();
        
        // Si la date de départ est déjà passée, on propose de prolonger à partir d'aujourd'hui
        $suggestedDate = $currentCheckOut->isPast() ? $today->copy()->addDay() : $currentCheckOut->copy()->addDay();
        
        $transaction->load(['customer.user', 'room.type', 'room.roomStatus']);
        
        return view('transaction.extend', compact('transaction', 'suggestedDate'));
    }

    /**
     * Traiter la prolongation d'une réservation
     */
    public function processExtend(Request $request, Transaction $transaction)
    {
        // Vérifier les permissions
        if (!in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist'])) {
            abort(403, 'Accès non autorisé.');
        }
        
        // Validation
        $validator = Validator::make($request->all(), [
            'new_check_out' => 'required|date|after:' . $transaction->check_out->format('Y-m-d'),
            'additional_nights' => 'required|integer|min:1|max:30',
            'notes' => 'nullable|string|max:500',
        ], [
            'new_check_out.required' => 'La nouvelle date de départ est requise',
            'new_check_out.after' => 'La nouvelle date de départ doit être après la date actuelle (' . $transaction->check_out->format('d/m/Y') . ')',
            'additional_nights.required' => 'Le nombre de nuits supplémentaires est requis',
            'additional_nights.min' => 'Vous devez ajouter au moins 1 nuit',
            'additional_nights.max' => 'Vous ne pouvez pas ajouter plus de 30 nuits',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Vérifier la disponibilité de la chambre
        $newCheckOut = $request->new_check_out;
        
        if (!$this->isRoomAvailable($transaction->room_id, $transaction->check_in->format('Y-m-d'), $newCheckOut, $transaction->id)) {
            return redirect()->back()
                ->with('error', 'Cette chambre n\'est pas disponible pour la période de prolongation.')
                ->withInput();
        }
        
        try {
            DB::beginTransaction();
            
            // Sauvegarder l'état avant modification
            $beforeState = [
                'check_out' => $transaction->check_out->format('Y-m-d'),
                'total_price' => $transaction->getTotalPrice(),
                'notes' => $transaction->notes
            ];
            
            // Calculer le prix supplémentaire
            $additionalNights = $request->additional_nights;
            $additionalPrice = $additionalNights * $transaction->room->price;
            $newTotalPrice = $transaction->getTotalPrice() + $additionalPrice;
            
            // Mettre à jour la réservation
            $transaction->update([
                'check_out' => $newCheckOut,
                'notes' => ($transaction->notes ? $transaction->notes . "\n---\n" : '') . 
                        'Prolongation: ' . now()->format('d/m/Y H:i') . 
                        ' - ' . $additionalNights . ' nuit(s) supplémentaire(s)' .
                        ($request->notes ? ' - ' . $request->notes : ''),
            ]);
            
            // Enregistrer dans l'historique
            History::create([
                'transaction_id' => $transaction->id,
                'user_id' => auth()->id(),
                'action' => 'extend',
                'description' => 'Prolongation du séjour de ' . $additionalNights . ' nuit(s)',
                'old_values' => json_encode($beforeState),
                'new_values' => json_encode([
                    'check_out' => $transaction->check_out->format('Y-m-d'),
                    'total_price' => $newTotalPrice,
                    'notes' => $transaction->notes
                ]),
                'notes' => $request->notes
            ]);
            
            // Enregistrer l'action si réceptionniste
            if (auth()->user()->role === 'Receptionist') {
                $this->logReceptionistAction(
                    actionType: 'reservation',
                    actionSubtype: 'extend',
                    actionable: $transaction,
                    actionData: [
                        'additional_nights' => $additionalNights,
                        'additional_price' => $additionalPrice,
                        'new_check_out' => $newCheckOut,
                        'old_check_out' => $beforeState['check_out']
                    ],
                    beforeState: $beforeState,
                    afterState: [
                        'check_out' => $transaction->check_out->format('Y-m-d'),
                        'total_price' => $newTotalPrice,
                        'notes' => $transaction->notes
                    ],
                    notes: 'Prolongation de ' . $additionalNights . ' nuit(s)'
                );
            }
            
            DB::commit();
            
            // Message de succès
            $message = "✅ Séjour prolongé avec succès !<br>";
            $message .= "<strong>+{$additionalNights} nuit(s)</strong> ajoutée(s)<br>";
            $message .= "Nouvelle date de départ : <strong>" . Carbon::parse($newCheckOut)->format('d/m/Y') . "</strong><br>";
            $message .= "Supplément : <strong>" . number_format($additionalPrice, 0, ',', ' ') . " CFA</strong><br>";
            $message .= "Nouveau total : <strong>" . number_format($newTotalPrice, 0, ',', ' ') . " CFA</strong>";
            
            return redirect()->route('transaction.show', $transaction)
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur prolongation séjour:', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id
            ]);
            
            return redirect()->back()
                ->with('error', 'Erreur lors de la prolongation: ' . $e->getMessage());
        }
    }
}