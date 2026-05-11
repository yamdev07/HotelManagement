<?php

namespace App\Http\Controllers;

use App\Exceptions\HotelException;
use App\Http\Requests\UpdateTransactionRequest;
use App\Http\Requests\UpdateTransactionStatusRequest;
use App\Models\Payment;
use App\Models\Transaction;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Services\CheckInService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function __construct(
        private readonly TransactionRepositoryInterface $transactionRepository,
        private readonly TransactionService             $transactionService,
        private readonly CheckInService                 $checkInService,
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Transaction::class);

        return view('transaction.index', [
            'transactions'        => $this->transactionRepository->getTransaction($request),
            'transactionsExpired' => $this->transactionRepository->getTransactionExpired($request),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Transaction::class);

        return redirect()->route('transaction.reservation.createIdentity');
    }

    public function store(Request $request)
    {
        return redirect()->route('transaction.index');
    }

    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        $transaction->load(['customer.user', 'room.type', 'user']);

        $payments    = $transaction->payments()->orderBy('created_at', 'desc')->get();
        $checkIn     = $transaction->check_in;
        $checkOut    = $transaction->check_out;
        $nights      = $checkIn->diffInDays($checkOut);
        $totalPrice  = $transaction->getTotalPrice();
        $totalPayment = $transaction->getTotalPayment();
        $remaining   = $totalPrice - $totalPayment;
        $isFullyPaid = $remaining <= 0;
        $isExpired   = $checkOut->isPast();
        $canCancel   = $transaction->canBeCancelled() || auth()->user()->isSuper();

        return view('transaction.show', compact(
            'transaction', 'payments', 'nights', 'totalPrice',
            'totalPayment', 'remaining', 'isExpired', 'isFullyPaid',
            'canCancel'
        ));
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        if ($transaction->check_out->isPast() || $transaction->status === 'cancelled') {
            return redirect()->route('transaction.show', $transaction)
                ->with('error', 'Impossible de modifier une réservation terminée ou annulée.');
        }

        $transaction->load(['customer.user', 'room.type', 'room.roomStatus']);

        [$availableRooms, $occupiedRooms] = $this->getAvailableAndOccupiedRooms(
            $transaction->room_id,
            $transaction->check_in,
            $transaction->check_out,
            $transaction->id
        );

        return view('transaction.edit', compact('transaction', 'availableRooms', 'occupiedRooms'));
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        try {
            $updated = $this->transactionService->update($transaction, $request->validated());

            $message = "Réservation #{$updated->id} mise à jour avec succès.";

            return redirect()->route('transaction.show', $updated)->with('success', $message);

        } catch (HotelException $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        } catch (\Exception $e) {
            Log::error('Erreur modification transaction', ['id' => $transaction->id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Erreur interne lors de la modification.')->withInput();
        }
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        try {
            $id           = $transaction->id;
            $customerName = optional($transaction->customer)->name;

            $this->transactionService->destroy($transaction);

            return redirect()->route('transaction.index')
                ->with('success', "Réservation #{$id} ({$customerName}) supprimée définitivement.");

        } catch (\Exception $e) {
            Log::error('Erreur suppression transaction', ['id' => $transaction->id, 'error' => $e->getMessage()]);
            return redirect()->route('transaction.index')
                ->with('error', 'Erreur lors de la suppression.');
        }
    }

    public function updateStatus(UpdateTransactionStatusRequest $request, Transaction $transaction)
    {
        try {
            $updated = $this->transactionService->updateStatus(
                $transaction,
                $request->validated('status'),
                $request->validated('cancel_reason')
            );

            $message = "Statut mis à jour : {$updated->status_label}.";

            if ($request->ajax()) {
                return response()->json([
                    'success'          => true,
                    'message'          => $message,
                    'new_status'       => $updated->status,
                    'new_status_label' => $updated->status_label,
                ]);
            }

            return redirect()->back()->with('success', $message);

        } catch (HotelException $e) {
            if ($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], $e->httpStatusCode());
            }
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Erreur statut transaction', ['id' => $transaction->id, 'error' => $e->getMessage()]);
            if ($request->ajax()) {
                return response()->json(['error' => 'Erreur interne.'], 500);
            }
            return redirect()->back()->with('error', 'Erreur interne.');
        }
    }

    public function cancel(Request $request, Transaction $transaction)
    {
        $this->authorize('cancel', $transaction);

        $request->validate([
            'cancel_reason' => ['required', 'string', 'max:500'],
        ]);

        try {
            $force = auth()->user()->isSuper();
            $this->transactionService->cancel($transaction, $request->cancel_reason, $force);

            return redirect()->route('transaction.show', $transaction)
                ->with('success', 'Réservation annulée avec succès.');

        } catch (HotelException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function noShow(Request $request, Transaction $transaction)
    {
        $this->authorize('markAsNoShow', $transaction);

        $request->validate(['reason' => ['nullable', 'string', 'max:500']]);

        try {
            $this->transactionService->markAsNoShow($transaction, $request->reason ?? '');

            return redirect()->route('transaction.show', $transaction)
                ->with('success', 'Réservation marquée comme No Show.');

        } catch (HotelException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function restore(Transaction $transaction)
    {
        $this->authorize('restore', $transaction);

        try {
            $this->transactionService->restore($transaction);

            return redirect()->route('transaction.show', $transaction)
                ->with('success', 'Réservation restaurée avec succès.');

        } catch (HotelException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function extend(Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $transaction->load(['customer.user', 'room.type']);

        return view('transaction.extend', [
            'transaction'   => $transaction,
            'suggestedDate' => $transaction->check_out->copy()->addDay(),
        ]);
    }

    public function processExtend(\Illuminate\Http\Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $request->validate([
            'new_check_out'     => ['required', 'date', 'after:' . $transaction->check_out->format('Y-m-d')],
            'additional_nights' => ['required', 'integer', 'min:1', 'max:30'],
        ]);

        try {
            $newCheckOut    = \Carbon\Carbon::parse($request->new_check_out)->endOfDay();
            $pricePerNight  = (float) optional($transaction->room)->price;
            $extraNights    = (int) $request->additional_nights;
            $extraAmount    = $pricePerNight * $extraNights;

            $transaction->update([
                'check_out'   => $newCheckOut,
                'total_price' => (float) $transaction->total_price + $extraAmount,
            ]);

            \Illuminate\Support\Facades\Log::info("Transaction #{$transaction->id} prolongée", [
                'by'           => auth()->id(),
                'new_check_out' => $newCheckOut,
                'extra_nights' => $extraNights,
                'extra_amount' => $extraAmount,
            ]);

            return redirect()->route('transaction.show', $transaction)
                ->with('success', "Séjour prolongé de {$extraNights} nuit(s). Nouveau départ : {$newCheckOut->format('d/m/Y')}.");

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erreur prolongation', ['id' => $transaction->id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Erreur lors de la prolongation.')->withInput();
        }
    }

    // -----------------------------------------------------------------------
    // Vue invoice (impression)
    // -----------------------------------------------------------------------

    public function invoice(Transaction $transaction)
    {
        $this->authorize('view', $transaction);
        $transaction->load(['customer.user', 'room.type', 'user', 'payments']);
        return view('transaction.show', compact('transaction') + [
            'payments'     => $transaction->payments,
            'nights'       => $transaction->check_in->diffInDays($transaction->check_out),
            'totalPrice'   => $transaction->getTotalPrice(),
            'totalPayment' => $transaction->getTotalPayment(),
            'remaining'    => $transaction->getRemainingPayment(),
            'isFullyPaid'  => $transaction->isFullyPaid(),
            'isExpired'    => $transaction->check_out->isPast(),
            'canCancel'    => $transaction->canBeCancelled() || auth()->user()->isSuper(),
            'printMode'    => true,
        ]);
    }

    // -----------------------------------------------------------------------
    // Historique des activités
    // -----------------------------------------------------------------------

    public function history(Transaction $transaction)
    {
        $this->authorize('view', $transaction);
        $transaction->load(['customer.user', 'room.type', 'user', 'payments']);

        $histories = \Spatie\Activitylog\Models\Activity::where('subject_type', Transaction::class)
            ->where('subject_id', $transaction->id)
            ->orderByDesc('created_at')
            ->get();

        $payments = $transaction->payments()->orderByDesc('created_at')->get();

        return view('transaction.history', compact('transaction', 'histories', 'payments'));
    }

    // -----------------------------------------------------------------------
    // Réservations du client connecté
    // -----------------------------------------------------------------------

    public function myReservations(\Illuminate\Http\Request $request)
    {
        $customer = auth()->user()->customer;

        $transactions = $customer
            ? Transaction::where('customer_id', $customer->id)
                ->whereNotIn('status', ['cancelled', 'completed', 'no_show'])
                ->with(['room.type'])
                ->orderByDesc('created_at')
                ->get()
            : collect();

        $transactionsExpired = $customer
            ? Transaction::where('customer_id', $customer->id)
                ->whereIn('status', ['completed', 'cancelled', 'no_show'])
                ->with(['room.type'])
                ->orderByDesc('created_at')
                ->get()
            : collect();

        return view('transaction.my-reservations', compact('transactions', 'transactionsExpired'));
    }

    // -----------------------------------------------------------------------
    // Export CSV
    // -----------------------------------------------------------------------

    public function export(string $type)
    {
        $this->authorize('viewAny', Transaction::class);

        $transactions = Transaction::with(['customer', 'room', 'user'])
            ->orderByDesc('created_at')
            ->get();

        $filename = 'transactions_' . now()->format('Ymd_His') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($transactions) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8
            fputcsv($out, ['#', 'Client', 'Chambre', 'Arrivée', 'Départ', 'Statut', 'Prix total', 'Total payé', 'Reste'], ';');
            foreach ($transactions as $tx) {
                fputcsv($out, [
                    $tx->id,
                    optional($tx->customer)->name,
                    optional($tx->room)->number,
                    $tx->check_in?->format('d/m/Y'),
                    $tx->check_out?->format('d/m/Y'),
                    $tx->status_label,
                    $tx->getTotalPrice(),
                    $tx->getTotalPayment(),
                    $tx->getRemainingPayment(),
                ], ';');
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    // -----------------------------------------------------------------------
    // Late checkout
    // -----------------------------------------------------------------------

    public function lateCheckout(\Illuminate\Http\Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $request->validate([
            'expected_checkout_time' => ['required', 'string'],
            'late_checkout_fee'      => ['required', 'numeric', 'min:0'],
            'payment_method'         => ['required', 'string', 'in:cash,card,transfer,mobile_money'],
        ]);

        $fee = (float) $request->late_checkout_fee;

        $transaction->update([
            'late_checkout'          => true,
            'late_checkout_fee'      => $fee,
            'expected_checkout_time' => $request->expected_checkout_time,
            'total_price'            => (float) $transaction->total_price + $fee,
        ]);

        if ($fee > 0) {
            Payment::create([
                'transaction_id' => $transaction->id,
                'created_by'     => auth()->id(),
                'user_id'        => auth()->id(),
                'amount'         => $fee,
                'status'         => Payment::STATUS_COMPLETED,
                'payment_method' => $request->payment_method,
                'payment_date'   => now(),
                'verified_by'    => auth()->id(),
                'verified_at'    => now(),
                'reference'      => 'LATE-' . $transaction->id . '-' . now()->format('YmdHis'),
                'description'    => 'Supplément late checkout — départ à ' . $request->expected_checkout_time,
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Late checkout enregistré et paiement créé.']);
        }

        return redirect()->back()->with('success', 'Late checkout enregistré et paiement encaissé.');
    }

    // -----------------------------------------------------------------------
    // Early checkout
    // -----------------------------------------------------------------------

    public function earlyCheckout(\Illuminate\Http\Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $request->validate([
            'refund_policy'        => ['required', 'in:full,partial,none'],
            'refund_amount'        => ['nullable', 'numeric', 'min:0'],
            'payment_method'       => ['required', 'string'],
            'early_checkout_reason'=> ['nullable', 'string', 'max:500'],
        ]);

        $today        = \Carbon\Carbon::today()->endOfDay();
        $actualNights = max(1, $transaction->check_in->diffInDays($today));
        $newPrice     = $actualNights * (float) optional($transaction->room)->price;

        $refundAmount = match($request->refund_policy) {
            'full'    => max(0, $transaction->getTotalPayment() - $newPrice),
            'partial' => (float) ($request->refund_amount ?? 0),
            default   => 0,
        };

        \Illuminate\Support\Facades\DB::transaction(function () use ($transaction, $today, $newPrice, $refundAmount, $request) {
            $transaction->update([
                'check_out'             => $today,
                'total_price'           => $newPrice,
                'status'                => 'completed',
                'actual_check_out'      => now(),
                'early_checkout'        => true,
                'early_checkout_refund' => $refundAmount,
                'early_checkout_reason' => $request->early_checkout_reason,
            ]);

            if ($refundAmount > 0) {
                \App\Models\Payment::create([
                    'user_id'        => auth()->id(),
                    'created_by'     => auth()->id(),
                    'transaction_id' => $transaction->id,
                    'amount'         => $refundAmount,
                    'payment_method' => $request->payment_method,
                    'status'         => \App\Models\Payment::STATUS_REFUNDED,
                    'reference'      => 'EARLY-' . $transaction->id . '-' . time(),
                    'description'    => 'Remboursement early checkout',
                    'payment_date'   => now(),
                ]);
            }

            if ($transaction->room) {
                $transaction->room->update(['room_status_id' => \App\Enums\RoomStatus::Dirty->value]);
            }
        });

        return redirect()->route('transaction.show', $transaction)
            ->with('success', 'Early checkout enregistré.' . ($refundAmount > 0 ? " Remboursement : " . number_format($refundAmount, 0, ',', ' ') . " FCFA." : ''));
    }

    // -----------------------------------------------------------------------
    // AJAX helpers
    // -----------------------------------------------------------------------

    public function checkEarlyCheckoutPossibility(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        return response()->json([
            'possible'   => $transaction->isActive(),
            'check_in'   => $transaction->check_in?->format('Y-m-d'),
            'check_out'  => $transaction->check_out?->format('Y-m-d'),
            'status'     => $transaction->status,
            'is_active'  => $transaction->isActive(),
        ]);
    }

    public function checkPaymentStatus(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        $transaction->updatePaymentStatus();

        return response()->json([
            'total_price'   => (float) $transaction->total_price,
            'total_payment' => $transaction->getTotalPayment(),
            'remaining'     => $transaction->getRemainingPayment(),
            'is_fully_paid' => $transaction->isFullyPaid(),
            'status'        => $transaction->status,
        ]);
    }

    public function checkIfCanComplete(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        return response()->json([
            'can_complete' => $transaction->canBeCheckedOut(),
            'is_active'    => $transaction->isActive(),
            'is_fully_paid' => $transaction->isFullyPaid(),
            'remaining'    => $transaction->getRemainingPayment(),
        ]);
    }

    public function compteSejour(Transaction $transaction)
    {
        $transaction->load(['customer', 'room.type', 'extras.user', 'restaurantOrders.items.menu', 'payments']);

        $roomSubtotal      = $transaction->room->price * $transaction->nights;
        $restaurantTotal   = $transaction->restaurantOrders->whereNotIn('status', ['paid', 'cancelled'])->sum('total');
        $extrasTotal       = $transaction->extras->sum(fn($e) => $e->amount * $e->quantity);
        $grandTotal        = $transaction->getTotalPrice();
        $totalPaid         = $transaction->getTotalPayment();
        $remaining         = max(0, $grandTotal - $totalPaid);

        $extraCategories   = \App\Models\TransactionExtra::getCategories();

        return view('transaction.compte-sejour', compact(
            'transaction',
            'roomSubtotal',
            'restaurantTotal',
            'extrasTotal',
            'grandTotal',
            'totalPaid',
            'remaining',
            'extraCategories'
        ));
    }

    public function checkAvailability(\Illuminate\Http\Request $request, Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        $request->validate([
            'check_in'  => ['required', 'date'],
            'check_out' => ['required', 'date', 'after:check_in'],
        ]);

        $available = Transaction::isRoomAvailableForPeriod(
            $transaction->room_id,
            $request->check_in,
            $request->check_out,
            $transaction->id
        );

        return response()->json(['available' => $available]);
    }

    public function showDetails(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        $transaction->load(['customer.user', 'room.type', 'user', 'payments']);

        return response()->json([
            'id'            => $transaction->id,
            'customer'      => optional($transaction->customer)->name,
            'room'          => optional($transaction->room)->number,
            'check_in'      => $transaction->check_in?->format('d/m/Y'),
            'check_out'     => $transaction->check_out?->format('d/m/Y'),
            'status'        => $transaction->status,
            'status_label'  => $transaction->status_label,
            'total_price'   => $transaction->getTotalPrice(),
            'total_payment' => $transaction->getTotalPayment(),
            'remaining'     => $transaction->getRemainingPayment(),
            'is_fully_paid' => $transaction->isFullyPaid(),
            'payments_count' => $transaction->payments->count(),
        ]);
    }

    public function lateCheckoutStatus(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        return response()->json([
            'success' => true,
            'data'    => $transaction->getLateCheckoutPaymentStatus(),
        ]);
    }

    public function markAsArrived(Transaction $transaction)
    {
        $this->authorize('updateStatus', Transaction::class);

        try {
            $this->checkInService->checkIn($transaction);

            return redirect()->back()->with('success',
                "Client marqué comme arrivé. Chambre {$transaction->room->number} maintenant occupée."
            );
        } catch (HotelException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function markAsDeparted(Request $request, Transaction $transaction)
    {
        $this->authorize('updateStatus', Transaction::class);

        try {
            $this->checkInService->checkOut($transaction);

            session()->flash('departure_success', [
                'title'         => 'Départ enregistré - Chambre à nettoyer',
                'message'       => 'Client parti. Chambre marquée "À NETTOYER".',
                'transaction_id' => $transaction->id,
                'room_number'   => optional($transaction->room)->number,
                'customer_name' => optional($transaction->customer)->name,
            ]);

            return redirect()->back()->with('success', 'Check-out effectué avec succès.');

        } catch (HotelException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // -----------------------------------------------------------------------
    // Helpers privés
    // -----------------------------------------------------------------------

    private function getAvailableAndOccupiedRooms(int $currentRoomId, $checkIn, $checkOut, int $excludeId): array
    {
        $occupiedIds = Transaction::whereNotIn('status', ['cancelled', 'completed', 'no_show'])
            ->where('id', '!=', $excludeId)
            ->where(fn ($q) => $q->where('check_in', '<', $checkOut)->where('check_out', '>', $checkIn))
            ->pluck('room_id')
            ->toArray();

        $allRooms = \App\Models\Room::with('type', 'roomStatus', 'images')->get();

        $available = $allRooms->filter(
            fn ($r) => ! in_array($r->id, $occupiedIds) || $r->id === $currentRoomId
        );

        $occupied = $allRooms->filter(
            fn ($r) => in_array($r->id, $occupiedIds) && $r->id !== $currentRoomId
        );

        return [$available, $occupied];
    }
}
