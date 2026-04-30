<?php

namespace App\Http\Controllers;

use App\Exceptions\HotelException;
use App\Http\Requests\UpdateTransactionRequest;
use App\Http\Requests\UpdateTransactionStatusRequest;
use App\Models\Transaction;
use App\Repositories\Interface\TransactionRepositoryInterface;
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

        $allRooms = \App\Models\Room::with('type', 'roomStatus')->get();

        $available = $allRooms->filter(
            fn ($r) => ! in_array($r->id, $occupiedIds) || $r->id === $currentRoomId
        );

        $occupied = $allRooms->filter(
            fn ($r) => in_array($r->id, $occupiedIds) && $r->id !== $currentRoomId
        );

        return [$available, $occupied];
    }
}
