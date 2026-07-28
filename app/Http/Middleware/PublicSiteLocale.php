<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

/**
 * Independent locale middleware for the public vitrine site.
 * Uses ?lang= param → session('public_lang') → browser Accept-Language → default 'fr'.
 * Completely independent from the main app's SetLocale / session('locale').
 */
class PublicSiteLocale
{
    private const VALID = ['fr', 'en'];

    private const DEFAULT = 'fr';

    public function handle(Request $request, Closure $next)
    {
        $locale = $request->query('lang');

        if ($locale && in_array($locale, self::VALID, true)) {
            session(['public_lang' => $locale]);
        } else {
            $locale = session('public_lang');
        }

        if (! $locale || ! in_array($locale, self::VALID, true)) {
            $locale = $this->detectFromBrowser($request);
        }

        app()->setLocale($locale);
        Carbon::setLocale($locale);

        return $next($request);
    }

    private function detectFromBrowser(Request $request): string
    {
        $best = $request->getPreferredLanguage(self::VALID);

        return $best ?: self::DEFAULT;
    }
}
