<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // 1. Vérifie si l'utilisateur est authentifié
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        // 2. Récupère l'utilisateur
        $user = Auth::user();
        
        // 3. Vérifie si l'utilisateur a un des rôles autorisés
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // 4. Si non autorisé
        return redirect()->back()->with('failed', 'Vous n\'êtes pas autorisé à accéder à cette page.');
    }
}