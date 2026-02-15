<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckReceptionistRestriction
{
    /**
     * Routes complètement interdites aux réceptionnistes
     */
    protected $restrictedRoutes = [
        'transaction.cancel',
        'transaction.destroy',
        'customer.destroy',
        'room.destroy',
        'payments.cancel',
        'housekeeping.assign-cleaner',
        'housekeeping.start-cleaning',
    ];

    /**
     * Routes qui nécessitent une autorisation spéciale
     * (Pour information seulement - la logique d'autorisation est dans les contrôleurs)
     */
    protected $requiresAuthorization = [
        'transaction.cancel',
        'transaction.destroy',
        'customer.destroy',
        'room.destroy',
        'payments.cancel',
        'housekeeping.assign-cleaner',
        'housekeeping.start-cleaning',
    ];

    /**
     * Gérer la requête - VERSION SIMPLIFIÉE
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        // Si pas d'utilisateur ou pas réceptionniste, laisser passer
        if (! $user || $user->role !== 'Receptionist') {
            return $next($request);
        }
        
        $routeName = $request->route()->getName();
        $method = $request->method();
        
        // Si pas de nom de route, laisser passer
        if (! $routeName) {
            return $next($request);
        }
        
        // Vérifier si c'est une route restreinte
        if (in_array($routeName, $this->restrictedRoutes)) {
            return $this->handleUnauthorized($request, $routeName);
        }
        
        // Journaliser l'accès en mode debug
        if (config('app.debug') && in_array($routeName, $this->requiresAuthorization)) {
            Log::channel('receptionist')->info('Receptionist accessed route that may require authorization', [
                'user_id' => $user->id,
                'route' => $routeName,
                'method' => $method,
            ]);
        }
        
        // Laisser passer pour toutes les autres routes
        // Les restrictions spécifiques seront gérées dans les contrôleurs
        return $next($request);
    }

    /**
     * Gérer les accès non autorisés
     */
    protected function handleUnauthorized(Request $request, $routeName)
    {
        $user = Auth::user();
        
        // Journaliser la tentative d'accès non autorisé
        Log::warning('Réceptionniste a tenté une action restreinte', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'route' => $routeName,
            'method' => $request->method(),
            'ip' => $request->ip(),
        ]);
        
        // Si c'est une requête AJAX/API
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Action non autorisée pour le personnel de réception.',
                'requires_authorization' => in_array($routeName, $this->requiresAuthorization),
            ], 403);
        }
        
        // Message d'erreur selon le type d'action
        $messages = [
            'transaction.cancel' => '❌ L\'annulation des réservations est réservée aux administrateurs.',
            'transaction.destroy' => '❌ La suppression des réservations est réservée aux administrateurs.',
            'customer.destroy' => '❌ La suppression des clients est réservée aux administrateurs.',
            'room.destroy' => '❌ La suppression des chambres est réservée aux administrateurs.',
            'payments.cancel' => '❌ L\'annulation des paiements est réservée aux administrateurs.',
            'housekeeping.assign-cleaner' => '❌ L\'assignation du personnel de ménage est réservée aux administrateurs.',
            'housekeeping.start-cleaning' => '❌ Le démarrage du nettoyage est réservé au personnel housekeeping.',
        ];
        
        $message = $messages[$routeName] ?? '❌ Action non autorisée pour le personnel de réception.';
        
        if (in_array($routeName, $this->requiresAuthorization)) {
            $message .= ' Cette action nécessite une autorisation spéciale.';
        }
        
        return redirect()->back()
            ->with('error', $message)
            ->with('unauthorized_access', true);
    }

    /**
     * Méthode utilitaire pour vérifier si une route nécessite une autorisation
     * (Utilisable dans les vues ou autres endroits)
     */
    public static function requiresAuthorization($routeName)
    {
        $instance = new self;
        return in_array($routeName, $instance->requiresAuthorization);
    }

    /**
     * Méthode utilitaire pour vérifier si un réceptionniste peut accéder à une route
     * (Utilisable dans les vues ou contrôleurs)
     */
    public static function canAccess($routeName)
    {
        $user = Auth::user();
        
        // Si pas d'utilisateur ou pas réceptionniste, autoriser
        if (! $user || $user->role !== 'Receptionist') {
            return true;
        }
        
        $instance = new self;
        return !in_array($routeName, $instance->restrictedRoutes);
    }
}