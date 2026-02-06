<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackUserActivity
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $action = null, $entityType = null)
    {
        // Capturer les propriétés de base AVANT l'exécution
        if (Auth::check()) {
            activity()
                ->causedBy(Auth::user())
                ->withProperties([
                    'ip_address' => $request->ip() ?: '127.0.0.1',
                    'user_agent' => $request->userAgent() ?: 'Unknown',
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                ]);
        }

        $response = $next($request);

        // Log automatique après l'exécution
        if (Auth::check() && $this->shouldLogRequest($request)) {
            $this->logActivity($request, $response, $action, $entityType);
        }

        return $response;
    }

    /**
     * Déterminer si la requête doit être enregistrée
     */
    protected function shouldLogRequest(Request $request): bool
    {
        // Exclure certaines routes
        $excludedPaths = [
            'activity', 'log', 'logs',
            'horizon', 'telescope',
            'storage', 'assets',
            'css', 'js', 'img', 'fonts',
            'api/', '_debugbar',
        ];

        $currentPath = $request->path();

        foreach ($excludedPaths as $excluded) {
            if (str_starts_with($currentPath, $excluded) ||
                str_contains($currentPath, $excluded)) {
                return false;
            }
        }

        // Logger seulement les méthodes importantes et utilisateurs auth
        return Auth::check() && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE']);
    }

    /**
     * Enregistrer l'activité
     */
    protected function logActivity(Request $request, $response, $action = null, $entityType = null)
    {
        try {
            // Déterminer l'action
            if (! $action) {
                $action = $this->determineAction($request);
            }

            // Déterminer l'entité concernée
            $subject = $this->determineSubject($request, $entityType);

            // Préparer les propriétés
            $properties = [
                'route' => $request->route()?->getName(),
                'status_code' => $response->getStatusCode(),
                'referer' => $request->header('referer'),
                'input' => $this->filterSensitiveData($request->except(['_token', '_method'])),
            ];

            // Ajouter un snapshot si on a un sujet
            if ($subject) {
                $properties['snapshot'] = $this->createSnapshot($subject);
            }

            // Ajouter ces propriétés supplémentaires à l'activité
            activity()
                ->withProperties($properties)
                ->log($action);

        } catch (\Exception $e) {
            // Ne pas casser l'application si le logging échoue
            \Log::error('Failed to log activity: '.$e->getMessage());
        }
    }

    /**
     * Déterminer l'action automatiquement
     */
    protected function determineAction(Request $request): string
    {
        $routeName = $request->route()?->getName();

        if ($routeName) {
            if (str_contains($routeName, '.store')) {
                return 'created';
            }
            if (str_contains($routeName, '.update')) {
                return 'updated';
            }
            if (str_contains($routeName, '.destroy')) {
                return 'deleted';
            }
            if (str_contains($routeName, '.edit')) {
                return 'edit_viewed';
            }
            if (str_contains($routeName, '.show')) {
                return 'details_viewed';
            }
            if (str_contains($routeName, '.index')) {
                return 'list_viewed';
            }
        }

        return match ($request->method()) {
            'POST' => 'created',
            'PUT', 'PATCH' => 'updated',
            'DELETE' => 'deleted',
            default => 'viewed'
        };
    }

    /**
     * Déterminer l'objet concerné
     */
    protected function determineSubject(Request $request, $entityType = null)
    {
        $parameters = $request->route()?->parameters() ?? [];

        foreach ($parameters as $key => $value) {
            if (is_numeric($value)) {
                // Déterminer la classe du modèle
                if ($entityType) {
                    $modelClass = 'App\\Models\\'.ucfirst($entityType);
                } else {
                    // Deviner à partir du nom du paramètre
                    $modelKey = str_replace(['id', '_id'], '', $key);
                    $modelClass = 'App\\Models\\'.ucfirst(\Str::singular($modelKey));
                }

                if (class_exists($modelClass)) {
                    // Chercher même les modèles supprimés
                    if (method_exists($modelClass, 'withTrashed')) {
                        return $modelClass::withTrashed()->find($value);
                    }

                    return $modelClass::find($value);
                }
            }
        }

        return null;
    }

    /**
     * Créer un snapshot de l'objet
     */
    protected function createSnapshot($subject): array
    {
        if (! $subject) {
            return [];
        }

        $snapshot = [
            'id' => $subject->id,
            'type' => get_class($subject),
        ];

        // Utiliser une méthode dédiée si elle existe
        if (method_exists($subject, 'getLogSnapshot')) {
            return array_merge($snapshot, $subject->getLogSnapshot());
        }

        // Capturer des attributs communs
        $attributes = ['name', 'title', 'email', 'reference', 'code', 'number', 'label'];

        foreach ($attributes as $attr) {
            if (isset($subject->$attr) && ! empty($subject->$attr)) {
                $snapshot[$attr] = $subject->$attr;
            }
        }

        return $snapshot;
    }

    /**
     * Filtrer les données sensibles
     */
    protected function filterSensitiveData(array $data): array
    {
        $sensitiveFields = [
            'password',
            'password_confirmation',
            'current_password',
            'new_password',
            'token',
            'api_key',
            'secret',
            'credit_card',
            'cvv',
            'card_number',
            'expiry_date',
            'ssn',
            'auth_token',
            'remember_token',
        ];

        foreach ($sensitiveFields as $field) {
            if (array_key_exists($field, $data)) {
                $data[$field] = '***HIDDEN***';
            }
        }

        return $data;
    }
}
