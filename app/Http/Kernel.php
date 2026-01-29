<?php

namespace App\Http;

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\CheckAdminRestriction;
use App\Http\Middleware\CheckReceptionistRestriction;
use App\Http\Middleware\CheckHousekeepingReadOnly;
use App\Http\Middleware\TrackUserActivity; // AJOUTEZ CE USE
use App\Http\Middleware\CaptureRequestDetails; // ET CELUI-CI SI VOUS L'AVEZ
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        
        // OPTIONNEL : Si vous voulez capturer les détails pour TOUTES les requêtes
        // \App\Http\Middleware\CaptureRequestDetails::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            
            // MIDDLEWARE D'ACTIVITÉ - RECOMMANDÉ ICI
            // Il sera exécuté pour toutes les routes web
            \App\Http\Middleware\TrackUserActivity::class,
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            // Optionnel pour l'API
            // \App\Http\Middleware\TrackUserActivity::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        
        // ==================== MIDDLEWARES PERSONNALISÉS ====================
        'checkrole' => CheckRole::class,
        'admin.restrict' => CheckAdminRestriction::class,
        'receptionist.restrict' => CheckReceptionistRestriction::class,
        'housekeeping.readonly' => CheckHousekeepingReadOnly::class,
        
        // MIDDLEWARE D'ACTIVITÉ EN TANT QUE MIDDLEWARE DE ROUTE
        // Utile si vous voulez l'appliquer sélectivement
        'activity' => TrackUserActivity::class,
        'activity.withparams' => \App\Http\Middleware\TrackUserActivity::class,
    ];
}