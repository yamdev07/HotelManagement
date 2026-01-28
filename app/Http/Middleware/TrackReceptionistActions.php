<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\ReceptionistSessionService;

class TrackReceptionistActions
{
    protected $sessionService;
    
    public function __construct(ReceptionistSessionService $sessionService)
    {
        $this->sessionService = $sessionService;
    }
    
    public function handle(Request $request, Closure $next, $actionType = null)
    {
        $response = $next($request);
        
        // Vérifier si l'utilisateur est un réceptionniste avec session active
        if (auth()->check() && 
            auth()->user()->role === 'Receptionist' && 
            auth()->user()->is_active_session &&
            auth()->user()->current_session_id) {
            
            $this->trackAction($request, $actionType);
        }
        
        return $response;
    }
    
    private function trackAction(Request $request, $actionType)
    {
        $user = auth()->user();
        $session = $user->currentSession;
        
        if (!$session) return;
        
        // Déterminer le type d'action basé sur la route
        $routeName = $request->route()->getName();
        $actionSubtype = $this->getActionSubtype($request);
        
        // Logger l'action
        $actionable = $this->getActionableEntity($request);
        
        $this->sessionService->logAction(
            $session,
            $user,
            $actionType ?? $this->getActionTypeFromRoute($routeName),
            $actionSubtype,
            $actionable,
            $this->getActionData($request),
            $this->getActionNotes($request)
        );
        
        // Mettre à jour les statistiques
        $this->updateStats($session, $actionType, $request);
    }
    
    private function getActionTypeFromRoute($routeName)
    {
        $patterns = [
            'reservation' => '/reservation|booking/i',
            'checkin' => '/checkin|check-in/i',
            'checkout' => '/checkout|check-out/i',
            'payment' => '/payment|paiement/i',
            'customer' => '/customer|client/i',
            'room' => '/room|chambre/i'
        ];
        
        foreach ($patterns as $type => $pattern) {
            if (preg_match($pattern, $routeName)) {
                return $type;
            }
        }
        
        return 'system';
    }
    
    private function getActionSubtype(Request $request)
    {
        $method = $request->method();
        
        return match($method) {
            'POST' => 'create',
            'PUT', 'PATCH' => 'update',
            'DELETE' => 'delete',
            'GET' => 'view',
            default => 'other'
        };
    }
    
    private function getActionableEntity(Request $request)
    {
        // Récupérer l'entité depuis les paramètres de route
        $parameters = $request->route()->parameters();
        
        foreach ($parameters as $parameter) {
            if (is_object($parameter)) {
                return $parameter;
            }
        }
        
        return null;
    }
    
    private function getActionData(Request $request)
    {
        return [
            'route' => $request->route()->getName(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'input' => $this->sanitizeInput($request->all())
        ];
    }
    
    private function sanitizeInput($input)
    {
        // Supprimer les données sensibles
        $sensitive = ['password', 'password_confirmation', 'credit_card', 'cvv'];
        
        foreach ($sensitive as $field) {
            if (isset($input[$field])) {
                $input[$field] = '***HIDDEN***';
            }
        }
        
        return $input;
    }
    
    private function getActionNotes(Request $request)
    {
        // Extraire des notes spécifiques si présentes
        if ($request->has('notes')) {
            return substr($request->input('notes'), 0, 200);
        }
        
        return null;
    }
    
    private function updateStats($session, $actionType, $request)
    {
        $statsMap = [
            'reservation' => 'reservation',
            'checkin' => 'checkin',
            'checkout' => 'checkout',
            'payment' => 'payment',
            'customer' => 'customer'
        ];
        
        if (isset($statsMap[$actionType])) {
            $this->sessionService->updateSessionStats($session, $statsMap[$actionType]);
        }
        
        // Mettre à jour les montants financiers
        if ($actionType === 'payment' && $request->has('amount')) {
            $paymentMethod = $request->input('payment_method', 'other');
            $amount = $request->input('amount', 0);
            
            $statType = match($paymentMethod) {
                'cash' => 'cash',
                'card', 'credit_card', 'debit_card' => 'card',
                default => 'other_payment'
            };
            
            $this->sessionService->updateSessionStats($session, $statType, $amount);
            $this->sessionService->updateSessionStats($session, 'transaction_amount', $amount);
        }
    }
}