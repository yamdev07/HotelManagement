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
        
        // Pour admin: récupérer tous les réceptionnistes
        $allReceptionists = [];
        if ($user->role === 'Admin' || $user->role === 'Super') {
            $allReceptionists = User::whereIn('role', ['Receptionist', 'Cashier', 'Admin', 'Super'])
                ->orderBy('name')
                ->get();
        }
        
        // Récupérer toutes les sessions pour admin
        $allSessions = collect([]);
        $allSessionsCount = 0;
        if ($user->role === 'Admin' || $user->role === 'Super') {
            $allSessions = CashierSession::with('user')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            $allSessionsCount = CashierSession::count();
        }
        
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
            // AJOUTEZ CES VARIABLES
            'allReceptionists' => $allReceptionists,
            'allSessions' => $allSessions,
            'allSessionsCount' => $allSessionsCount,
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

        /**
     * VÉRIFIER SI UNE SESSION ACTIVE EST NÉCESSAIRE POUR UNE ACTION
     */
    public function requireActiveSession(Request $request)
    {
        $user = Auth::user();
        $activeSession = $this->getActiveSession($user);
        
        if (!$activeSession) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune session active. Veuillez démarrer une session.',
                'redirect' => route('cashier.sessions.create')
            ], 403);
        }
        
        return response()->json([
            'success' => true,
            'session' => $activeSession
        ]);
    }

    /**
     * ASSOCIER AUTOMATIQUEMENT UNE TRANSACTION À LA SESSION ACTIVE
     */
    public function autoLinkTransactionToSession(Transaction $transaction)
    {
        $user = Auth::user();
        $activeSession = $this->getActiveSession($user);
        
        if (!$activeSession) {
            return [
                'success' => false,
                'message' => 'Aucune session active'
            ];
        }
        
        try {
            // Associer la transaction à la session
            $transaction->update([
                'cashier_session_id' => $activeSession->id
            ]);
            
            // Mettre à jour le solde si paiement associé
            $totalPayment = $transaction->getTotalPayment();
            if ($totalPayment > 0) {
                $activeSession->current_balance += $totalPayment;
                $activeSession->save();
            }
            
            return [
                'success' => true,
                'message' => 'Transaction associée à la session #' . $activeSession->id
            ];
            
        } catch (\Exception $e) {
            \Log::error('Error linking transaction to session', [
                'transaction_id' => $transaction->id,
                'session_id' => $activeSession->id,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * RAPPORT DE CLÔTURE DÉTAILLÉ
     */
    public function detailedClosingReport(CashierSession $cashierSession)
    {
        $user = Auth::user();
        
        // Vérifier les permissions
        if ($user->role !== 'Admin' && $user->role !== 'Super' && $cashierSession->user_id !== $user->id) {
            return redirect()->route('cashier.dashboard')
                ->with('error', 'Accès non autorisé.');
        }
        
        // Vérifier que la session est fermée
        if ($cashierSession->status !== 'closed') {
            return redirect()->route('cashier.sessions.show', $cashierSession)
                ->with('error', 'La session doit être fermée pour générer le rapport.');
        }
        
        try {
            // Transactions de la session
            $transactions = Transaction::where('cashier_session_id', $cashierSession->id)
                ->with(['customer.user', 'room.type', 'payments'])
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Paiements de la session
            $payments = Payment::where('cashier_session_id', $cashierSession->id)
                ->with(['transaction.customer', 'user'])
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Analyse par méthode de paiement
            $paymentMethodsAnalysis = [];
            foreach ($payments->groupBy('payment_method') as $method => $methodPayments) {
                $completedPayments = $methodPayments->where('status', Payment::STATUS_COMPLETED);
                $refundedPayments = $methodPayments->where('status', Payment::STATUS_REFUNDED);
                
                $paymentMethodsAnalysis[$method] = [
                    'total' => $completedPayments->sum('amount'),
                    'refunded' => $refundedPayments->sum('amount'),
                    'net' => $completedPayments->sum('amount') - abs($refundedPayments->sum('amount')),
                    'count' => $completedPayments->count(),
                    'refund_count' => $refundedPayments->count()
                ];
            }
            
            // Analyse par statut de transaction
            $transactionStatusAnalysis = [
                'total' => $transactions->count(),
                'active' => $transactions->where('status', 'active')->count(),
                'completed' => $transactions->where('status', 'completed')->count(),
                'cancelled' => $transactions->where('status', 'cancelled')->count(),
                'reservation' => $transactions->where('status', 'reservation')->count(),
            ];
            
            // Analyse horaire
            $hourlyAnalysis = [];
            for ($hour = 0; $hour < 24; $hour++) {
                $hourStart = $cashierSession->start_time->copy()->setHour($hour)->setMinute(0);
                $hourEnd = $hourStart->copy()->addHour();
                
                $hourTransactions = $transactions->filter(function($transaction) use ($hourStart, $hourEnd) {
                    return $transaction->created_at->between($hourStart, $hourEnd);
                });
                
                $hourPayments = $payments->filter(function($payment) use ($hourStart, $hourEnd) {
                    return $payment->created_at->between($hourStart, $hourEnd);
                });
                
                $hourlyAnalysis[$hour] = [
                    'hour' => sprintf('%02d:00', $hour),
                    'transactions' => $hourTransactions->count(),
                    'payments' => $hourPayments->count(),
                    'revenue' => $hourPayments->where('status', Payment::STATUS_COMPLETED)->sum('amount'),
                    'average_payment' => $hourPayments->count() > 0 ? 
                        $hourPayments->where('status', Payment::STATUS_COMPLETED)->sum('amount') / $hourPayments->count() : 0
                ];
            }
            
            // Résumé financier
            $financialSummary = [
                'initial_balance' => $cashierSession->initial_balance,
                'total_payments' => $payments->where('status', Payment::STATUS_COMPLETED)->sum('amount'),
                'total_refunds' => abs($payments->where('status', Payment::STATUS_REFUNDED)->sum('amount')),
                'theoretical_balance' => $cashierSession->theoretical_balance,
                'final_balance' => $cashierSession->final_balance,
                'balance_difference' => $cashierSession->balance_difference,
                'net_revenue' => $payments->where('status', Payment::STATUS_COMPLETED)->sum('amount') 
                    - abs($payments->where('status', Payment::STATUS_REFUNDED)->sum('amount'))
            ];
            
            // Performance
            $duration = $cashierSession->start_time->diff($cashierSession->end_time);
            $performance = [
                'duration_hours' => $duration->h + ($duration->i / 60),
                'transactions_per_hour' => $duration->h > 0 ? 
                    $transactions->count() / $duration->h : $transactions->count(),
                'revenue_per_hour' => $duration->h > 0 ? 
                    $financialSummary['net_revenue'] / $duration->h : $financialSummary['net_revenue'],
                'payment_efficiency' => $transactions->count() > 0 ? 
                    ($payments->count() / $transactions->count()) * 100 : 0
            ];
            
            return view('cashier.sessions.detailed-report', [
                'cashierSession' => $cashierSession,
                'transactions' => $transactions,
                'payments' => $payments,
                'paymentMethodsAnalysis' => $paymentMethodsAnalysis,
                'transactionStatusAnalysis' => $transactionStatusAnalysis,
                'hourlyAnalysis' => $hourlyAnalysis,
                'financialSummary' => $financialSummary,
                'performance' => $performance,
                'user' => $user
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error generating detailed report', [
                'session_id' => $cashierSession->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('cashier.sessions.show', $cashierSession)
                ->with('error', 'Erreur lors de la génération du rapport: ' . $e->getMessage());
        }
    }

    /**
     * VERROUILLER/DÉVERROUILLER UNE SESSION (Admin seulement)
     */
    public function toggleLock(CashierSession $cashierSession, Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'Admin' && $user->role !== 'Super') {
            return response()->json([
                'success' => false,
                'message' => 'Action réservée aux administrateurs.'
            ], 403);
        }
        
        try {
            $action = $request->get('action', 'lock');
            $notes = $request->get('notes', '');
            
            if ($action === 'lock') {
                $cashierSession->update([
                    'status' => 'locked',
                    'notes' => $cashierSession->notes . "\n[VERROUILLÉ par " . $user->name . " - " . now()->format('Y-m-d H:i:s') . "] " . $notes
                ]);
                
                $message = 'Session verrouillée avec succès';
            } else {
                $cashierSession->update([
                    'status' => 'closed',
                    'notes' => $cashierSession->notes . "\n[DÉVERROUILLÉ par " . $user->name . " - " . now()->format('Y-m-d H:i:s') . "] " . $notes
                ]);
                
                $message = 'Session déverrouillée avec succès';
            }
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'session' => $cashierSession
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * VÉRIFIER LA COHÉRENCE D'UNE SESSION
     */
    public function checkConsistency(CashierSession $cashierSession)
    {
        $user = Auth::user();
        
        if ($user->role !== 'Admin' && $user->role !== 'Super' && $cashierSession->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé.'
            ], 403);
        }
        
        try {
            $issues = [];
            
            // Vérifier les transactions sans paiements
            $transactionsWithoutPayments = Transaction::where('cashier_session_id', $cashierSession->id)
                ->whereDoesntHave('payments')
                ->count();
                
            if ($transactionsWithoutPayments > 0) {
                $issues[] = [
                    'type' => 'warning',
                    'message' => $transactionsWithoutPayments . ' transaction(s) sans paiement',
                    'severity' => 'medium'
                ];
            }
            
            // Vérifier les paiements en attente
            $pendingPayments = Payment::where('cashier_session_id', $cashierSession->id)
                ->where('status', Payment::STATUS_PENDING)
                ->count();
                
            if ($pendingPayments > 0) {
                $issues[] = [
                    'type' => 'warning',
                    'message' => $pendingPayments . ' paiement(s) en attente',
                    'severity' => 'low'
                ];
            }
            
            // Vérifier la cohérence des totaux
            $calculatedTheoreticalBalance = $cashierSession->calculateTheoreticalBalance();
            $balanceDifference = abs($calculatedTheoreticalBalance - $cashierSession->theoretical_balance);
            
            if ($balanceDifference > 1) { // Tolérance de 1 CFA
                $issues[] = [
                    'type' => 'error',
                    'message' => 'Incohérence dans le solde théorique: Différence de ' . number_format($balanceDifference, 2) . ' CFA',
                    'severity' => 'high'
                ];
            }
            
            // Vérifier la durée
            if ($cashierSession->isActive()) {
                $duration = $cashierSession->start_time->diffInHours(now());
                if ($duration > 12) {
                    $issues[] = [
                        'type' => 'warning',
                        'message' => 'Session active depuis ' . $duration . ' heures',
                        'severity' => 'medium'
                    ];
                }
            }
            
            return response()->json([
                'success' => true,
                'issues' => $issues,
                'session' => $cashierSession,
                'calculated_theoretical_balance' => $calculatedTheoreticalBalance,
                'balance_difference' => $balanceDifference
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * EXPORTER LES TRANSACTIONS D'UNE SESSION
     */
    public function exportSessionTransactions(CashierSession $cashierSession, $format = 'excel')
    {
        $user = Auth::user();
        
        if ($user->role !== 'Admin' && $user->role !== 'Super' && $cashierSession->user_id !== $user->id) {
            return redirect()->route('cashier.dashboard')
                ->with('error', 'Accès non autorisé.');
        }
        
        try {
            $transactions = Transaction::where('cashier_session_id', $cashierSession->id)
                ->with(['customer.user', 'room.type', 'payments'])
                ->orderBy('created_at', 'desc')
                ->get();
            
            $payments = Payment::where('cashier_session_id', $cashierSession->id)
                ->with(['transaction.customer', 'user'])
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Pour Excel/CSV
            if ($format === 'excel' || $format === 'csv') {
                $data = [
                    'session' => $cashierSession,
                    'transactions' => $transactions,
                    'payments' => $payments,
                    'generated_at' => now()->format('Y-m-d H:i:s'),
                    'generated_by' => $user->name
                ];
                
                // Ici, normalement vous utiliseriez un package Excel comme Maatwebsite/Laravel-Excel
                // Pour l'instant, on retourne une vue
                return view('cashier.sessions.export', $data);
            }
            
            // Pour PDF
            if ($format === 'pdf') {
                $data = [
                    'session' => $cashierSession,
                    'transactions' => $transactions,
                    'payments' => $payments,
                    'user' => $user
                ];
                
                // Normalement, utiliser DomPDF ou un autre package
                return view('cashier.sessions.pdf-export', $data);
            }
            
            return redirect()->route('cashier.sessions.show', $cashierSession)
                ->with('error', 'Format non supporté');
                
        } catch (\Exception $e) {
            \Log::error('Error exporting session', [
                'session_id' => $cashierSession->id,
                'format' => $format,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('cashier.sessions.show', $cashierSession)
                ->with('error', 'Erreur lors de l\'export: ' . $e->getMessage());
        }
    }

    /**
     * TRANSFÉRER UNE SESSION À UN AUTRE RÉCEPTIONNISTE (Admin seulement)
     */
    public function transferSession(CashierSession $cashierSession, Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'Admin' && $user->role !== 'Super') {
            return redirect()->route('cashier.dashboard')
                ->with('error', 'Action réservée aux administrateurs.');
        }
        
        $request->validate([
            'new_user_id' => 'required|exists:users,id',
            'reason' => 'required|string|max:500'
        ]);
        
        $newUser = User::findOrFail($request->new_user_id);
        
        // Vérifier que le nouvel utilisateur a les droits
        if (!in_array($newUser->role, ['Receptionist', 'Admin', 'Super', 'Cashier'])) {
            return redirect()->back()
                ->with('error', 'Le nouvel utilisateur n\'a pas les droits nécessaires.');
        }
        
        DB::beginTransaction();
        
        try {
            $oldUserId = $cashierSession->user_id;
            
            // Mettre à jour la session
            $cashierSession->update([
                'user_id' => $newUser->id,
                'notes' => $cashierSession->notes . "\n[TRANSFÉRÉ de " . User::find($oldUserId)->name . " à " . $newUser->name . " - " . now()->format('Y-m-d H:i:s') . "] Raison: " . $request->reason
            ]);
            
            DB::commit();
            
            \Log::info('Session transferred', [
                'session_id' => $cashierSession->id,
                'from_user' => $oldUserId,
                'to_user' => $newUser->id,
                'by_user' => $user->id
            ]);
            
            return redirect()->route('cashier.sessions.show', $cashierSession)
                ->with('success', 'Session transférée avec succès à ' . $newUser->name);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors du transfert: ' . $e->getMessage());
        }
    }

    /**
     * API: RÉCUPÉRER LA SESSION ACTIVE D'UN UTILISATEUR
     */
    public function getUserActiveSession($userId)
    {
        $currentUser = Auth::user();
        
        // Seuls les admins peuvent voir les sessions des autres
        if ($currentUser->role !== 'Admin' && $currentUser->role !== 'Super' && $currentUser->id != $userId) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé.'
            ], 403);
        }
        
        try {
            $session = CashierSession::where('user_id', $userId)
                ->where('status', 'active')
                ->first();
                
            if (!$session) {
                return response()->json([
                    'success' => true,
                    'has_active_session' => false,
                    'message' => 'Aucune session active'
                ]);
            }
            
            $session->load('user');
            
            return response()->json([
                'success' => true,
                'has_active_session' => true,
                'session' => $session
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer les statistiques en temps réel (AJAX)
     */
    public function liveStats(Request $request)
    {
        try {
            $user = auth()->user();
            
            // Récupérer la session en cours
            $currentSession = CashierSession::where('user_id', $user->id)
                ->whereNull('closed_at')
                ->first();
            
            if (!$currentSession) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune session ouverte',
                    'has_session' => false
                ]);
            }
            
            // Calculer les statistiques
            $totalCash = $currentSession->total_cash ?? 0;
            $totalCard = $currentSession->total_card ?? 0;
            $totalMobile = $currentSession->total_mobile ?? 0;
            $totalCheque = $currentSession->total_cheque ?? 0;
            $totalOther = $currentSession->total_other ?? 0;
            
            $totalTransactions = $currentSession->total_transactions ?? 0;
            $totalAmount = $totalCash + $totalCard + $totalMobile + $totalCheque + $totalOther;
            
            // Récupérer les transactions de la session
            $transactions = Transaction::where('cashier_session_id', $currentSession->id)
                ->whereDate('created_at', now()->toDateString())
                ->get();
            
            $transactionsCount = $transactions->count();
            $transactionsAmount = $transactions->sum(function($transaction) {
                return $transaction->getTotalPayment();
            });
            
            return response()->json([
                'success' => true,
                'has_session' => true,
                'session_id' => $currentSession->id,
                'session_start' => $currentSession->created_at->format('H:i'),
                'stats' => [
                    'total_amount' => number_format($totalAmount, 0, ',', ' ') . ' CFA',
                    'total_cash' => number_format($totalCash, 0, ',', ' ') . ' CFA',
                    'total_card' => number_format($totalCard, 0, ',', ' ') . ' CFA',
                    'total_mobile' => number_format($totalMobile, 0, ',', ' ') . ' CFA',
                    'total_cheque' => number_format($totalCheque, 0, ',', ' ') . ' CFA',
                    'total_other' => number_format($totalOther, 0, ',', ' ') . ' CFA',
                    'total_transactions' => $totalTransactions,
                    'session_duration' => $currentSession->created_at->diffForHumans(now(), true),
                    'today_transactions' => $transactionsCount,
                    'today_amount' => number_format($transactionsAmount, 0, ',', ' ') . ' CFA'
                ],
                'updated_at' => now()->format('H:i:s')
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erreur live stats caisse:', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}