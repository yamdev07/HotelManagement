<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckHousekeepingReadOnly
{
    // Routes en lecture seule pour Housekeeping
    private $readOnlyRoutes = [
        'customer.index', 'customer.show',
        'room.index', 'room.show', 
        'transaction.index', 'transaction.show',
        'payment.index', 'payment.show',
        'dashboard.index',
    ];
    
    // Routes avec accès complet (housekeeping module)
    private $fullAccessRoutes = [
        'housekeeping.', // Tout ce qui commence par housekeeping.
    ];
    
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        // Si l'utilisateur est Housekeeping
        if ($user && $user->role === 'Housekeeping') {
            $routeName = $request->route()->getName();
            $method = $request->method();
            
            // 1. Vérifie si c'est une route housekeeping (accès complet)
            foreach ($this->fullAccessRoutes as $prefix) {
                if (str_starts_with($routeName, $prefix)) {
                    return $next($request); // Accès autorisé
                }
            }
            
            // 2. Vérifie si c'est une route en lecture seule
            $isReadOnlyRoute = in_array($routeName, $this->readOnlyRoutes);
            
            // 3. Si ce n'est pas une route autorisée, bloque
            if (!$isReadOnlyRoute) {
                return redirect()->route('housekeeping.index')
                    ->with('error', 'Accès restreint au personnel housekeeping.');
            }
            
            // 4. Pour les routes en lecture seule, bloque les méthodes POST/PUT/DELETE
            if ($isReadOnlyRoute && !in_array($method, ['GET', 'HEAD'])) {
                return redirect()->back()
                    ->with('error', 'Le personnel housekeeping a un accès en lecture seulement.');
            }
        }
        
        return $next($request);
    }
}