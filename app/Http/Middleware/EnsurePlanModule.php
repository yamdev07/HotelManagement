<?php

namespace App\Http\Middleware;

use App\Models\Hotel;
use Closure;
use Illuminate\Http\Request;
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

            // Seuls Admin/Super peuvent gérer l'abonnement -> page de facturation.
            if (in_array($user->role, ['Super', 'Admin'], true)) {
                return redirect()->route('billing.show')->with('error', $message);
            }

            // Les autres rôles (Serveur, Cuisinier, Caissier...) ne peuvent pas
            // payer : au lieu d'une boucle ou d'un 403 brut, on affiche une page
            // propre expliquant que le module n'est pas inclus dans l'abonnement.
            return redirect()->route('module.unavailable')
                ->with('module_label', $label)
                ->with('plan_name', $hotel->planName());
        }

        return $next($request);
    }
}
