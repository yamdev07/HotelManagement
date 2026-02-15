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
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // 1. Vérifie si l'utilisateur est authentifié
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        // 2. Récupère l'utilisateur
        $user = Auth::user();

        // 3. ⭐⭐ CORRECTION CRITIQUE : "Super" = SUPER ADMIN = accès à TOUT
        //    Peu importe les rôles demandés, "Super" passe toujours
        if ($user->role === 'Super') {
            return $next($request);
        }

        // 4. Pour les autres rôles : vérifie si l'utilisateur a un des rôles autorisés
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // 5. DEBUG en environnement local (optionnel)
        if (app()->environment('local')) {
            \Log::debug('Accès refusé par CheckRole', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_role' => $user->role,
                'roles_requis' => $roles,
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
            ]);
        }

        // 6. Si non autorisé
        return redirect()->back()
            ->with('failed', 'Vous n\'êtes pas autorisé à accéder à cette page.')
            ->with('error_type', 'role_denied');
    }
}
