<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckReceptionistRestriction
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        // Si l'utilisateur est Receptionist et essaie d'accéder aux users
        if ($user && $user->role === 'Receptionist') {
            $routeName = $request->route()->getName();
            $path = $request->path();
            
            // Empêche l'accès aux routes de gestion des utilisateurs
            if (str_starts_with($routeName, 'user.') || 
                str_starts_with($path, 'user/') ||
                str_contains($path, 'users')) {
                
                return redirect()->route('dashboard.index')
                    ->with('error', 'Le personnel de réception n\'a pas accès à la gestion du staff.');
            }
        }
        
        return $next($request);
    }
}