<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\CashierSession;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     * DASHBOARD PERSONNALISÉ
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Log pour débogage
        \Log::info('Dashboard accessed', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'email' => $user->email,
        ]);

        // Récupère la session active
        $activeSession = $this->getActiveSession($user);
        
        // Charger les paiements de la session active
        if ($activeSession) {
            $activeSession->load(['payments' => function($query) {
                $query->with(['transaction.customer'])
                    ->where('status', Payment::STATUS_COMPLETED)
                    ->orderBy('created_at', 'desc');
            }]);
            
            \Log::info('Session active avec paiements', [
                'session_id' => $activeSession->id,
                'payments_count' => $activeSession->payments->count(),
                'total' => $activeSession->current_balance,
                'start_time' => $activeSession->start_time->format('Y-m-d H:i:s')
            ]);
        }

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
            'isCashier' => $user->role === 'Receptionist' || $user->role === 'Admin' || $user->role === 'Super', // CORRIGÉ
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
            if (method_exists($user, 'activeCashierSession')) {
                return $user->activeCashierSession;
            }

            return CashierSession::where('user_id', $user->id)
                ->where('status', 'active')
                ->first();

        } catch (\Exception $e) {
            \Log::error('Error getting active session', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Statistiques du jour - Adapté pour le profil connecté
     */
    private function getTodayStats($user)
    {
        $today = Carbon::today();

        try {
            // Récupérer la session active de l'utilisateur
            $activeSession = $this->getActiveSession($user);
            
            // Revenu = solde de la session active
            $userRevenue = $activeSession ? $activeSession->current_balance : 0;

            // =====================================================
            // 1. RÉSERVATIONS du profil aujourd'hui
            // Utilise user_id (créateur de la transaction)
            // =====================================================
            $reservations = 0;
            try {
                $reservations = Transaction::where('user_id', $user->id)
                    ->whereDate('created_at', $today)
                    ->count();
                    
                \Log::info('Réservations du profil', [
                    'user_id' => $user->id,
                    'count' => $reservations
                ]);
            } catch (\Exception $e) {
                \Log::warning('Erreur comptage réservations', ['error' => $e->getMessage()]);
            }

            // =====================================================
            // 2. CHECK-INS du profil aujourd'hui
            // Utilise checked_in_by
            // =====================================================
            $checkins = 0;
            try {
                $checkins = Transaction::where('checked_in_by', $user->id)
                    ->whereDate('actual_check_in', $today)
                    ->where('status', 'active')
                    ->count();
                    
                \Log::info('Check-ins du profil', [
                    'user_id' => $user->id,
                    'count' => $checkins
                ]);
            } catch (\Exception $e) {
                \Log::warning('Erreur comptage check-ins', ['error' => $e->getMessage()]);
            }

            // =====================================================
            // 3. CHECK-OUTS du profil aujourd'hui
            // Utilise checked_out_by
            // =====================================================
            $checkouts = 0;
            try {
                $checkouts = Transaction::where('checked_out_by', $user->id)
                    ->whereDate('actual_check_out', $today)
                    ->where('status', 'completed')
                    ->count();
                    
                \Log::info('Check-outs du profil', [
                    'user_id' => $user->id,
                    'count' => $checkouts
                ]);
            } catch (\Exception $e) {
                \Log::warning('Erreur comptage check-outs', ['error' => $e->getMessage()]);
            }

            // =====================================================
            // 4. PAIEMENTS COMPLÉTÉS du profil aujourd'hui
            // Utilise user_id dans la table payments
            // =====================================================
            $completedPayments = 0;
            try {
                $completedPayments = Payment::where('user_id', $user->id)
                    ->whereDate('created_at', $today)
                    ->where('status', Payment::STATUS_COMPLETED)
                    ->count();
            } catch (\Exception $e) {
                \Log::warning('Erreur comptage paiements', ['error' => $e->getMessage()]);
            }

            // =====================================================
            // 5. PAIEMENTS EN ATTENTE
            // =====================================================
            $pendingPayments = Payment::where('status', Payment::STATUS_PENDING)->count();

            return [
                'totalBookings' => $reservations,
                'checkins' => $checkins,
                'checkouts' => $checkouts,
                'completedPayments' => $completedPayments,
                'revenue' => $userRevenue,
                'pendingPayments' => $pendingPayments,
            ];
            
        } catch (\Exception $e) {
            \Log::error('Erreur getTodayStats', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
            
            return [
                'totalBookings' => 0,
                'checkins' => 0,
                'checkouts' => 0,
                'completedPayments' => 0,
                'revenue' => $activeSession->current_balance ?? 0,
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

            // Pour les non-admins : paiements de l'utilisateur connecté
            return Payment::where('status', Payment::STATUS_PENDING)
                ->where('user_id', $user->id)
                ->with(['transaction.booking.customer', 'transaction.booking.room'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            \Log::error('Erreur getPendingPayments', ['error' => $e->getMessage()]);
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
        if ($activeSession) {
            return false;
        }

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

                // Calculer la durée formatée
                $session->formatted_duration = $this->formatDuration($session);
                $session->can_view = $user->role === 'Admin' || $user->role === 'Super' || $session->user_id === $user->id;

                return $session;
            });

            return view('cashier.sessions.index', [
                'sessions' => $sessions,
                'user' => $user,
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in sessions index', ['error' => $e->getMessage()]);

            return redirect()->route('cashier.dashboard')
                ->with('error', 'Erreur lors du chargement des sessions: '.$e->getMessage());
        }
    }

    /**
     * Formater la durée d'une session
     */
    private function formatDuration($session)
    {
        if (!$session->end_time) {
            return 'En cours';
        }

        $minutes = $session->start_time->diffInMinutes($session->end_time);
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        if ($hours > 0) {
            return $hours . 'h ' . $remainingMinutes . 'min';
        }
        
        return $remainingMinutes . ' min';
    }

    /**
     * FORMULAIRE DE CRÉATION DE SESSION
     */
    public function create()
    {
        $user = Auth::user();

        $activeSession = $this->getActiveSession($user);

        if ($activeSession) {
            return redirect()->route('cashier.dashboard')
                ->with('warning', 'Vous avez déjà une session active. Veuillez la clôturer avant d\'en démarrer une nouvelle.');
        }

        if (! $this->canUserStartSession($user, $activeSession)) {
            return redirect()->route('cashier.dashboard')
                ->with('error', 'Vous n\'avez pas les permissions nécessaires pour démarrer une session.');
        }

        try {
            $paymentMethods = Payment::getPaymentMethods();

            return view('cashier.sessions.create', [
                'paymentMethods' => $paymentMethods,
                'user' => $user,
                'defaultBalance' => 0,
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in session create form', ['error' => $e->getMessage()]);

            return redirect()->route('cashier.dashboard')
                ->with('error', 'Erreur lors du chargement du formulaire: '.$e->getMessage());
        }
    }

    /**
     * STOCKAGE D'UNE NOUVELLE SESSION
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $activeSession = $this->getActiveSession($user);
        if ($activeSession) {
            return redirect()->back()
                ->with('error', 'Vous avez déjà une session active. ID: #'.$activeSession->id);
        }

        DB::beginTransaction();

        try {
            $now = Carbon::now();
            $hour = (int)$now->format('H');
            
            // Détermination du shift selon l'heure
            $shiftType = 'morning';
            
            if ($hour >= 5 && $hour < 12) {
                $shiftType = 'morning';
            } elseif ($hour >= 12 && $hour < 17) {
                $shiftType = 'afternoon';
            } elseif ($hour >= 17 && $hour < 22) {
                $shiftType = 'evening';
            } elseif ($hour >= 22 || $hour < 5) {
                $shiftType = 'night';
            }

            $session = CashierSession::create([
                'user_id' => $user->id,
                'initial_balance' => 0,
                'current_balance' => 0,
                'start_time' => $now,
                'status' => 'active',
                'notes' => $request->notes,
                'shift_type' => $shiftType,
            ]);

            DB::commit();

            \Log::info('Session started', [
                'session_id' => $session->id,
                'user_id' => $user->id,
                'start_time' => $session->start_time->format('Y-m-d H:i:s'),
                'shift_type' => $shiftType,
            ]);

            return redirect()->route('cashier.dashboard')
                ->with('success', 'Session démarrée avec succès! ID: #'.$session->id . ' à ' . $now->format('H:i'));

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Error starting session', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Erreur lors du démarrage de la session: '.$e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * AFFICHAGE D'UNE SESSION
     */
    public function show(CashierSession $cashierSession)
    {
        $user = Auth::user();

        \Log::info('=== ACCÈS À SHOW SESSION ===', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'session_id' => $cashierSession->id,
            'session_user_id' => $cashierSession->user_id,
            'start_time' => $cashierSession->start_time->format('Y-m-d H:i:s'),
            'end_time' => $cashierSession->end_time ? $cashierSession->end_time->format('Y-m-d H:i:s') : null,
        ]);

        if ($user->role !== 'Admin' && $user->role !== 'Super' && $cashierSession->user_id !== $user->id) {
            \Log::warning('🔴 ACCÈS REFUSÉ - Permission', [
                'user_role' => $user->role,
                'session_user_id' => $cashierSession->user_id,
                'current_user_id' => $user->id
            ]);
            
            return redirect()->route('cashier.dashboard')
                ->with('error', 'Vous n\'avez pas accès à cette session.');
        }

        \Log::info('✅ ACCÈS AUTORISÉ', [
            'session_id' => $cashierSession->id
        ]);

        try {
            $payments = Payment::where('cashier_session_id', $cashierSession->id)
                ->with(['transaction.booking', 'user'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            \Log::info('Paiements récupérés', [
                'count' => $payments->count()
            ]);

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

            // Ajouter la durée formatée
            $cashierSession->formatted_duration = $this->formatDuration($cashierSession);

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
                'user' => $user,
            ]);

        } catch (\Exception $e) {
            \Log::error('🔴 ERREUR show session', [
                'session_id' => $cashierSession->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('cashier.sessions.index')
                ->with('error', 'Erreur lors du chargement de la session: '.$e->getMessage());
        }
    }

    /**
     * ÉDITION D'UNE SESSION
     */
    public function edit(CashierSession $cashierSession)
    {
        $user = Auth::user();

        if ($user->role !== 'Admin' && $user->role !== 'Super' && $cashierSession->user_id !== $user->id) {
            return redirect()->route('cashier.dashboard')
                ->with('error', 'Action non autorisée.');
        }

        if ($cashierSession->status === 'closed') {
            return redirect()->route('cashier.sessions.show', $cashierSession)
                ->with('error', 'Les sessions clôturées ne peuvent pas être modifiées.');
        }

        return view('cashier.sessions.edit', [
            'cashierSession' => $cashierSession,
            'user' => $user,
        ]);
    }

    /**
     * MISE À JOUR D'UNE SESSION
     */
    public function update(Request $request, CashierSession $cashierSession)
    {
        $user = Auth::user();

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
                'user_id' => $user->id,
            ]);

            return redirect()->route('cashier.sessions.show', $cashierSession)
                ->with('success', 'Session mise à jour avec succès.');

        } catch (\Exception $e) {
            \Log::error('Error updating session', [
                'session_id' => $cashierSession->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour: '.$e->getMessage());
        }
    }

    /**
     * CLÔTURE D'UNE SESSION
     */
    public function destroy(Request $request, CashierSession $cashierSession)
    {
        // AJOUTEZ CE DÉBOGAGE AU DÉBUT
        \Log::info('=== TENTATIVE DE CLÔTURE DÉTAILLÉE ===', [
            'user_id' => auth()->id(),
            'user_role' => auth()->user()->role,
            'session_id' => $cashierSession->id,
            'session_user_id' => $cashierSession->user_id,
            'session_status' => $cashierSession->status,
            'current_balance' => $cashierSession->current_balance,
            'initial_balance' => $cashierSession->initial_balance,
            'request_data' => $request->all(),
            'request_method' => $request->method(),
            'request_url' => $request->url(),
            'csrf_token' => $request->session()->token(),
        ]);

        $user = Auth::user();

        // Vérification des permissions
        if ($user->role !== 'Admin' && $user->role !== 'Super' && $cashierSession->user_id !== $user->id) {
            \Log::warning('🔴 PERMISSION REFUSÉE', [
                'user_role' => $user->role,
                'session_user_id' => $cashierSession->user_id,
                'current_user_id' => $user->id
            ]);
            return redirect()->route('cashier.dashboard')
                ->with('error', 'Action non autorisée.');
        }

        if ($cashierSession->status !== 'active') {
            \Log::warning('🔴 SESSION NON ACTIVE', ['status' => $cashierSession->status]);
            return redirect()->back()
                ->with('error', 'Cette session n\'est pas active.');
        }

        DB::beginTransaction();

        try {
            // Calculer les paiements
            $completedPayments = Payment::where('cashier_session_id', $cashierSession->id)
                ->where('status', Payment::STATUS_COMPLETED)
                ->sum('amount') ?? 0;

            $refundedPayments = Payment::where('cashier_session_id', $cashierSession->id)
                ->where('status', Payment::STATUS_REFUNDED)
                ->sum('amount') ?? 0;

            $theoreticalBalance = $cashierSession->initial_balance + $completedPayments - $refundedPayments;
            
            $physicalBalance = $request->input('final_balance', $cashierSession->current_balance);
            $difference = $physicalBalance - $theoreticalBalance;

            \Log::info('💰 CALCULS DE CLÔTURE', [
                'completed_payments' => $completedPayments,
                'refunded_payments' => $refundedPayments,
                'initial_balance' => $cashierSession->initial_balance,
                'theoretical_balance' => $theoreticalBalance,
                'physical_balance' => $physicalBalance,
                'current_balance' => $cashierSession->current_balance,
                'difference' => $difference
            ]);

            $endTime = Carbon::now();

            $updateData = [
                'final_balance' => $physicalBalance,
                'theoretical_balance' => $theoreticalBalance,
                'balance_difference' => $difference,
                'end_time' => $endTime,
                'status' => 'closed',
                'updated_at' => $endTime,
            ];

            // Ajouter closing_notes si présent
            if ($request->has('closing_notes')) {
                $updateData['closing_notes'] = $request->input('closing_notes');
            }

            // Mettre à jour les notes
            $notes = $cashierSession->notes ? $cashierSession->notes . "\n" : '';
            $updateData['notes'] = $notes . "Clôturée le " . $endTime->format('d/m/Y H:i') . " par " . $user->name;

            \Log::info('📝 MISE À JOUR SESSION', $updateData);

            $cashierSession->update($updateData);

            // Créer un paiement d'ajustement si différence
            if (abs($difference) > 0.01) {
                \Log::info('💰 CRÉATION PAIEMENT AJUSTEMENT', ['difference' => $difference]);
                
                $payment = Payment::create([
                    'user_id' => $user->id,
                    'created_by' => $user->id,
                    'transaction_id' => null,
                    'cashier_session_id' => $cashierSession->id,
                    'amount' => abs($difference),
                    'payment_method' => 'cash',
                    'status' => Payment::STATUS_COMPLETED,
                    'description' => $difference > 0 ? 'Excédent à la clôture' : 'Manquant à la clôture',
                    'reference' => 'ADJ-' . $cashierSession->id . '-' . time(),
                ]);
                
                \Log::info('✅ PAIEMENT AJUSTEMENT CRÉÉ', ['payment_id' => $payment->id]);
            }

            DB::commit();

            $duration = $cashierSession->start_time->diffInMinutes($endTime);
            $hours = floor($duration / 60);
            $minutes = $duration % 60;

            \Log::info('✅ SESSION CLÔTURÉE AVEC SUCCÈS', [
                'session_id' => $cashierSession->id,
                'duration' => $duration . ' minutes',
                'difference' => $difference
            ]);

            $message = '✅ Session #'.$cashierSession->id.' clôturée avec succès. ';
            $message .= 'Durée: ' . ($hours > 0 ? $hours . 'h ' : '') . $minutes . 'min. ';
            
            if (abs($difference) > 0.01) {
                $message .= 'Différence: ' . number_format($difference, 0, ',', ' ') . ' FCFA';
            }

            return redirect()->route('cashier.dashboard')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('❌ ERREUR CLÔTURE SESSION', [
                'session_id' => $cashierSession->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
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
                'timestamp' => now()->toDateTimeString(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: '.$e->getMessage(),
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
                'hasActiveSession' => ! is_null($session),
                'session' => $session,
                'canStartSession' => $this->canUserStartSession($user, $session),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * RAPPORT JOURNALIER
     */
    public function dailyReport(Request $request)
    {
        $user = Auth::user();

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
                'user' => $user,
            ]);

        } catch (\Exception $e) {
            \Log::error('Error generating daily report', ['error' => $e->getMessage()]);

            return redirect()->route('cashier.dashboard')
                ->with('error', 'Erreur lors de la génération du rapport: '.$e->getMessage());
        }
    }

    /**
     * DÉMARRER UNE SESSION (API alternative)
     */
    public function startSession(Request $request)
    {
        $user = Auth::user();

        $activeSession = $this->getActiveSession($user);
        if ($activeSession) {
            return response()->json([
                'success' => false,
                'message' => 'Vous avez déjà une session active',
            ], 400);
        }

        DB::beginTransaction();

        try {
            $now = Carbon::now();
            
            $session = CashierSession::create([
                'user_id' => $user->id,
                'initial_balance' => $request->initial_balance ?? 0,
                'current_balance' => $request->initial_balance ?? 0,
                'start_time' => $now,
                'status' => 'active',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Session démarrée',
                'session' => $session,
                'start_time' => $now->format('Y-m-d H:i:s')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erreur: '.$e->getMessage(),
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
                    'user' => $user,
                ]);
            }

            $receptionists = User::whereIn('role', ['Receptionist', 'Cashier'])
                ->withCount([
                    'cashierSessions',
                    'cashierSessions as active_sessions_count' => function ($query) {
                        $query->where('status', 'active');
                    },
                ])
                ->paginate(15);

            return view('cashier.reports.index', [
                'receptionists' => $receptionists,
                'user' => $user,
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in receptionist report', ['error' => $e->getMessage()]);

            return redirect()->route('cashier.dashboard')
                ->with('error', 'Erreur: '.$e->getMessage());
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
            'totalRevenue' => Payment::where('user_id', $userId)
                ->where('status', Payment::STATUS_COMPLETED)
                ->sum('amount') ?? 0,
            'avgSessionDuration' => $this->calculateAverageDuration($userId),
        ];
    }

    /**
     * Calculer la durée moyenne des sessions
     */
    private function calculateAverageDuration($userId)
    {
        $sessions = CashierSession::where('user_id', $userId)
            ->where('status', 'closed')
            ->whereNotNull('end_time')
            ->get();

        if ($sessions->isEmpty()) {
            return 0;
        }

        $totalMinutes = $sessions->sum(function ($session) {
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

        if (! $activeSession) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune session active. Veuillez démarrer une session.',
                'redirect' => route('cashier.sessions.create'),
            ], 403);
        }

        return response()->json([
            'success' => true,
            'session' => $activeSession,
        ]);
    }

    /**
     * ASSOCIER AUTOMATIQUEMENT UNE TRANSACTION À LA SESSION ACTIVE
     */
    public function autoLinkTransactionToSession(Transaction $transaction)
    {
        $user = Auth::user();
        $activeSession = $this->getActiveSession($user);

        if (! $activeSession) {
            return [
                'success' => false,
                'message' => 'Aucune session active',
            ];
        }

        try {
            $transaction->update([
                'cashier_session_id' => $activeSession->id,
            ]);

            $totalPayment = $transaction->getTotalPayment();
            if ($totalPayment > 0) {
                $activeSession->current_balance += $totalPayment;
                $activeSession->save();
            }

            return [
                'success' => true,
                'message' => 'Transaction associée à la session #'.$activeSession->id,
            ];

        } catch (\Exception $e) {
            \Log::error('Error linking transaction to session', [
                'transaction_id' => $transaction->id,
                'session_id' => $activeSession->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Erreur: '.$e->getMessage(),
            ];
        }
    }

    /**
     * RAPPORT DE CLÔTURE DÉTAILLÉ
     */
    public function detailedClosingReport(CashierSession $cashierSession)
    {
        $user = Auth::user();

        if ($user->role !== 'Admin' && $user->role !== 'Super' && $cashierSession->user_id !== $user->id) {
            return redirect()->route('cashier.dashboard')
                ->with('error', 'Accès non autorisé.');
        }

        if ($cashierSession->status !== 'closed') {
            return redirect()->route('cashier.sessions.show', $cashierSession)
                ->with('error', 'La session doit être fermée pour générer le rapport.');
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

            $paymentMethodsAnalysis = [];
            foreach ($payments->groupBy('payment_method') as $method => $methodPayments) {
                $completedPayments = $methodPayments->where('status', Payment::STATUS_COMPLETED);
                $refundedPayments = $methodPayments->where('status', Payment::STATUS_REFUNDED);

                $paymentMethodsAnalysis[$method] = [
                    'total' => $completedPayments->sum('amount'),
                    'refunded' => $refundedPayments->sum('amount'),
                    'net' => $completedPayments->sum('amount') - abs($refundedPayments->sum('amount')),
                    'count' => $completedPayments->count(),
                    'refund_count' => $refundedPayments->count(),
                ];
            }

            $transactionStatusAnalysis = [
                'total' => $transactions->count(),
                'active' => $transactions->where('status', 'active')->count(),
                'completed' => $transactions->where('status', 'completed')->count(),
                'cancelled' => $transactions->where('status', 'cancelled')->count(),
                'reservation' => $transactions->where('status', 'reservation')->count(),
            ];

            $financialSummary = [
                'initial_balance' => $cashierSession->initial_balance,
                'total_payments' => $payments->where('status', Payment::STATUS_COMPLETED)->sum('amount'),
                'total_refunds' => abs($payments->where('status', Payment::STATUS_REFUNDED)->sum('amount')),
                'theoretical_balance' => $cashierSession->theoretical_balance,
                'final_balance' => $cashierSession->final_balance,
                'balance_difference' => $cashierSession->balance_difference,
                'net_revenue' => $payments->where('status', Payment::STATUS_COMPLETED)->sum('amount')
                    - abs($payments->where('status', Payment::STATUS_REFUNDED)->sum('amount')),
            ];

            $duration = $cashierSession->start_time->diff($cashierSession->end_time);
            $performance = [
                'duration_hours' => $duration->h + ($duration->i / 60),
                'duration_formatted' => $this->formatDuration($cashierSession),
                'transactions_per_hour' => $duration->h > 0 ?
                    $transactions->count() / $duration->h : $transactions->count(),
                'revenue_per_hour' => $duration->h > 0 ?
                    $financialSummary['net_revenue'] / $duration->h : $financialSummary['net_revenue'],
                'payment_efficiency' => $transactions->count() > 0 ?
                    ($payments->count() / $transactions->count()) * 100 : 0,
            ];

            return view('cashier.sessions.detailed-report', [
                'cashierSession' => $cashierSession,
                'transactions' => $transactions,
                'payments' => $payments,
                'paymentMethodsAnalysis' => $paymentMethodsAnalysis,
                'transactionStatusAnalysis' => $transactionStatusAnalysis,
                'financialSummary' => $financialSummary,
                'performance' => $performance,
                'user' => $user,
            ]);

        } catch (\Exception $e) {
            \Log::error('Error generating detailed report', [
                'session_id' => $cashierSession->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('cashier.sessions.show', $cashierSession)
                ->with('error', 'Erreur lors de la génération du rapport: '.$e->getMessage());
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
                'message' => 'Action réservée aux administrateurs.',
            ], 403);
        }

        try {
            $action = $request->get('action', 'lock');
            $notes = $request->get('notes', '');

            if ($action === 'lock') {
                $cashierSession->update([
                    'status' => 'locked',
                    'notes' => $cashierSession->notes."\n[VERROUILLÉ par ".$user->name.' - '.now()->format('Y-m-d H:i:s').'] '.$notes,
                ]);

                $message = 'Session verrouillée avec succès';
            } else {
                $cashierSession->update([
                    'status' => 'closed',
                    'notes' => $cashierSession->notes."\n[DÉVERROUILLÉ par ".$user->name.' - '.now()->format('Y-m-d H:i:s').'] '.$notes,
                ]);

                $message = 'Session déverrouillée avec succès';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'session' => $cashierSession,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: '.$e->getMessage(),
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
                'message' => 'Accès non autorisé.',
            ], 403);
        }

        try {
            $issues = [];

            $transactionsWithoutPayments = Transaction::where('cashier_session_id', $cashierSession->id)
                ->whereDoesntHave('payments')
                ->count();

            if ($transactionsWithoutPayments > 0) {
                $issues[] = [
                    'type' => 'warning',
                    'message' => $transactionsWithoutPayments.' transaction(s) sans paiement',
                    'severity' => 'medium',
                ];
            }

            $pendingPayments = Payment::where('cashier_session_id', $cashierSession->id)
                ->where('status', Payment::STATUS_PENDING)
                ->count();

            if ($pendingPayments > 0) {
                $issues[] = [
                    'type' => 'warning',
                    'message' => $pendingPayments.' paiement(s) en attente',
                    'severity' => 'low',
                ];
            }

            if ($cashierSession->isActive()) {
                $duration = $cashierSession->start_time->diffInHours(now());
                if ($duration > 12) {
                    $issues[] = [
                        'type' => 'warning',
                        'message' => 'Session active depuis '.$duration.' heures',
                        'severity' => 'medium',
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'issues' => $issues,
                'session' => $cashierSession,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: '.$e->getMessage(),
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

            if ($format === 'excel' || $format === 'csv') {
                $data = [
                    'session' => $cashierSession,
                    'transactions' => $transactions,
                    'payments' => $payments,
                    'generated_at' => now()->format('Y-m-d H:i:s'),
                    'generated_by' => $user->name,
                ];

                return view('cashier.sessions.export', $data);
            }

            if ($format === 'pdf') {
                $data = [
                    'session' => $cashierSession,
                    'transactions' => $transactions,
                    'payments' => $payments,
                    'user' => $user,
                ];

                return view('cashier.sessions.pdf-export', $data);
            }

            return redirect()->route('cashier.sessions.show', $cashierSession)
                ->with('error', 'Format non supporté');

        } catch (\Exception $e) {
            \Log::error('Error exporting session', [
                'session_id' => $cashierSession->id,
                'format' => $format,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('cashier.sessions.show', $cashierSession)
                ->with('error', 'Erreur lors de l\'export: '.$e->getMessage());
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
            'reason' => 'required|string|max:500',
        ]);

        $newUser = User::findOrFail($request->new_user_id);

        if (! in_array($newUser->role, ['Receptionist', 'Admin', 'Super', 'Cashier'])) {
            return redirect()->back()
                ->with('error', 'Le nouvel utilisateur n\'a pas les droits nécessaires.');
        }

        DB::beginTransaction();

        try {
            $oldUserId = $cashierSession->user_id;

            $cashierSession->update([
                'user_id' => $newUser->id,
                'notes' => $cashierSession->notes."\n[TRANSFÉRÉ de ".User::find($oldUserId)->name.' à '.$newUser->name.' - '.now()->format('Y-m-d H:i:s').'] Raison: '.$request->reason,
            ]);

            DB::commit();

            \Log::info('Session transferred', [
                'session_id' => $cashierSession->id,
                'from_user' => $oldUserId,
                'to_user' => $newUser->id,
                'by_user' => $user->id,
            ]);

            return redirect()->route('cashier.sessions.show', $cashierSession)
                ->with('success', 'Session transférée avec succès à '.$newUser->name);

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Erreur lors du transfert: '.$e->getMessage());
        }
    }

    /**
     * API: RÉCUPÉRER LA SESSION ACTIVE D'UN UTILISATEUR
     */
    public function getUserActiveSession($userId)
    {
        $currentUser = Auth::user();

        if ($currentUser->role !== 'Admin' && $currentUser->role !== 'Super' && $currentUser->id != $userId) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé.',
            ], 403);
        }

        try {
            $session = CashierSession::where('user_id', $userId)
                ->where('status', 'active')
                ->first();

            if (! $session) {
                return response()->json([
                    'success' => true,
                    'has_active_session' => false,
                    'message' => 'Aucune session active',
                ]);
            }

            $session->load('user');

            return response()->json([
                'success' => true,
                'has_active_session' => true,
                'session' => $session,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: '.$e->getMessage(),
            ], 500);
        }
    }

   /**
 * Récupérer les statistiques en temps réel (AJAX) - CORRIGÉ
 */
public function liveStats(Request $request)
{
    try {
        $user = auth()->user();

        $currentSession = CashierSession::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (! $currentSession) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune session ouverte',
                'has_session' => false,
            ]);
        }

        // ✅ UTILISER LES COLONNES EXISTANTES
        $totalCash = $currentSession->cash_in ?? 0;
        $totalCard = $currentSession->card_total ?? 0;
        $totalMobile = $currentSession->mobile_total ?? 0;
        $totalOther = $currentSession->other_total ?? 0;
        $totalRefunds = $currentSession->refunds_total ?? 0;
        
        // ✅ Total général (sans compter les remboursements deux fois)
        $totalAmount = $totalCash + $totalCard + $totalMobile + $totalOther;
        
        // ✅ Calculer le nombre de transactions (à partir des paiements)
        $paymentsCount = Payment::where('cashier_session_id', $currentSession->id)
            ->where('status', Payment::STATUS_COMPLETED)
            ->count();

        // ✅ Transactions du jour associées à la session
        $transactions = Transaction::where('cashier_session_id', $currentSession->id)
            ->whereDate('created_at', now()->toDateString())
            ->get();

        $transactionsCount = $transactions->count();
        $transactionsAmount = $transactions->sum(function ($transaction) {
            return $transaction->getTotalPayment();
        });

        return response()->json([
            'success' => true,
            'has_session' => true,
            'session_id' => $currentSession->id,
            'session_start' => $currentSession->start_time->format('H:i'),
            'stats' => [
                // ✅ Totaux par méthode
                'total_amount' => number_format($totalAmount, 0, ',', ' ').' CFA',
                'total_cash' => number_format($totalCash, 0, ',', ' ').' CFA',
                'total_card' => number_format($totalCard, 0, ',', ' ').' CFA',
                'total_mobile' => number_format($totalMobile, 0, ',', ' ').' CFA',
                'total_other' => number_format($totalOther, 0, ',', ' ').' CFA',
                'total_refunds' => number_format($totalRefunds, 0, ',', ' ').' CFA',
                
                // ✅ Informations session
                'total_transactions' => $paymentsCount,
                'session_duration' => $currentSession->start_time->diffForHumans(now(), true),
                'current_balance' => number_format($currentSession->current_balance, 0, ',', ' ').' CFA',
                'initial_balance' => number_format($currentSession->initial_balance, 0, ',', ' ').' CFA',
                
                // ✅ Transactions du jour
                'today_transactions' => $transactionsCount,
                'today_amount' => number_format($transactionsAmount, 0, ',', ' ').' CFA',
            ],
            'updated_at' => now()->format('H:i:s'),
        ]);

    } catch (\Exception $e) {
        \Log::error('Erreur live stats caisse:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Erreur serveur',
            'error' => config('app.debug') ? $e->getMessage() : null,
        ], 500);
    }
}
    /**
 * Rapport détaillé d'une session - CORRIGÉ
 */
public function report(CashierSession $cashierSession)
{
    $user = Auth::user();

    if ($user->role !== 'Admin' && $user->role !== 'Super' && $cashierSession->user_id !== $user->id) {
        return redirect()->route('cashier.dashboard')
            ->with('error', 'Vous n\'avez pas accès à ce rapport.');
    }

    try {
        $payments = Payment::where('cashier_session_id', $cashierSession->id)
            ->with(['transaction.customer', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalCompleted = $payments->where('status', Payment::STATUS_COMPLETED)->sum('amount');
        $totalRefunded = $payments->where('status', Payment::STATUS_REFUNDED)->sum('amount');
        $paymentCount = $payments->where('status', Payment::STATUS_COMPLETED)->count();
        
        // ✅ Utiliser les colonnes de la session
        $byMethod = [
            'cash' => [
                'count' => $payments->where('status', Payment::STATUS_COMPLETED)->where('payment_method', 'cash')->count(),
                'total' => $payments->where('status', Payment::STATUS_COMPLETED)->where('payment_method', 'cash')->sum('amount'),
                'method' => 'Espèces',
                'icon' => 'fa-money-bill-wave',
                'session_total' => $cashierSession->cash_in ?? 0,
            ],
            'card' => [
                'count' => $payments->where('status', Payment::STATUS_COMPLETED)->whereIn('payment_method', ['card', 'fedapay'])->count(),
                'total' => $payments->where('status', Payment::STATUS_COMPLETED)->whereIn('payment_method', ['card', 'fedapay'])->sum('amount'),
                'method' => 'Carte bancaire',
                'icon' => 'fa-credit-card',
                'session_total' => $cashierSession->card_total ?? 0,
            ],
            'mobile_money' => [
                'count' => $payments->where('status', Payment::STATUS_COMPLETED)->where('payment_method', 'mobile_money')->count(),
                'total' => $payments->where('status', Payment::STATUS_COMPLETED)->where('payment_method', 'mobile_money')->sum('amount'),
                'method' => 'Mobile Money',
                'icon' => 'fa-mobile-alt',
                'session_total' => $cashierSession->mobile_total ?? 0,
            ],
            'other' => [
                'count' => $payments->where('status', Payment::STATUS_COMPLETED)->whereNotIn('payment_method', ['cash', 'card', 'fedapay', 'mobile_money'])->count(),
                'total' => $payments->where('status', Payment::STATUS_COMPLETED)->whereNotIn('payment_method', ['cash', 'card', 'fedapay', 'mobile_money'])->sum('amount'),
                'method' => 'Autres',
                'icon' => 'fa-circle',
                'session_total' => $cashierSession->other_total ?? 0,
            ],
        ];

        $startTime = $cashierSession->start_time;
        $endTime = $cashierSession->end_time ?? now();
        $duration = $startTime->diff($endTime);
        $durationFormatted = $duration->format('%h heures %i minutes');

        // ✅ Calculer le total des encaissements (sans remboursements)
        $totalIncome = $cashierSession->cash_in + $cashierSession->card_total + 
                      $cashierSession->mobile_total + $cashierSession->other_total;

        return view('cashier.sessions.report', [
            'session' => $cashierSession,
            'payments' => $payments,
            'totalCompleted' => $totalCompleted,
            'totalRefunded' => $totalRefunded,
            'paymentCount' => $paymentCount,
            'byMethod' => $byMethod,
            'durationFormatted' => $durationFormatted,
            'user' => $user,
            'totalIncome' => $totalIncome,
            'summary' => [
                'initial_balance' => $cashierSession->initial_balance,
                'current_balance' => $cashierSession->current_balance,
                'final_balance' => $cashierSession->final_balance,
                'theoretical_balance' => $cashierSession->theoretical_balance,
                'balance_difference' => $cashierSession->balance_difference,
                'total_refunds' => $cashierSession->refunds_total,
                'total_income' => $totalIncome,
                'shift_type' => $cashierSession->shift_type,
            ]
        ]);

    } catch (\Exception $e) {
        \Log::error('Erreur génération rapport', [
            'session_id' => $cashierSession->id,
            'error' => $e->getMessage()
        ]);

        return redirect()->route('cashier.sessions.show', $cashierSession)
            ->with('error', 'Erreur lors de la génération du rapport: ' . $e->getMessage());
    }
}
}