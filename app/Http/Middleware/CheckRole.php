<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $user     = Auth::user();
        $userRole = UserRole::tryFrom($user->role);

        // Super admin bypass
        if ($userRole === UserRole::Super) {
            return $next($request);
        }

        // Valider les rôles demandés via l'enum (évite les magic strings)
        $allowedRoles = array_filter(
            array_map(fn (string $r) => UserRole::tryFrom($r), $roles)
        );

        foreach ($allowedRoles as $allowed) {
            if ($userRole === $allowed) {
                return $next($request);
            }
        }

        if (app()->environment('local')) {
            \Log::debug('Accès refusé par CheckRole', [
                'user_id'       => $user->id,
                'user_role'     => $user->role,
                'roles_requis'  => $roles,
                'url'           => $request->fullUrl(),
            ]);
        }

        return redirect()->back()
            ->with('failed', 'Vous n\'êtes pas autorisé à accéder à cette page.')
            ->with('error_type', 'role_denied');
    }
}
