<?php

namespace App\Http\Controllers;

use App\Models\CashierSession;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use App\Models\Room;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CashierSessionController extends Controller
{
    /**
     * Applique les middlewares d'authentification et de rôle
     * IMPORTANT: Utiliser les rôles EXACTEMENT comme dans la base (avec majuscule)
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('checkrole:Receptionist,Admin,Super,Cashier');
    }

    /**
     * DASHBOARD PERSONNALISÉ - Version corrigée
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Log pour débogage
        \Log::info('Dashboard accessed', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'email' => $user->email
        ]);
        
        // Récupère la session active
        $activeSession = $this->getActiveSession($user);
        
        // Statistiques du jour
        $todayStats = $this->getTodayStats($user);
        
        // Données pour la vue
        $data = [
            'user' => $user,
            'activeSession' => $activeSession,
            'todayStats' => $todayStats,
            'pendingPayments' => $this->getPendingPayments($user),
            'recentSessions' => $this->getRecentSessions($user),
            'allActiveSessions' => $user->role === 'Admin' || $user->role === 'Super' 
                ? $this->getAllActiveSessions() 
                : [],
            'canStartSession' => $this->canUserStartSession($user, $activeSession),
            'currentTime' => now()->format('d/m/Y H:i:s'),
            'isReceptionist' => $user->role === 'Receptionist',
            'isAdmin' => $user->role === 'Admin' || $user->role === 'Super',
            'isCashier' => $user->role === 'Cashier',
        ];
        
        return view('cashier.dashboard', $data);
    }
    
    /**
     * Récupère la session active de l'utilisateur
     */
    private function getActiveSession($user)
    {
        try {
            // Méthode 1: Via relation si elle existe
            if (method_exists($user, 'activeCashierSession')) {
                return $user->activeCashierSession;
            }
            
            // Méthode 2: Direct query
            return CashierSession::where('user_id', $user->id)
                ->where('status', 'active')
                ->first();
                
        } catch (\Exception $e) {
            \Log::error('Error getting active session', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
    
    /**
     * Statistiques du jour
     */
    private function getTodayStats($user)
    {
        $today = Carbon::today();
        
        try {
            return [
                'totalBookings' => Booking::whereDate('created_at', $today)->count(),
                'checkins' => Booking::whereDate('check_in', $today)->count(),
                'checkouts' => Booking::whereDate('check_out', $today)->count(),
                'completedPayments' => Payment::whereDate('created_at', $today)
                    ->where('status', Payment::STATUS_COMPLETED)
                    ->count(),
                'revenue' => Payment::whereDate('created_at', $today)
                    ->where('status', Payment::STATUS_COMPLETED)
                    ->sum('amount') ?? 0,
                'pendingPayments' => Payment::where('status', Payment::STATUS_PENDING)->count(),
            ];
        } catch (\Exception $e) {
            return [
                'totalBookings' => 0,
                'checkins' => 0,
                'checkouts' => 0,
                'completedPayments' => 0,
                'revenue' => 0,
                'pendingPayments' => 0,
            ];
        }
    }
    
    /**
     * Paiements en attente
     */
    private function getPendingPayments($user)
    {
        try {
            if ($user->role === 'Admin' || $user->role === 'Super') {
                return Payment::where('status', Payment::STATUS_PENDING)
                    ->with(['transaction.booking.customer', 'transaction.booking.room'])
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
            }
            
            return Payment::where('status', Payment::STATUS_PENDING)
                ->where('created_by', $user->id)
                ->with(['transaction.booking.customer', 'transaction.booking.room'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }
    
    /**
     * Sessions récentes
     */
    private function getRecentSessions($user)
    {
        try {
            if ($user->role === 'Admin' || $user->role === 'Super') {
                return CashierSession::with('user')
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
            }
            
            return CashierSession::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }
    
    /**
     * Toutes les sessions actives (pour admin)
     */
    private function getAllActiveSessions()
    {
        try {
            return CashierSession::with('user')
                ->where('status', 'active')
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }
    
    /**
     * Vérifie si l'utilisateur peut démarrer une session
     */
    private function canUserStartSession($user, $activeSession)
    {
        // Si déjà une session active, non
        if ($activeSession) {
            return false;
        }
        
        // Vérifie le rôle
        $allowedRoles = ['Receptionist', 'Admin', 'Super', 'Cashier'];
        return in_array($user->role, $allowedRoles);
    }

    /**
     * LISTE DES SESSIONS
     */
    public function index()
    {
        $user = Auth::user();
        
        try {
            if ($user->role === 'Admin' || $user->role === 'Super') {
                $sessions = CashierSession::with('user')
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);
            } else {
                $sessions = CashierSession::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);
            }
            
            // Ajoute des statistiques
            $sessions->getCollection()->transform(function ($session) use ($user) {
                $session->payments_count = Payment::where('cashier_session_id', $session->id)
                    ->where('status', Payment::STATUS_COMPLETED)
                    ->count();
                    
                $session->payments_total = Payment::where('cashier_session_id', $session->id)
                    ->where('status', Payment::STATUS_COMPLETED)
                    ->sum('amount') ?? 0;
                    
                $session->can_view = $user->role === 'Admin' || $user->role === 'Super' || $session->user_id === $user->id;
                
                return $session;
            });
            
            return view('cashier.sessions.index', [
                'sessions' => $sessions,
                'user' => $user
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in sessions index', ['error' => $e->getMessage()]);
            return redirect()->route('cashier.dashboard')
                ->with('error', 'Erreur lors du chargement des sessions: ' . $e->getMessage());
        }
    }

    /**
     * FORMULAIRE DE CRÉATION DE SESSION
     */
    public function create()
    {
        $user = Auth::user();
        
        // Vérifie si l'utilisateur a déjà une session active
        $activeSession = $this->getActiveSession($user);
        
        if ($activeSession) {
            return redirect()->route('cashier.dashboard')
                ->with('warning', 'Vous avez déjà une session active. Veuillez la clôturer avant d\'en démarrer une nouvelle.');
        }
        
        // Vérifie les permissions
        if (!$this->canUserStartSession($user, $activeSession)) {
            return redirect()->route('cashier.dashboard')
                ->with('error', 'Vous n\'avez pas les permissions nécessaires pour démarrer une session.');
        }
        
        try {
            $paymentMethods = Payment::getPaymentMethods();
            
            return view('cashier.sessions.create', [
                'paymentMethods' => $paymentMethods,
                'user' => $user,
                'defaultBalance' => 0
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in session create form', ['error' => $e->getMessage()]);
            return redirect()->route('cashier.dashboard')
                ->with('error', 'Erreur lors du chargement du formulaire: ' . $e->getMessage());
        }
    }

    /**
     * STOCKAGE D'UNE NOUVELLE SESSION
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Validation
        $request->validate([
            'initial_balance' => 'required|numeric|min:0',
            'payment_method' => 'required|in:' . implode(',', array_keys(Payment::getPaymentMethods())),
            'notes' => 'nullable|string|max:500',
        ]);
        
        // Vérifie si une session active existe déjà
        $activeSession = $this->getActiveSession($user);
        if ($activeSession) {
            return redirect()->back()
                ->with('error', 'Vous avez déjà une session active. ID: #' . $activeSession->id);
        }
        
        DB::beginTransaction();
        
        try {
            // Crée la session
            $session = CashierSession::create([
                'user_id' => $user->id,
                'initial_balance' => $request->initial_balance,
                'current_balance' => $request->initial_balance,
                'start_time' => Carbon::now(),
                'status' => 'active',
                'notes' => $request->notes,
            ]);
            
            // Crée un paiement d'ouverture si solde > 0
            if ($request->initial_balance > 0) {
                Payment::create([
                    'user_id' => $user->id,
                    'created_by' => $user->id,
                    'cashier_session_id' => $session->id,
                    'amount' => $request->initial_balance,
                    'status' => Payment::STATUS_COMPLETED,
                    'payment_method' => $request->payment_method,
                    'description' => 'Solde initial - Session #' . $session->id,
                    'reference' => 'OPEN-' . $session->id . '-' . time(),
                ]);
            }
            
            DB::commit();
            
            \Log::info('Session started', [
                'session_id' => $session->id,
                'user_id' => $user->id,
                'initial_balance' => $request->initial_balance
            ]);
            
            return redirect()->route('cashier.dashboard')
                ->with('success', 'Session démarrée avec succès! ID: #' . $session->id);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Error starting session', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->with('error', 'Erreur lors du démarrage de la session: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * AFFICHAGE D'UNE SESSION
     */
    public function show(CashierSession $cashierSession)
    {
        $user = Auth::user();
        
        // Vérifie les permissions
        if ($user->role !== 'Admin' && $user->role !== 'Super' && $cashierSession->user_id !== $user->id) {
            return redirect()->route('cashier.dashboard')
                ->with('error', 'Vous n\'avez pas accès à cette session.');
        }
        
        try {
            // Paiements associés
            $payments = Payment::where('cashier_session_id', $cashierSession->id)
                ->with(['transaction.booking', 'user'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
            
            // Statistiques
            $stats = [
                'totalPayments' => Payment::where('cashier_session_id', $cashierSession->id)
                    ->where('status', Payment::STATUS_COMPLETED)
                    ->count(),
                'totalAmount' => Payment::where('cashier_session_id', $cashierSession->id)
                    ->where('status', Payment::STATUS_COMPLETED)
                    ->sum('amount') ?? 0,
                'pendingPayments' => Payment::where('cashier_session_id', $cashierSession->id)
                    ->where('status', Payment::STATUS_PENDING)
                    ->count(),
                'refundedAmount' => Payment::where('cashier_session_id', $cashierSession->id)
                    ->where('status', Payment::STATUS_REFUNDED)
                    ->sum('amount') ?? 0,
            ];
            
            // Méthodes de paiement utilisées
            $paymentMethods = Payment::where('cashier_session_id', $cashierSession->id)
                ->where('status', Payment::STATUS_COMPLETED)
                ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
                ->groupBy('payment_method')
                ->get();
            
            return view('cashier.sessions.show', [
                'cashierSession' => $cashierSession,
                'payments' => $payments,
                'stats' => $stats,
                'paymentMethods' => $paymentMethods,
                'user' => $user
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error showing session', [
                'session_id' => $cashierSession->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('cashier.sessions.index')
                ->with('error', 'Erreur lors du chargement de la session: ' . $e->getMessage());
        }
    }

    /**
     * ÉDITION D'UNE SESSION
     */
    public function edit(CashierSession $cashierSession)
    {
        $user = Auth::user();
        
        // Vérifie les permissions
        if ($user->role !== 'Admin' && $user->role !== 'Super' && $cashierSession->user_id !== $user->id) {
            return redirect()->route('cashier.dashboard')
                ->with('error', 'Action non autorisée.');
        }
        
        // Empêche l'édition d'une session clôturée
        if ($cashierSession->status === 'closed') {
            return redirect()->route('cashier.sessions.show', $cashierSession)
                ->with('error', 'Les sessions clôturées ne peuvent pas être modifiées.');
        }
        
        return view('cashier.sessions.edit', [
            'cashierSession' => $cashierSession,
            'user' => $user
        ]);
    }

    /**
     * MISE À JOUR D'UNE SESSION
     */
    public function update(Request $request, CashierSession $cashierSession)
    {
        $user = Auth::user();
        
        // Vérifie les permissions
        if ($user->role !== 'Admin' && $user->role !== 'Super' && $cashierSession->user_id !== $user->id) {
            return redirect()->route('cashier.dashboard')
                ->with('error', 'Action non autorisée.');
        }
        
        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);
        
        try {
            $cashierSession->update([
                'notes' => $request->notes,
            ]);
            
            \Log::info('Session updated', [
                'session_id' => $cashierSession->id,
                'user_id' => $user->id
            ]);
            
            return redirect()->route('cashier.sessions.show', $cashierSession)
                ->with('success', 'Session mise à jour avec succès.');
                
        } catch (\Exception $e) {
            \Log::error('Error updating session', [
                'session_id' => $cashierSession->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    /**
     * CLÔTURE D'UNE SESSION
     */
    public function destroy(CashierSession $cashierSession)
    {
        $user = Auth::user();
        
        // Vérifie les permissions
        if ($user->role !== 'Admin' && $user->role !== 'Super' && $cashierSession->user_id !== $user->id) {
            return redirect()->route('cashier.dashboard')
                ->with('error', 'Action non autorisée.');
        }
        
        // Vérifie le statut
        if ($cashierSession->status !== 'active') {
            return redirect()->back()
                ->with('error', 'Cette session n\'est pas active.');
        }
        
        DB::beginTransaction();
        
        try {
            // Calculs
            $completedPayments = Payment::where('cashier_session_id', $cashierSession->id)
                ->where('status', Payment::STATUS_COMPLETED)
                ->sum('amount') ?? 0;
                
            $refundedPayments = Payment::where('cashier_session_id', $cashierSession->id)
                ->where('status', Payment::STATUS_REFUNDED)
                ->sum('amount') ?? 0;
                
            $theoreticalBalance = $cashierSession->initial_balance + $completedPayments - $refundedPayments;
            
            // Solde physique (par défaut le solde actuel)
            $physicalBalance = request('final_balance', $cashierSession->current_balance);
            $difference = $physicalBalance - $theoreticalBalance;
            
            // Met à jour la session
            $cashierSession->update([
                'final_balance' => $physicalBalance,
                'theoretical_balance' => $theoreticalBalance,
                'balance_difference' => $difference,
                'end_time' => Carbon::now(),
                'status' => 'closed',
                'closing_notes' => request('closing_notes', ''),
            ]);
            
            // Ajustement si différence
            if ($difference != 0) {
                Payment::create([
                    'user_id' => $user->id,
                    'created_by' => $user->id,
                    'cashier_session_id' => $cashierSession->id,
                    'amount' => abs($difference),
                    'status' => Payment::STATUS_COMPLETED,
                    'payment_method' => Payment::METHOD_CASH,
                    'description' => $difference > 0 ? 'Excédent à la clôture' : 'Manquant à la clôture',
                    'reference' => 'CLOSE-' . $cashierSession->id . '-' . time(),
                ]);
            }
            
            DB::commit();
            
            \Log::info('Session closed', [
                'session_id' => $cashierSession->id,
                'user_id' => $user->id,
                'difference' => $difference
            ]);
            
            $message = 'Session #' . $cashierSession->id . ' clôturée avec succès. ';
            $message .= 'Différence: ' . number_format($difference, 2) . ' FCFA';
            
            if ($user->role === 'Admin' || $user->role === 'Super') {
                return redirect()->route('cashier.sessions.index')
                    ->with('success', $message);
            }
            
            return redirect()->route('cashier.dashboard')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Error closing session', [
                'session_id' => $cashierSession->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->with('error', 'Erreur lors de la clôture: ' . $e->getMessage());
        }
    }

    /**
     * API: STATISTIQUES EN TEMPS RÉEL
     */
    public function getLiveStats()
    {
        $user = Auth::user();
        $today = Carbon::today();
        
        try {
            $stats = [
                'todayBookings' => Booking::whereDate('created_at', $today)->count(),
                'todayRevenue' => Payment::whereDate('created_at', $today)
                    ->where('status', Payment::STATUS_COMPLETED)
                    ->sum('amount') ?? 0,
                'pendingPayments' => Payment::where('status', Payment::STATUS_PENDING)->count(),
                'activeSessions' => CashierSession::where('status', 'active')->count(),
                'userActiveSession' => $this->getActiveSession($user) ? true : false,
            ];
            
            return response()->json([
                'success' => true,
                'stats' => $stats,
                'timestamp' => now()->toDateTimeString()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * API: VÉRIFIE LA SESSION ACTIVE
     */
    public function checkActiveSession()
    {
        $user = Auth::user();
        
        try {
            $session = $this->getActiveSession($user);
            
            return response()->json([
                'success' => true,
                'hasActiveSession' => !is_null($session),
                'session' => $session,
                'canStartSession' => $this->canUserStartSession($user, $session)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * RAPPORT JOURNALIER
     */
    public function dailyReport(Request $request)
    {
        $user = Auth::user();
        
        // Seulement pour admin/super
        if ($user->role !== 'Admin' && $user->role !== 'Super') {
            return redirect()->route('cashier.dashboard')
                ->with('error', 'Accès réservé aux administrateurs.');
        }
        
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        
        try {
            $sessions = CashierSession::whereDate('created_at', $date)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();
            
            $payments = Payment::whereDate('created_at', $date)
                ->with(['cashierSession.user', 'transaction.booking'])
                ->orderBy('created_at', 'desc')
                ->get();
            
            $stats = [
                'totalSessions' => $sessions->count(),
                'activeSessions' => $sessions->where('status', 'active')->count(),
                'totalRevenue' => $payments->where('status', Payment::STATUS_COMPLETED)->sum('amount') ?? 0,
                'totalRefunded' => $payments->where('status', Payment::STATUS_REFUNDED)->sum('amount') ?? 0,
            ];
            
            return view('cashier.reports.daily', [
                'sessions' => $sessions,
                'payments' => $payments,
                'stats' => $stats,
                'date' => $date,
                'user' => $user
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error generating daily report', ['error' => $e->getMessage()]);
            return redirect()->route('cashier.dashboard')
                ->with('error', 'Erreur lors de la génération du rapport: ' . $e->getMessage());
        }
    }
    
    /**
     * DÉMARRER UNE SESSION (API alternative)
     */
    public function startSession(Request $request)
    {
        $user = Auth::user();
        
        // Vérifie si déjà une session active
        $activeSession = $this->getActiveSession($user);
        if ($activeSession) {
            return response()->json([
                'success' => false,
                'message' => 'Vous avez déjà une session active'
            ], 400);
        }
        
        DB::beginTransaction();
        
        try {
            $session = CashierSession::create([
                'user_id' => $user->id,
                'initial_balance' => $request->initial_balance ?? 0,
                'current_balance' => $request->initial_balance ?? 0,
                'start_time' => Carbon::now(),
                'status' => 'active',
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Session démarrée',
                'session' => $session
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * RAPPORT POUR UN RÉCEPTIONNISTE SPÉCIFIQUE
     */
    public function receptionistReport($userId = null)
    {
        $user = Auth::user();
        
        if ($user->role !== 'Admin' && $user->role !== 'Super') {
            return redirect()->route('cashier.dashboard')
                ->with('error', 'Accès réservé aux administrateurs.');
        }
        
        try {
            if ($userId) {
                $receptionist = User::findOrFail($userId);
                
                $sessions = CashierSession::where('user_id', $userId)
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);
                
                $stats = $this->getReceptionistStats($userId);
                
                return view('cashier.reports.receptionist', [
                    'receptionist' => $receptionist,
                    'sessions' => $sessions,
                    'stats' => $stats,
                    'user' => $user
                ]);
            }
            
            // Liste tous les réceptionnistes
            $receptionists = User::whereIn('role', ['Receptionist', 'Cashier'])
                ->withCount([
                    'cashierSessions',
                    'cashierSessions as active_sessions_count' => function($query) {
                        $query->where('status', 'active');
                    }
                ])
                ->paginate(15);
            
            return view('cashier.reports.index', [
                'receptionists' => $receptionists,
                'user' => $user
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in receptionist report', ['error' => $e->getMessage()]);
            return redirect()->route('cashier.dashboard')
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
    
    /**
     * Statistiques pour un réceptionniste
     */
    private function getReceptionistStats($userId)
    {
        return [
            'totalSessions' => CashierSession::where('user_id', $userId)->count(),
            'activeSessions' => CashierSession::where('user_id', $userId)
                ->where('status', 'active')->count(),
            'totalRevenue' => Payment::where('created_by', $userId)
                ->where('status', Payment::STATUS_COMPLETED)
                ->sum('amount') ?? 0,
            'avgSessionDuration' => $this->calculateAverageDuration($userId),
        ];
    }
    
    private function calculateAverageDuration($userId)
    {
        $sessions = CashierSession::where('user_id', $userId)
            ->where('status', 'closed')
            ->whereNotNull('end_time')
            ->get();
            
        if ($sessions->isEmpty()) {
            return 0;
        }
        
        $totalMinutes = $sessions->sum(function($session) {
            return $session->start_time->diffInMinutes($session->end_time);
        });
        
        return round($totalMinutes / $sessions->count(), 1);
    }
}