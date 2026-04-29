<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\CashierSession;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use App\Services\CashierSessionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CashierSessionController extends Controller
{
    public function __construct(private CashierSessionService $sessionService)
    {
        $this->middleware('auth');
        $this->middleware('checkrole:Receptionist,Admin,Super,Cashier');
    }

    public function dashboard()
    {
        $user          = Auth::user();
        $activeSession = $this->sessionService->getActiveSession($user);
        $isAdmin       = $this->sessionService->isAdmin($user);

        if ($activeSession) {
            $activeSession->load(['payments' => function ($q) {
                $q->with(['transaction.customer'])
                    ->where('status', Payment::STATUS_COMPLETED)
                    ->orderByDesc('created_at');
            }]);
        }

        return view('cashier.dashboard', [
            'user'              => $user,
            'activeSession'     => $activeSession,
            'todayStats'        => $this->sessionService->getTodayStats($user),
            'pendingPayments'   => $this->sessionService->getPendingPayments($user),
            'recentSessions'    => $this->sessionService->getRecentSessions($user),
            'allActiveSessions' => $isAdmin ? $this->sessionService->getAllActiveSessions() : [],
            'canStartSession'   => $this->sessionService->canUserStartSession($user, $activeSession),
            'currentTime'       => now()->format('d/m/Y H:i:s'),
            'isReceptionist'    => $user->isReceptionist(),
            'isAdmin'           => $isAdmin,
            'isCashier'         => $user->isCashier() || $isAdmin,
            'allReceptionists'  => $isAdmin ? User::whereIn('role', ['Receptionist', 'Cashier', 'Admin', 'Super'])->orderBy('name')->get() : [],
            'allSessions'       => $isAdmin ? CashierSession::with('user')->orderByDesc('created_at')->paginate(10) : collect([]),
            'allSessionsCount'  => $isAdmin ? CashierSession::count() : 0,
        ]);
    }

    public function index()
    {
        $user = Auth::user();

        try {
            $sessions = $this->sessionService->isAdmin($user)
                ? CashierSession::with('user')->orderByDesc('created_at')->paginate(20)
                : CashierSession::where('user_id', $user->id)->orderByDesc('created_at')->paginate(20);

            $sessions->getCollection()->transform(function ($session) use ($user) {
                $session->payments_count    = Payment::where('cashier_session_id', $session->id)->where('status', Payment::STATUS_COMPLETED)->count();
                $session->payments_total    = Payment::where('cashier_session_id', $session->id)->where('status', Payment::STATUS_COMPLETED)->sum('amount') ?? 0;
                $session->formatted_duration = $this->sessionService->formatDuration($session);
                $session->can_view          = $this->sessionService->isAdmin($user) || $session->user_id === $user->id;
                return $session;
            });

            return view('cashier.sessions.index', compact('sessions', 'user'));
        } catch (\Throwable $e) {
            return redirect()->route('cashier.dashboard')->with('error', 'Erreur lors du chargement des sessions: '.$e->getMessage());
        }
    }

    public function create()
    {
        $user          = Auth::user();
        $activeSession = $this->sessionService->getActiveSession($user);

        if ($activeSession) {
            return redirect()->route('cashier.dashboard')->with('warning', "Vous avez déjà une session active. Veuillez la clôturer avant d'en démarrer une nouvelle.");
        }

        if (! $this->sessionService->canUserStartSession($user, $activeSession)) {
            return redirect()->route('cashier.dashboard')->with('error', "Vous n'avez pas les permissions nécessaires pour démarrer une session.");
        }

        return view('cashier.sessions.create', [
            'paymentMethods' => Payment::getPaymentMethods(),
            'user'           => $user,
            'defaultBalance' => 0,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(['notes' => 'nullable|string|max:500']);

        $user          = Auth::user();
        $activeSession = $this->sessionService->getActiveSession($user);

        if ($activeSession) {
            return redirect()->back()->with('error', 'Vous avez déjà une session active. ID: #'.$activeSession->id);
        }

        try {
            $now       = Carbon::now();
            $shiftType = $this->sessionService->determineShiftType($now);

            $session = CashierSession::create([
                'user_id'         => $user->id,
                'initial_balance' => 0,
                'current_balance' => 0,
                'start_time'      => $now,
                'status'          => 'active',
                'notes'           => $request->notes,
                'shift_type'      => $shiftType,
            ]);

            return redirect()->route('cashier.dashboard')->with('success', "Session démarrée avec succès! ID: #{$session->id} à ".$now->format('H:i'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Erreur lors du démarrage de la session: '.$e->getMessage())->withInput();
        }
    }

    public function show(CashierSession $cashierSession)
    {
        $user = Auth::user();

        if (! $this->sessionService->isAdmin($user) && $cashierSession->user_id !== $user->id) {
            return redirect()->route('cashier.dashboard')->with('error', "Vous n'avez pas accès à cette session.");
        }

        try {
            $payments = Payment::where('cashier_session_id', $cashierSession->id)
                ->with(['transaction.booking', 'user'])
                ->orderByDesc('created_at')
                ->paginate(15);

            $stats = [
                'totalPayments'   => Payment::where('cashier_session_id', $cashierSession->id)->where('status', Payment::STATUS_COMPLETED)->count(),
                'totalAmount'     => Payment::where('cashier_session_id', $cashierSession->id)->where('status', Payment::STATUS_COMPLETED)->sum('amount') ?? 0,
                'pendingPayments' => Payment::where('cashier_session_id', $cashierSession->id)->where('status', Payment::STATUS_PENDING)->count(),
                'refundedAmount'  => Payment::where('cashier_session_id', $cashierSession->id)->where('status', Payment::STATUS_REFUNDED)->sum('amount') ?? 0,
            ];

            $cashierSession->formatted_duration = $this->sessionService->formatDuration($cashierSession);

            $paymentMethods = Payment::where('cashier_session_id', $cashierSession->id)
                ->where('status', Payment::STATUS_COMPLETED)
                ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
                ->groupBy('payment_method')
                ->get();

            return view('cashier.sessions.show', compact('cashierSession', 'payments', 'stats', 'paymentMethods', 'user'));
        } catch (\Throwable $e) {
            return redirect()->route('cashier.sessions.index')->with('error', 'Erreur lors du chargement de la session: '.$e->getMessage());
        }
    }

    public function edit(CashierSession $cashierSession)
    {
        $user = Auth::user();

        if (! $this->sessionService->isAdmin($user) && $cashierSession->user_id !== $user->id) {
            return redirect()->route('cashier.dashboard')->with('error', 'Action non autorisée.');
        }

        if ($cashierSession->status === 'closed') {
            return redirect()->route('cashier.sessions.show', $cashierSession)->with('error', 'Les sessions clôturées ne peuvent pas être modifiées.');
        }

        return view('cashier.sessions.edit', compact('cashierSession', 'user'));
    }

    public function update(Request $request, CashierSession $cashierSession)
    {
        $user = Auth::user();

        if (! $this->sessionService->isAdmin($user) && $cashierSession->user_id !== $user->id) {
            return redirect()->route('cashier.dashboard')->with('error', 'Action non autorisée.');
        }

        $request->validate(['notes' => 'nullable|string|max:500']);

        try {
            $cashierSession->update(['notes' => $request->notes]);
            return redirect()->route('cashier.sessions.show', $cashierSession)->with('success', 'Session mise à jour avec succès.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour: '.$e->getMessage());
        }
    }

    public function destroy(Request $request, CashierSession $cashierSession)
    {
        $user = Auth::user();

        if (! $this->sessionService->isAdmin($user) && $cashierSession->user_id !== $user->id) {
            return redirect()->route('cashier.dashboard')->with('error', 'Action non autorisée.');
        }

        if ($cashierSession->status !== 'active') {
            return redirect()->back()->with('error', "Cette session n'est pas active.");
        }

        try {
            $physicalBalance = (float) $request->input('final_balance', $cashierSession->current_balance);
            $session         = $this->sessionService->closeSession($cashierSession, $user, $physicalBalance, $request->input('closing_notes'));

            $endTime  = $session->end_time;
            $duration = $cashierSession->start_time->diffInMinutes($endTime);
            $hours    = (int) floor($duration / 60);
            $minutes  = $duration % 60;

            $message = '✅ Session #'.$session->id.' clôturée. Durée: '.($hours > 0 ? "{$hours}h " : '')."{$minutes}min.";

            if (abs($session->balance_difference ?? 0) > 0.01) {
                $message .= ' Différence: '.number_format($session->balance_difference, 0, ',', ' ').' FCFA';
            }

            return redirect()->route('cashier.dashboard')->with('success', $message);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Erreur lors de la clôture: '.$e->getMessage());
        }
    }

    public function getLiveStats()
    {
        $user  = Auth::user();
        $today = Carbon::today();

        try {
            return response()->json([
                'success'   => true,
                'stats'     => [
                    'todayBookings'    => Booking::whereDate('created_at', $today)->count(),
                    'todayRevenue'     => Payment::whereDate('created_at', $today)->where('status', Payment::STATUS_COMPLETED)->sum('amount') ?? 0,
                    'pendingPayments'  => Payment::where('status', Payment::STATUS_PENDING)->count(),
                    'activeSessions'   => CashierSession::where('status', 'active')->count(),
                    'userActiveSession' => ! is_null($this->sessionService->getActiveSession($user)),
                ],
                'timestamp' => now()->toDateTimeString(),
            ]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: '.$e->getMessage()], 500);
        }
    }

    public function checkActiveSession()
    {
        $user    = Auth::user();
        $session = $this->sessionService->getActiveSession($user);

        return response()->json([
            'success'         => true,
            'hasActiveSession' => ! is_null($session),
            'session'         => $session,
            'canStartSession' => $this->sessionService->canUserStartSession($user, $session),
        ]);
    }

    public function requireActiveSession()
    {
        $user    = Auth::user();
        $session = $this->sessionService->getActiveSession($user);

        if (! $session) {
            return response()->json([
                'success'  => false,
                'message'  => 'Aucune session active. Veuillez démarrer une session.',
                'redirect' => route('cashier.sessions.create'),
            ], 403);
        }

        return response()->json(['success' => true, 'session' => $session]);
    }

    public function dailyReport(Request $request)
    {
        $user = Auth::user();

        if (! $this->sessionService->isAdmin($user)) {
            return redirect()->route('cashier.dashboard')->with('error', 'Accès réservé aux administrateurs.');
        }

        $date     = $request->get('date', Carbon::today()->format('Y-m-d'));
        $sessions = CashierSession::whereDate('created_at', $date)->with('user')->orderByDesc('created_at')->get();
        $payments = Payment::whereDate('created_at', $date)->with(['cashierSession.user', 'transaction.booking'])->orderByDesc('created_at')->get();

        $stats = [
            'totalSessions'  => $sessions->count(),
            'activeSessions' => $sessions->where('status', 'active')->count(),
            'totalRevenue'   => $payments->where('status', Payment::STATUS_COMPLETED)->sum('amount') ?? 0,
            'totalRefunded'  => $payments->where('status', Payment::STATUS_REFUNDED)->sum('amount') ?? 0,
        ];

        return view('cashier.reports.daily', compact('sessions', 'payments', 'stats', 'date', 'user'));
    }

    public function receptionistReport(?int $userId = null)
    {
        $user = Auth::user();

        if (! $this->sessionService->isAdmin($user)) {
            return redirect()->route('cashier.dashboard')->with('error', 'Accès réservé aux administrateurs.');
        }

        try {
            if ($userId) {
                $receptionist = User::findOrFail($userId);
                $sessions     = CashierSession::where('user_id', $userId)->orderByDesc('created_at')->paginate(20);
                $stats        = $this->sessionService->getReceptionistStats($userId);

                return view('cashier.reports.receptionist', compact('receptionist', 'sessions', 'stats', 'user'));
            }

            $receptionists = User::whereIn('role', ['Receptionist', 'Cashier'])
                ->withCount([
                    'cashierSessions',
                    'cashierSessions as active_sessions_count' => fn ($q) => $q->where('status', 'active'),
                ])
                ->paginate(15);

            return view('cashier.reports.index', compact('receptionists', 'user'));
        } catch (\Throwable $e) {
            return redirect()->route('cashier.dashboard')->with('error', 'Erreur: '.$e->getMessage());
        }
    }

    public function detailedClosingReport(CashierSession $cashierSession)
    {
        $user = Auth::user();

        if (! $this->sessionService->isAdmin($user) && $cashierSession->user_id !== $user->id) {
            return redirect()->route('cashier.dashboard')->with('error', 'Accès non autorisé.');
        }

        if ($cashierSession->status !== 'closed') {
            return redirect()->route('cashier.sessions.show', $cashierSession)->with('error', 'La session doit être fermée pour générer le rapport.');
        }

        $transactions = Transaction::where('cashier_session_id', $cashierSession->id)
            ->with(['customer.user', 'room.type', 'payments'])
            ->orderByDesc('created_at')->get();

        $payments = Payment::where('cashier_session_id', $cashierSession->id)
            ->with(['transaction.customer', 'user'])
            ->orderByDesc('created_at')->get();

        $paymentMethodsAnalysis = [];
        foreach ($payments->groupBy('payment_method') as $method => $group) {
            $completed = $group->where('status', Payment::STATUS_COMPLETED);
            $refunded  = $group->where('status', Payment::STATUS_REFUNDED);

            $paymentMethodsAnalysis[$method] = [
                'total'        => $completed->sum('amount'),
                'refunded'     => $refunded->sum('amount'),
                'net'          => $completed->sum('amount') - abs($refunded->sum('amount')),
                'count'        => $completed->count(),
                'refund_count' => $refunded->count(),
            ];
        }

        $transactionStatusAnalysis = [
            'total'     => $transactions->count(),
            'active'    => $transactions->where('status', 'active')->count(),
            'completed' => $transactions->where('status', 'completed')->count(),
            'cancelled' => $transactions->where('status', 'cancelled')->count(),
        ];

        return view('cashier.reports.closing', compact(
            'cashierSession', 'transactions', 'payments', 'paymentMethodsAnalysis', 'transactionStatusAnalysis', 'user'
        ));
    }
}
