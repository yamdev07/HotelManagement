<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckReceptionistRestriction
{
    /**
     * Liste des permissions par défaut pour les réceptionnistes
     */
    protected $receptionistPermissions = [
        // Dashboard
        'dashboard.index' => true,
        'dashboard.data' => true,
        'dashboard.stats' => true,
        
        // Transactions - Lecture et actions limitées
        'transaction.index' => true,
        'transaction.create' => true,
        'transaction.store' => true,
        'transaction.show' => true,
        'transaction.edit' => true, // Mais avec restrictions dans le contrôleur
        'transaction.update' => true, // Avec restrictions
        'transaction.check-in' => true,
        'transaction.check-out' => true,
        'transaction.mark-arrived' => true,
        'transaction.mark-departed' => true,
        'transaction.invoice' => true,
        'transaction.myReservations' => false, // Seulement pour clients
        'transaction.show.customer' => false, // Seulement pour clients
        
        // Transactions - INTERDITES
        'transaction.destroy' => false, // Pas de suppression
        'transaction.cancel' => false, // Pas d'annulation sans autorisation
        'transaction.restore' => false, // Pas de restauration
        
        // Réservations
        'transaction.reservation.createIdentity' => true,
        'transaction.reservation.pickFromCustomer' => true,
        'transaction.reservation.storeCustomer' => true,
        'transaction.reservation.viewCountPerson' => true,
        'transaction.reservation.chooseRoom' => true,
        'transaction.reservation.confirmation' => true,
        'transaction.reservation.payDownPayment' => true,
        
        // Check-in
        'checkin.index' => true,
        'checkin.search' => true,
        'checkin.direct' => true,
        'checkin.show' => true,
        'checkin.store' => true,
        'checkin.quick' => true,
        'checkin.availability' => true,
        'checkin.dashboard' => true,
        
        // Restaurant
        'restaurant.index' => true,
        'restaurant.create' => true,
        'restaurant.store' => true,
        'restaurant.menus.destroy' => true,
        'restaurant.orders' => true,
        'restaurant.orders.show' => true,
        'restaurant.orders.store' => true,
        'restaurant.orders.update' => true,
        'restaurant.orders.cancel' => true,
        'restaurant.api.customers' => true,
        'restaurant.api.menus' => true,
        
        // Housekeeping - Vue seulement
        'housekeeping.index' => true,
        'housekeeping.to-clean' => true,
        'housekeeping.reports' => true,
        'housekeeping.daily-report' => true,
        'housekeeping.mobile' => true,
        'housekeeping.quick-list' => true,
        'housekeeping.scan' => true,
        'housekeeping.scan.process' => true,
        'housekeeping.stats' => true,
        'housekeeping.schedule' => true,
        'housekeeping.maintenance' => true,
        'housekeeping.inspections' => true,
        'housekeeping.monthly-stats' => true,
        
        // Housekeeping - Actions INTERDITES
        'housekeeping.start-cleaning' => false, // Seulement pour housekeeping
        'housekeeping.mark-cleaned' => false,
        'housekeeping.mark-inspection' => false,
        'housekeeping.mark-maintenance' => false,
        'housekeeping.complete-inspection' => false,
        'housekeeping.end-maintenance' => false,
        'housekeeping.maintenance-form' => false,
        'housekeeping.assign-cleaner' => false, // Seulement pour admins
        'housekeeping.update-priority' => false,
        'housekeeping.export' => false,
        
        // Disponibilité
        'availability.dashboard' => true,
        'availability.search' => true,
        'availability.calendar' => true,
        'availability.inventory' => true,
        'availability.room.detail' => true,
        'availability.room.conflicts' => true,
        'availability.check.availability' => true,
        'availability.calendar.cell.details' => true,
        
        // Clients
        'customer.index' => true,
        'customer.create' => true,
        'customer.store' => true,
        'customer.show' => true,
        'customer.edit' => true,
        'customer.update' => true,
        'customer.destroy' => false, // Pas de suppression de clients
        
        // Chambres
        'room.index' => true,
        'room.show' => true,
        'room.create' => false, // Création limitée
        'room.store' => false,
        'room.edit' => false, // Modification limitée
        'room.update' => false,
        'room.destroy' => false, // Pas de suppression
        
        // Types de chambres - Lecture seulement
        'type.index' => true,
        'type.show' => true,
        'type.create' => false,
        'type.store' => false,
        'type.edit' => false,
        'type.update' => false,
        'type.destroy' => false,
        
        // Équipements - Lecture seulement
        'facility.index' => true,
        'facility.show' => true,
        'facility.create' => false,
        'facility.store' => false,
        'facility.edit' => false,
        'facility.update' => false,
        'facility.destroy' => false,
        
        // Statuts de chambres - Lecture seulement
        'roomstatus.index' => true,
        'roomstatus.show' => true,
        'roomstatus.create' => false,
        'roomstatus.store' => false,
        'roomstatus.edit' => false,
        'roomstatus.update' => false,
        'roomstatus.destroy' => false,
        
        // Paiements
        'payments.index' => true,
        'payment.index' => true,
        'transaction.payment.create' => true,
        'transaction.payment.store' => true,
        'transaction.payment.check-status' => true,
        'transaction.payment.force-sync' => true,
        'payment.invoice' => true,
        
        // Paiements - INTERDITS
        'payments.cancel' => false, // Pas d'annulation
        'payments.restore' => false,
        'payments.expire' => false,
        'payments.export' => false,
        
        // Caisse
        'cashier.dashboard' => true,
        'cashier.current-session' => true,
        'cashier.session-summary' => true,
        'cashier.sessions.index' => true,
        'cashier.sessions.create' => true,
        'cashier.sessions.store' => true,
        'cashier.sessions.show' => true,
        'cashier.sessions.close' => true,
        'cashier.sessions.destroy' => false, // Pas de suppression de sessions
        'cashier.sessions.report' => true,
        'cashier.daily-report' => true,
        'cashier.open-session' => true,
        'cashier.start-session' => true,
        'cashier.close-session' => true,
        'cashier.my-sessions' => true,
        'cashier.session-report' => true,
        'cashier.reception-dashboard' => true,
        
        // Images - Lecture seulement
        'image.store' => false, // Upload seulement pour admins
        'image.destroy' => false,
        
        // Rapports
        'reports.index' => true,
        
        // Journal d'activité
        'activity-log.index' => true,
        'activity-log.all' => true,
        
        // Notifications
        'notification.index' => true,
        'notification.markAllAsRead' => true,
        'notification.routeTo' => true,
        
        // Profil
        'profile.index' => true,
        'profile.edit' => true,
        'profile.update' => true,
        'profile.update.info' => true,
        'profile.update.password' => true,
        'profile.update.avatar' => true,
        
        // Utilisateurs - INTERDIT
        'user.index' => false,
        'user.create' => false,
        'user.store' => false,
        'user.show' => true, // Peut voir son propre profil seulement
        'user.edit' => false,
        'user.update' => false,
        'user.destroy' => false,
        
        // Routes spéciales
        'quick.createIdentity' => true,
        'home' => true,
        'logout' => true,
        'logout.now' => true,
        'admin' => false, // Pas d'accès admin
    ];

    /**
     * Routes qui nécessitent une autorisation spéciale
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
     * Gérer la requête
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'Receptionist') {
            return $next($request);
        }
        
        $routeName = $request->route()->getName();
        $method = $request->method();
        $path = $request->path();
        
        // Si pas de nom de route, utiliser le chemin
        if (!$routeName) {
            $routeName = $this->determineRouteFromPath($path, $method);
        }
        
        // Vérifier les permissions
        if (!$this->hasPermission($routeName, $method, $path, $user)) {
            return $this->handleUnauthorized($request, $routeName);
        }
        
        // Vérifier les autorisations spéciales pour les actions critiques
        if ($this->requiresSpecialAuthorization($routeName)) {
            if (!$this->hasSpecialAuthorization($user, $routeName)) {
                return redirect()->route('dashboard.index')
                    ->with('error', 'Cette action nécessite une autorisation spéciale. Veuillez contacter un administrateur.')
                    ->with('authorization_required', true)
                    ->with('action', $routeName);
            }
        }
        
        // Journaliser l'accès (optionnel pour le débogage)
        if (config('app.debug')) {
            Log::channel('receptionist')->info('Receptionist access', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'route' => $routeName,
                'path' => $path,
                'method' => $method,
                'ip' => $request->ip()
            ]);
        }
        
        // Ajouter des restrictions supplémentaires pour certaines routes
        $response = $next($request);
        
        // Post-processing : vérifier les restrictions dans la réponse
        return $this->postProcess($response, $routeName, $user);
    }
    
    /**
     * Vérifier si le réceptionniste a la permission
     */
    protected function hasPermission($routeName, $method, $path, $user)
    {
        // 1. Vérifier par nom de route
        if ($routeName && array_key_exists($routeName, $this->receptionistPermissions)) {
            return $this->receptionistPermissions[$routeName];
        }
        
        // 2. Vérifier par chemin et méthode
        if ($this->isRestrictedByPath($path, $method)) {
            return false;
        }
        
        // 3. Vérifier les routes de ressources CRUD
        if ($this->isRestrictedCrudRoute($routeName, $method)) {
            return false;
        }
        
        // 4. Vérifier l'accès aux utilisateurs
        if ($this->isUserManagementRoute($path, $routeName)) {
            // Un réceptionniste peut seulement voir son propre profil
            if ($routeName === 'user.show') {
                $requestedUserId = request()->route('user');
                return $requestedUserId == $user->id;
            }
            return false;
        }
        
        // 5. Par défaut, permettre l'accès (mais avec restrictions dans les contrôleurs)
        return true;
    }
    
    /**
     * Déterminer la route à partir du chemin
     */
    protected function determineRouteFromPath($path, $method)
    {
        $path = trim($path, '/');
        
        // Mapping des chemins communs
        $pathMappings = [
            'dashboard' => 'dashboard.index',
            'dashboard/data' => 'dashboard.data',
            'dashboard/stats' => 'dashboard.stats',
            'transaction' => 'transaction.index',
            'checkin' => 'checkin.index',
            'restaurant' => 'restaurant.index',
            'housekeeping' => 'housekeeping.index',
            'availability/dashboard' => 'availability.dashboard',
            'customer' => 'customer.index',
            'room' => 'room.index',
            'payments' => 'payments.index',
            'cashier/dashboard' => 'cashier.dashboard',
            'reports' => 'reports.index',
            'activity-log' => 'activity-log.index',
            'profile' => 'profile.index',
        ];
        
        if (isset($pathMappings[$path])) {
            return $pathMappings[$path];
        }
        
        // Pour les routes avec paramètres
        foreach ($pathMappings as $key => $route) {
            if (str_starts_with($path, $key . '/')) {
                return $route;
            }
        }
        
        return null;
    }
    
    /**
     * Vérifier les restrictions par chemin
     */
    protected function isRestrictedByPath($path, $method)
    {
        $restrictedPaths = [
            // Gestion des utilisateurs
            'user/create',
            'user/store',
            'user/destroy',
            'users/create',
            
            // Actions d'administration
            'admin/users',
            'admin/permissions',
            'admin/settings',
            
            // Actions critiques
            'transaction/destroy', // Suppression
            'transaction/cancel',  // Annulation
            'customer/destroy',
            'room/destroy',
            'payments/cancel',
            
            // API/System
            'system/',
            'api/users',
            'debug/',
            'telescope',
            'horizon',
            'log-viewer',
        ];
        
        foreach ($restrictedPaths as $restricted) {
            if (str_starts_with($path, $restricted) || str_contains($path, $restricted)) {
                return true;
            }
        }
        
        // Vérifier les méthodes dangereuses sur certaines routes
        if ($method === 'DELETE') {
            $deleteRestricted = [
                'transaction/',
                'customer/',
                'room/',
                'payment/',
                'user/',
            ];
            
            foreach ($deleteRestricted as $restricted) {
                if (str_starts_with($path, $restricted)) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Vérifier les routes CRUD
     */
    protected function isRestrictedCrudRoute($routeName, $method)
    {
        if (!$routeName) return false;
        
        // Routes de suppression
        if (str_ends_with($routeName, '.destroy') && $method === 'DELETE') {
            return true;
        }
        
        // Routes de création/édition pour certains modules
        $restrictedModules = ['type', 'facility', 'roomstatus'];
        foreach ($restrictedModules as $module) {
            if (str_starts_with($routeName, $module . '.') && 
                in_array(substr($routeName, strlen($module) + 1), ['create', 'store', 'edit', 'update', 'destroy'])) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Vérifier les routes de gestion des utilisateurs
     */
    protected function isUserManagementRoute($path, $routeName)
    {
        if (str_starts_with($path, 'user') || 
            str_starts_with($path, 'users') ||
            (str_starts_with($routeName, 'user.') && $routeName !== 'user.show')) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Vérifier si une action nécessite une autorisation spéciale
     */
    protected function requiresSpecialAuthorization($routeName)
    {
        return in_array($routeName, $this->requiresAuthorization);
    }
    
    /**
     * Vérifier si l'utilisateur a une autorisation spéciale
     */
    protected function hasSpecialAuthorization($user, $routeName)
    {
        // Vérifier si le réceptionniste a des autorisations spéciales
        // Cela pourrait être stocké dans la base de données
        if ($user->special_permissions) {
            $permissions = json_decode($user->special_permissions, true);
            if (in_array($routeName, $permissions)) {
                return true;
            }
        }
        
        // Vérifier avec un superviseur en temps réel (optionnel)
        return $this->requestSupervisorAuthorization($user, $routeName);
    }
    
    /**
     * Demander une autorisation à un superviseur (optionnel)
     */
    protected function requestSupervisorAuthorization($user, $routeName)
    {
        // Cette fonction pourrait :
        // 1. Envoyer une notification à un superviseur
        // 2. Vérifier si un superviseur est en ligne
        // 3. Utiliser un système d'approbation en deux étapes
        
        // Pour l'instant, retourner false par défaut
        return false;
    }
    
    /**
     * Gérer les accès non autorisés
     */
    protected function handleUnauthorized(Request $request, $routeName)
    {
        $user = Auth::user();
        
        // Journaliser la tentative d'accès non autorisé
        Log::channel('security')->warning('Receptionist unauthorized access attempt', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'route' => $routeName,
            'path' => $request->path(),
            'method' => $request->method(),
            'ip' => $request->ip()
        ]);
        
        // Si c'est une requête AJAX/API
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'error' => 'Accès non autorisé',
                'message' => 'Vous n\'avez pas la permission d\'accéder à cette ressource.',
                'requires_authorization' => $this->requiresSpecialAuthorization($routeName)
            ], 403);
        }
        
        // Redirection avec message d'erreur
        $message = 'Accès non autorisé. ';
        
        if ($this->requiresSpecialAuthorization($routeName)) {
            $message .= 'Cette action nécessite une autorisation spéciale.';
        } else {
            $message .= 'Le personnel de réception n\'a pas accès à cette fonctionnalité.';
        }
        
        return redirect()->route('dashboard.index')
            ->with('error', $message)
            ->with('unauthorized_access', true);
    }
    
    /**
     * Post-processing de la réponse
     */
    protected function postProcess($response, $routeName, $user)
    {
        // Cette méthode peut être utilisée pour :
        // 1. Modifier les réponses (cacher des boutons, etc.)
        // 2. Ajouter des restrictions supplémentaires
        // 3. Journaliser les actions
        
        // Pour l'instant, retourner la réponse telle quelle
        return $response;
    }
    
    /**
     * Obtenir la liste des permissions (pour l'affichage dans l'interface)
     */
    public static function getReceptionistPermissions()
    {
        $instance = new self();
        return $instance->receptionistPermissions;
    }
    
    /**
     * Vérifier une permission spécifique (utilisable dans les vues)
     */
    public static function can($permission)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'Receptionist') {
            return true; // Les autres rôles ne sont pas restreints par ce middleware
        }
        
        $instance = new self();
        return $instance->receptionistPermissions[$permission] ?? true;
    }
}