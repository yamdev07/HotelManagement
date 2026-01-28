<?php

namespace App\Services;

use App\Models\ReceptionistSession;
use App\Models\User;
use App\Models\ReceptionistAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReceptionistSessionService
{
    public function startSession(User $user, Request $request): ReceptionistSession
    {
        return DB::transaction(function () use ($user, $request) {
            // Fermer toute session active existante (au cas où)
            $this->closeActiveSession($user);
            
            // Générer un code de session unique
            $sessionCode = 'SESS-' . now()->format('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
            
            // Créer la nouvelle session
            $session = ReceptionistSession::create([
                'user_id' => $user->id,
                'session_code' => $sessionCode,
                'login_time' => now(),
                'login_ip' => $request->ip(),
                'login_device' => $request->userAgent(),
                'login_location' => $this->getLocationFromIP($request->ip()),
                'session_status' => 'active'
            ]);
            
            // Mettre à jour l'utilisateur
            $user->update([
                'current_session_id' => $session->id,
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
                'is_active_session' => true,
                'total_sessions' => DB::raw('total_sessions + 1')
            ]);
            
            // Logger l'action de connexion
            $this->logAction($session, $user, 'session', 'login', null, [
                'ip' => $request->ip(),
                'device' => $request->userAgent()
            ]);
            
            return $session;
        });
    }
    
    public function closeSession(User $user, $sessionId = null, $force = false): ?ReceptionistSession
    {
        return DB::transaction(function () use ($user, $sessionId, $force) {
            // Récupérer la session
            if ($sessionId) {
                $session = ReceptionistSession::find($sessionId);
            } else {
                $session = ReceptionistSession::where('user_id', $user->id)
                    ->where('session_status', 'active')
                    ->first();
            }
            
            if (!$session) {
                return null;
            }
            
            // Générer le rapport de session
            $this->generateSessionReport($session);
            
            // Mettre à jour la session
            $session->update([
                'logout_time' => now(),
                'session_status' => $force ? 'forced' : 'closed'
            ]);
            
            // Mettre à jour l'utilisateur
            $user->update([
                'current_session_id' => null,
                'last_logout_at' => now(),
                'is_active_session' => false
            ]);
            
            // Logger l'action de déconnexion
            $this->logAction($session, $user, 'session', 'logout', null, [
                'duration' => $session->getDurationFormatted(),
                'force' => $force
            ]);
            
            return $session;
        });
    }
    
    public function logAction(
        ReceptionistSession $session, 
        User $user, 
        string $actionType, 
        string $actionSubtype, 
        $actionable = null,
        array $data = [],
        string $notes = null
    ): ReceptionistAction {
        return ReceptionistAction::create([
            'session_id' => $session->id,
            'user_id' => $user->id,
            'action_type' => $actionType,
            'action_subtype' => $actionSubtype,
            'actionable_type' => $actionable ? get_class($actionable) : null,
            'actionable_id' => $actionable ? $actionable->id : null,
            'action_data' => $data,
            'before_state' => $this->getBeforeState($actionable, $actionSubtype),
            'after_state' => $this->getAfterState($actionable, $actionSubtype),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'notes' => $notes,
            'performed_at' => now()
        ]);
    }
    
    private function getBeforeState($actionable, $actionSubtype)
    {
        if (!$actionable || in_array($actionSubtype, ['create', 'login'])) {
            return null;
        }
        
        return $actionable->getAttributes();
    }
    
    private function getAfterState($actionable, $actionSubtype)
    {
        if (!$actionable || $actionSubtype === 'delete') {
            return null;
        }
        
        return $actionable->getAttributes();
    }
    
    public function updateSessionStats(ReceptionistSession $session, string $statType, $value = 1)
    {
        $updates = [];
        
        switch ($statType) {
            case 'reservation':
                $updates['reservations_count'] = DB::raw('reservations_count + 1');
                break;
            case 'checkin':
                $updates['checkins_count'] = DB::raw('checkins_count + 1');
                break;
            case 'checkout':
                $updates['checkouts_count'] = DB::raw('checkouts_count + 1');
                break;
            case 'payment':
                $updates['payments_count'] = DB::raw('payments_count + 1');
                break;
            case 'customer':
                $updates['customer_creations'] = DB::raw('customer_creations + 1');
                break;
            case 'transaction_amount':
                $updates['total_transactions_amount'] = DB::raw("total_transactions_amount + {$value}");
                break;
            case 'cash':
                $updates['cash_handled'] = DB::raw("cash_handled + {$value}");
                break;
            case 'card':
                $updates['card_handled'] = DB::raw("card_handled + {$value}");
                break;
            case 'other_payment':
                $updates['other_payments_handled'] = DB::raw("other_payments_handled + {$value}");
                break;
        }
        
        if (!empty($updates)) {
            $session->update($updates);
        }
    }
    
    public function generateSessionReport(ReceptionistSession $session)
    {
        $summary = $session->generateSessionSummary();
        
        $session->update([
            'session_summary' => $summary,
            'performance_metrics' => [
                'productivity_score' => $session->getProductivityScore(),
                'average_transaction_value' => $session->reservations_count > 0 ? 
                    $session->total_transactions_amount / $session->reservations_count : 0,
                'actions_per_hour' => $session->getProductivityScore(),
                'success_rate' => $this->calculateSuccessRate($session)
            ]
        ]);
        
        return $summary;
    }
    
    private function calculateSuccessRate(ReceptionistSession $session)
    {
        $totalActions = ReceptionistAction::where('session_id', $session->id)->count();
        $errorActions = ReceptionistAction::where('session_id', $session->id)
            ->where('action_subtype', 'like', '%error%')
            ->count();
            
        if ($totalActions === 0) return 100;
        
        return round((($totalActions - $errorActions) / $totalActions) * 100, 2);
    }
    
    private function getLocationFromIP($ip)
    {
        // Pour production, utiliser un service comme ipinfo.io
        if ($ip === '127.0.0.1' || $ip === '::1') {
            return 'Localhost';
        }
        
        // Simuler pour le développement
        $locations = ['Paris', 'Lyon', 'Marseille', 'Bordeaux', 'Lille'];
        return $locations[array_rand($locations)] . ' (estimation)';
    }
    
    private function closeActiveSession(User $user)
    {
        $activeSession = ReceptionistSession::where('user_id', $user->id)
            ->where('session_status', 'active')
            ->first();
            
        if ($activeSession) {
            $activeSession->update([
                'logout_time' => now(),
                'session_status' => 'timeout',
                'session_summary' => 'Session fermée automatiquement (nouvelle connexion)'
            ]);
        }
    }
}