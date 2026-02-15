<?php

namespace App\Http\Controllers;

use App\Models\ReceptionistSession;
use App\Services\ReceptionistSessionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceptionistSessionController extends Controller
{
    protected $sessionService;

    public function __construct(ReceptionistSessionService $sessionService)
    {
        $this->sessionService = $sessionService;
        $this->middleware('auth');
    }

    // Démarrer une session
    public function startSession(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'Receptionist') {
            return response()->json(['error' => 'Accès réservé aux réceptionnistes'], 403);
        }

        if ($user->is_active_session) {
            return response()->json(['error' => 'Session déjà active'], 400);
        }

        $session = $this->sessionService->startSession($user, $request);

        return response()->json([
            'success' => true,
            'message' => 'Session démarrée avec succès',
            'session' => $session,
            'session_code' => $session->session_code,
        ]);
    }

    // Fermer une session
    public function closeSession(Request $request)
    {
        $user = Auth::user();
        $sessionId = $request->input('session_id');
        $force = $request->input('force', false);

        $session = $this->sessionService->closeSession($user, $sessionId, $force);

        if (! $session) {
            return response()->json(['error' => 'Session non trouvée'], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Session fermée avec succès',
            'session' => $session,
            'report' => $session->session_summary,
        ]);
    }

    // Obtenir la session active
    public function getActiveSession()
    {
        $user = Auth::user();

        if (! $user->is_active_session || ! $user->current_session_id) {
            return response()->json(['active' => false]);
        }

        $session = ReceptionistSession::with(['actions' => function ($query) {
            $query->orderBy('performed_at', 'desc')->take(10);
        }])->find($user->current_session_id);

        return response()->json([
            'active' => true,
            'session' => $session,
            'duration' => $session->getDurationFormatted(),
            'stats' => [
                'reservations' => $session->reservations_count,
                'checkins' => $session->checkins_count,
                'checkouts' => $session->checkouts_count,
                'total_amount' => $session->total_transactions_amount,
            ],
        ]);
    }

    // Historique des sessions
    public function getSessionHistory(Request $request)
    {
        $user = Auth::user();

        $query = ReceptionistSession::where('user_id', $user->id)
            ->orderBy('login_time', 'desc');

        if ($request->has('date')) {
            $query->whereDate('login_time', $request->date);
        }

        if ($request->has('status')) {
            $query->where('session_status', $request->status);
        }

        $sessions = $query->paginate(15);

        return response()->json([
            'sessions' => $sessions,
            'stats' => [
                'total_sessions' => $user->total_sessions,
                'total_reservations' => $user->total_reservations,
                'total_checkins' => $user->total_checkins,
                'total_checkouts' => $user->total_checkouts,
                'total_value' => $user->total_transactions_value,
            ],
        ]);
    }

    // Détails d'une session
    public function getSessionDetails($sessionId)
    {
        $session = ReceptionistSession::with(['actions.actionable', 'user'])
            ->findOrFail($sessionId);

        // Vérifier les permissions
        if (Auth::user()->role !== 'Super' &&
            Auth::user()->role !== 'Admin' &&
            Auth::user()->id !== $session->user_id) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $actions = $session->actions()
            ->with('actionable')
            ->orderBy('performed_at', 'desc')
            ->paginate(20);

        return response()->json([
            'session' => $session,
            'actions' => $actions,
            'summary' => $session->session_summary,
            'performance' => $session->performance_metrics,
        ]);
    }

    // Générer un rapport PDF
    public function generateSessionReport($sessionId)
    {
        $session = ReceptionistSession::with(['actions', 'user'])->findOrFail($sessionId);

        // Vérifier les permissions
        if (Auth::user()->role !== 'Super' &&
            Auth::user()->role !== 'Admin' &&
            Auth::user()->id !== $session->user_id) {
            abort(403, 'Accès non autorisé');
        }

        $actions = $session->actions()
            ->orderBy('performed_at', 'asc')
            ->get();

        $pdf = PDF::loadView('pdf.session-report', [
            'session' => $session,
            'actions' => $actions,
            'generated_at' => now(),
        ]);

        $filename = "session-report-{$session->session_code}-".now()->format('Ymd-His').'.pdf';

        return $pdf->download($filename);
    }

    // Statistiques personnelles
    public function getPersonalStats(Request $request)
    {
        $user = Auth::user();

        $period = $request->input('period', 'month'); // day, week, month, year

        $query = ReceptionistSession::where('user_id', $user->id);

        switch ($period) {
            case 'day':
                $query->whereDate('login_time', today());
                break;
            case 'week':
                $query->whereBetween('login_time', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('login_time', now()->month)
                    ->whereYear('login_time', now()->year);
                break;
            case 'year':
                $query->whereYear('login_time', now()->year);
                break;
        }

        $sessions = $query->get();

        $stats = [
            'total_sessions' => $sessions->count(),
            'total_reservations' => $sessions->sum('reservations_count'),
            'total_checkins' => $sessions->sum('checkins_count'),
            'total_checkouts' => $sessions->sum('checkouts_count'),
            'total_payments' => $sessions->sum('payments_count'),
            'total_customers' => $sessions->sum('customer_creations'),
            'total_revenue' => $sessions->sum('total_transactions_amount'),
            'total_cash' => $sessions->sum('cash_handled'),
            'total_card' => $sessions->sum('card_handled'),
            'total_other' => $sessions->sum('other_payments_handled'),
            'average_productivity' => $sessions->avg('performance_metrics->productivity_score'),
            'average_session_duration' => $this->calculateAverageDuration($sessions),
        ];

        // Graphique des performances
        $performanceData = $sessions->map(function ($session) {
            return [
                'date' => $session->login_time->format('Y-m-d'),
                'productivity' => $session->getProductivityScore(),
                'reservations' => $session->reservations_count,
                'revenue' => $session->total_transactions_amount,
            ];
        });

        return response()->json([
            'stats' => $stats,
            'performance_data' => $performanceData,
            'period' => $period,
            'period_label' => $this->getPeriodLabel($period),
        ]);
    }

    private function calculateAverageDuration($sessions)
    {
        $totalMinutes = 0;
        $count = 0;

        foreach ($sessions as $session) {
            if ($session->logout_time) {
                $totalMinutes += $session->login_time->diffInMinutes($session->logout_time);
                $count++;
            }
        }

        return $count > 0 ? round($totalMinutes / $count) : 0;
    }

    private function getPeriodLabel($period)
    {
        $labels = [
            'day' => 'Aujourd\'hui',
            'week' => 'Cette semaine',
            'month' => 'Ce mois',
            'year' => 'Cette année',
        ];

        return $labels[$period] ?? $period;
    }
}
