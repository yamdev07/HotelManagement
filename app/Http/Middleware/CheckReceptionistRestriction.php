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
     * Déterminer la route à partir du chemin - VERSION MISE À JOUR
     */
    protected function determineRouteFromPath($path, $method)
    {
        $path = trim($path, '/');
        
        if (empty($path)) {
            return null;
        }
        
        // Mapping des chemins communs - VERSION COMPLÈTE
        $pathMappings = [
            // Dashboard
            'dashboard' => 'dashboard.index',
            'dashboard/data' => 'dashboard.data',
            'dashboard/stats' => 'dashboard.stats',
            
            // Transactions
            'transaction' => 'transaction.index',
            'transactions' => 'transaction.index',
            
            // Check-in
            'checkin' => 'checkin.index',
            'check-in' => 'checkin.index',
            
            // Restaurant
            'restaurant' => 'restaurant.index',
            
            // Housekeeping
            'housekeeping' => 'housekeeping.index',
            'housekeeping/dashboard' => 'housekeeping.index',
            
            // Disponibilité
            'availability' => 'availability.dashboard',
            'availability/dashboard' => 'availability.dashboard',
            
            // Clients
            'customer' => 'customer.index',
            'customers' => 'customer.index',
            
            // Chambres
            'room' => 'room.index',
            'rooms' => 'room.index',
            
            // Types de chambres
            'type' => 'type.index',
            'types' => 'type.index',
            
            // Paiements
            'payments' => 'payments.index',
            'payment' => 'payment.index',
            
            // Caisse
            'cashier' => 'cashier.dashboard',
            'cashier/dashboard' => 'cashier.dashboard',
            
            // Rapports
            'reports' => 'reports.index',
            
            // Journal d'activité
            'activity' => 'activity.index',
            'activity-log' => 'activity-log.index',
            'activities' => 'activity.index',
            
            // Profil
            'profile' => 'profile.index',
            
            // Notifications
            'notification' => 'notification.index',
            'notifications' => 'notification.index',
            
            // API/AJAX routes (routes sans nom dans web.php)
            'get-dialy-guest-chart-data' => 'chart.dailyGuestData',
            'chart/dailyGuest' => 'chart.dailyGuest',
            'dashboard/debug' => 'dashboard.debug',
            
            // Routes spéciales
            'home' => 'home',
            'logout' => 'logout',
            'logout-now' => 'logout.now',
            'admin' => 'admin',
        ];
        
        // 1. Vérifier le chemin exact
        if (isset($pathMappings[$path])) {
            return $pathMappings[$path];
        }
        
        // 2. Vérifier les routes avec paramètres
        foreach ($pathMappings as $key => $route) {
            if (!empty($key) && !empty($path) && str_starts_with($path, $key . '/')) {
                return $route;
            }
        }
        
        // 3. Vérifier les patterns courants
        if (str_starts_with($path, 'transaction/reservation/')) {
            return 'transaction.reservation.createIdentity';
        }
        
        if (str_starts_with($path, 'housekeeping/room/')) {
            // Identifier l'action spécifique
            if (str_contains($path, '/start-cleaning')) {
                return 'housekeeping.start-cleaning';
            }
            if (str_contains($path, '/finish-cleaning') || str_contains($path, '/mark-cleaned')) {
                return 'housekeeping.finish-cleaning';
            }
            if (str_contains($path, '/maintenance-form')) {
                return 'housekeeping.maintenance-form';
            }
            return 'housekeeping.index';
        }
        
        if (str_starts_with($path, 'availability/room/')) {
            return 'availability.room.detail';
        }
        
        if (str_starts_with($path, 'cashier/sessions/')) {
            if (str_contains($path, '/close')) {
                return 'cashier.sessions.close';
            }
            if (str_contains($path, '/report')) {
                return 'cashier.sessions.report';
            }
            return 'cashier.sessions.show';
        }
        
        if (str_starts_with($path, 'housekeeping/quick-list/')) {
            return 'housekeeping.quick-list';
        }
        
        // 4. Vérifier les routes de ressources CRUD
        $crudPatterns = [
            'customer' => 'customer.',
            'room' => 'room.',
            'type' => 'type.',
            'facility' => 'facility.',
            'roomstatus' => 'roomstatus.',
            'user' => 'user.',
            'transaction' => 'transaction.',
        ];
        
        foreach ($crudPatterns as $resource => $routePrefix) {
            if (str_starts_with($path, $resource . '/')) {
                // Extraire l'ID et vérifier la méthode
                $parts = explode('/', $path);
                if (count($parts) >= 2) {
                    $id = $parts[1];
                    if (is_numeric($id)) {
                        // Déterminer l'action basée sur la méthode HTTP
                        switch ($method) {
                            case 'GET':
                                if (count($parts) >= 3 && $parts[2] === 'edit') {
                                    return $routePrefix . 'edit';
                                }
                                return $routePrefix . 'show';
                            case 'PUT':
                            case 'PATCH':
                                return $routePrefix . 'update';
                            case 'DELETE':
                                return $routePrefix . 'destroy';
                            case 'POST':
                                return $routePrefix . 'store';
                        }
                    } else {
                        // Si ce n'est pas un nombre, c'est peut-être une action
                        if ($id === 'create') {
                            return $routePrefix . 'create';
                        }
                    }
                }
                return $routePrefix . 'index';
            }
        }
        
        // 5. Vérifier les routes d'autorisation
        if (str_starts_with($path, 'authorization/')) {
            return 'authorization.pending';
        }
        
        // 6. Log pour le débogage des routes non trouvées
        if (config('app.debug')) {
            Log::channel('receptionist')->debug('Route non trouvée dans determineRouteFromPath', [
                'path' => $path,
                'method' => $method,
                'request_path' => request()->path(),
                'full_url' => request()->fullUrl(),
            ]);
        }
        
        return null;
    }
        
    /**
     * Vérifier les restrictions par chemin - CORRECTION ICI
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
            // Vérifier que $restricted et $path sont définis avant d'appeler str_starts_with
            if ($restricted && $path && (str_starts_with($path, $restricted) || str_contains($path, $restricted))) {
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
                // Vérifier que $restricted et $path sont définis
                if ($restricted && $path && str_starts_with($path, $restricted)) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Vérifier les routes CRUD - CORRECTION ICI
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
            // Vérifier que $routeName n'est pas null avant d'utiliser str_starts_with
            if ($routeName && str_starts_with($routeName, $module . '.') && 
                in_array(substr($routeName, strlen($module) + 1), ['create', 'store', 'edit', 'update', 'destroy'])) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Vérifier les routes de gestion des utilisateurs - CORRECTION ICI
     */
    protected function isUserManagementRoute($path, $routeName)
    {
        // Vérifier que $path et $routeName ne sont pas null
        if ($path && (str_starts_with($path, 'user') || str_starts_with($path, 'users'))) {
            return true;
        }
        
        if ($routeName && str_starts_with($routeName, 'user.') && $routeName !== 'user.show') {
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