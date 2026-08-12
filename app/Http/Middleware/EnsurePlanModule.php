<?php

namespace App\Http\Middleware;

use App\Models\Hotel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Verrouille l'accès à un module premium selon l'offre de l'hôtel.
 * Ex : le module « restaurant » n'est pas inclus dans l'offre Starter.
 *
 * Usage : ->middleware('plan.module:restaurant')
 *
 * Le Super-Admin plateforme (hotel_id null / rôle Super) passe toujours.
 */
class EnsurePlanModule
{
    public function handle(Request $request, Closure $next, string $module): Response
    {
        $user = $request->user();

        // Invité, Super-Admin plateforme ou rôle Super : accès libre
        if (! $user || $user->hotel_id === null || (method_exists($user, 'isSuper') && $user->isSuper())) {
            return $next($request);
        }

        $hotel = $user->hotel;

        if ($hotel && ! $hotel->hasModule($module)) {
            $label = Hotel::moduleLabel($module);
            $message = __('flash.middleware_plan_missing', ['label' => $label, 'plan' => $hotel->planName()]);

            if ($request->expectsJson()) {
                abort(403, $message);
            }

            // Seuls Admin/Super peuvent gérer l'abonnement -> page de facturation
            // (uniquement si la route existe, pour ne jamais planter).
            if (in_array($user->role, ['Super', 'Admin'], true) && \Illuminate\Support\Facades\Route::has('billing.show')) {
                return redirect()->route('billing.show')->with('error', $message);
            }

            // Les autres rôles (Serveur, Cuisinier, Caissier...) ne peuvent pas
            // payer. On rend la page « module non inclus » DIRECTEMENT (pas via une
            // route nommée : évite tout « Route not defined » si routes/web.php
            // n'est pas à jour). Repli ultra-robuste si la vue manque : déconnexion
            // + message sur la page de connexion.
            if (View::exists('errors.module-unavailable')) {
                return response()->view('errors.module-unavailable', [
                    'moduleLabel' => $label,
                    'planName' => $hotel->planName(),
                ], 403);
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login.index')->with('failed', $message);
        }

        return $next($request);
    }
}
