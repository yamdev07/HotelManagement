<?php

namespace App\Http\Controllers;

use App\Exceptions\HotelException;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use App\Models\Transaction;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService,
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Payment::class);

        $query = Payment::with(['transaction.customer', 'transaction.room.type', 'user', 'cancelledByUser', 'createdBy'])
            ->orderBy('created_at', 'DESC');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('method')) {
            $query->where('payment_method', $request->method);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('reference', 'LIKE', "%{$search}%")
                    ->orWhereHas('transaction.customer', fn ($q) => $q->where('name', 'LIKE', "%{$search}%"))
                    ->orWhereHas('transaction.room', fn ($q) => $q->where('number', 'LIKE', "%{$search}%"));
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->paginate(20);

        $stats = [
            'total'        => Payment::count(),
            'total_amount' => Payment::where('status', Payment::STATUS_COMPLETED)->sum('amount'),
            'today'        => Payment::whereDate('created_at', today())->count(),
            'today_amount' => Payment::whereDate('created_at', today())->where('status', Payment::STATUS_COMPLETED)->sum('amount'),
            'cash'         => Payment::where('payment_method', Payment::METHOD_CASH)->where('status', Payment::STATUS_COMPLETED)->count(),
            'card'         => Payment::where('payment_method', Payment::METHOD_CARD)->where('status', Payment::STATUS_COMPLETED)->count(),
            'transfer'     => Payment::where('payment_method', Payment::METHOD_TRANSFER)->where('status', Payment::STATUS_COMPLETED)->count(),
            'mobile_money' => Payment::where('payment_method', Payment::METHOD_MOBILE_MONEY)->where('status', Payment::STATUS_COMPLETED)->count(),
        ];

        return view('payment.index', [
            'payments'       => $payments,
            'stats'          => $stats,
            'paymentMethods' => Payment::getPaymentMethods(),
            'statuses'       => [
                Payment::STATUS_PENDING   => 'En attente',
                Payment::STATUS_COMPLETED => 'Complété',
                Payment::STATUS_CANCELLED => 'Annulé',
            ],
        ]);
    }

    public function create(Transaction $transaction)
    {
        $this->authorize('create', Payment::class);

        $remaining = $transaction->getRemainingPayment();

        if ($remaining <= 0) {
            return redirect()->route('transaction.show', $transaction)
                ->with('info', 'Cette transaction est déjà entièrement payée.');
        }

        if (in_array($transaction->status, ['cancelled', 'no_show'])) {
            return redirect()->route('transaction.show', $transaction)
                ->with('error', 'Impossible d\'effectuer un paiement sur une transaction annulée ou no show.');
        }

        return view('transaction.payment.create', [
            'transaction'    => $transaction,
            'paymentMethods' => Payment::getPaymentMethods(),
        ]);
    }

    public function store(StorePaymentRequest $request, Transaction $transaction)
    {
        try {
            $payment = $this->paymentService->store($transaction, $request->validated());

            $transaction->refresh();
            $remaining    = $transaction->getRemainingPayment();
            $isFullyPaid  = $transaction->isFullyPaid();
            $amountFmt    = number_format($payment->amount, 0, ',', ' ');
            $message      = "Paiement de {$amountFmt} CFA enregistré avec succès.";

            if ($isFullyPaid) {
                $message .= ' Transaction entièrement réglée.';
            } elseif ($remaining > 0) {
                $message .= ' Solde restant : ' . number_format($remaining, 0, ',', ' ') . ' CFA.';
            }

            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success'      => true,
                    'message'      => $message,
                    'data'         => [
                        'payment'     => ['id' => $payment->id, 'reference' => $payment->reference, 'amount' => $payment->amount],
                        'transaction' => [
                            'id'           => $transaction->id,
                            'total_price'  => (float) $transaction->total_price,
                            'remaining'    => $remaining,
                            'is_fully_paid' => $isFullyPaid,
                        ],
                    ],
                    'redirect_url' => route('transaction.show', $transaction),
                ]);
            }

            return redirect()->route('transaction.show', $transaction)->with('success', $message);

        } catch (HotelException $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], $e->httpStatusCode());
            }
            return redirect()->back()->with('error', $e->getMessage())->withInput();

        } catch (\Exception $e) {
            Log::error('Erreur paiement', ['transaction_id' => $transaction->id, 'error' => $e->getMessage()]);
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Erreur interne.'], 500);
            }
            return redirect()->back()->with('error', 'Erreur interne lors du paiement.')->withInput();
        }
    }

    public function show(Payment $payment)
    {
        $this->authorize('view', $payment);

        $payment->load(['transaction.customer', 'transaction.room.type', 'user', 'createdBy', 'cancelledByUser']);
        $payment->transaction?->updatePaymentStatus();

        return view('payment.show', [
            'payment'        => $payment,
            'paymentMethods' => Payment::getPaymentMethods(),
        ]);
    }

    public function cancel(Request $request, Payment $payment)
    {
        $this->authorize('cancel', $payment);

        $request->validate(['cancel_reason' => ['required', 'string', 'max:500']]);

        if (! $payment->canBeCancelled()) {
            return redirect()->back()->with('error', 'Ce paiement ne peut pas être annulé.');
        }

        try {
            $this->paymentService->cancel($payment, $request->cancel_reason);

            return redirect()->route('payments.index')->with('success', 'Paiement annulé avec succès.');

        } catch (HotelException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Erreur annulation paiement', ['payment_id' => $payment->id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Erreur lors de l\'annulation.');
        }
    }

    public function refund(Request $request, Payment $payment)
    {
        $this->authorize('refund', Payment::class);

        $request->validate(['cancel_reason' => ['required', 'string', 'max:500']]);

        if (! $payment->canBeRefunded()) {
            return redirect()->back()->with('error', 'Ce paiement ne peut pas être remboursé.');
        }

        try {
            \DB::transaction(function () use ($payment, $request) {
                $refund = Payment::create([
                    'user_id'        => $payment->user_id,
                    'created_by'     => auth()->id(),
                    'transaction_id' => $payment->transaction_id,
                    'amount'         => -$payment->amount,
                    'payment_method' => Payment::METHOD_REFUND,
                    'status'         => Payment::STATUS_COMPLETED,
                    'reference'      => 'REFUND-' . ($payment->reference ?? 'PAY-' . $payment->id),
                    'description'    => 'Remboursement : ' . $request->cancel_reason,
                ]);

                $payment->markAsRefunded(auth()->id(), $request->cancel_reason);
                $payment->transaction?->updatePaymentStatus();

                activity()->performedOn($payment)->causedBy(auth()->user())
                    ->withProperties(['refund_payment_id' => $refund->id, 'reason' => $request->cancel_reason])
                    ->log('Paiement remboursé');
            });

            return redirect()->route('payments.index')->with('success', 'Paiement remboursé avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur remboursement', ['payment_id' => $payment->id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Erreur lors du remboursement.');
        }
    }

    public function invoice(Payment $payment)
    {
        $this->authorize('view', $payment);

        $payment->load(['transaction.customer', 'transaction.room.type', 'transaction.payments', 'user', 'createdBy']);

        if (! $payment->transaction) {
            return redirect()->back()->with('error', 'Transaction non trouvée pour ce paiement.');
        }

        $payment->transaction->updatePaymentStatus();
        $transaction  = $payment->transaction;
        $totalPrice   = $transaction->getTotalPrice();
        $totalPayment = Payment::getTotalForTransaction($payment->transaction_id);
        $remaining    = max(0, $totalPrice - $totalPayment);

        return view('payment.invoice', compact('payment', 'totalPrice', 'totalPayment', 'remaining', 'transaction'));
    }

    public function export(Request $request)
    {
        $this->authorize('viewAny', Payment::class);

        $query = Payment::with(['transaction.customer', 'user', 'createdBy'])
            ->orderBy('created_at', 'DESC');

        if ($request->filled('method'))    $query->where('payment_method', $request->method);
        if ($request->filled('status'))    $query->where('status', $request->status);
        if ($request->filled('date_from')) $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->filled('date_to'))   $query->whereDate('created_at', '<=', $request->date_to);

        $payments = $query->get();
        $csv      = fopen('php://temp', 'w');

        fputcsv($csv, ['ID', 'Date', 'Référence', 'Client', 'Transaction', 'Montant (CFA)', 'Méthode', 'Statut', 'Description', 'Créé par']);

        foreach ($payments as $p) {
            fputcsv($csv, [
                $p->id,
                $p->created_at->format('d/m/Y H:i'),
                $p->reference,
                optional(optional($p->transaction)->customer)->name ?? 'N/A',
                $p->transaction_id,
                number_format($p->amount, 0, ',', ' '),
                $p->payment_method_label,
                $p->status_text,
                $p->description ?? '',
                optional($p->createdBy)->name ?? 'N/A',
            ]);
        }

        rewind($csv);
        $data = stream_get_contents($csv);
        fclose($csv);

        return response($data)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="paiements_' . date('Y-m-d_H-i') . '.csv"');
    }

    public function checkTransactionStatus(Transaction $transaction)
    {
        $transaction->updatePaymentStatus();
        $transaction->refresh();

        return response()->json([
            'total_price'   => $transaction->total_price,
            'total_payment' => $transaction->total_payment,
            'remaining'     => $transaction->getRemainingPayment(),
            'payment_rate'  => $transaction->getPaymentRate(),
            'is_fully_paid' => $transaction->isFullyPaid(),
            'status'        => $transaction->status,
        ]);
    }
}
