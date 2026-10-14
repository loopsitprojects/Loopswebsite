<?php

namespace App\Http\Middleware;

use Closure;
use GeoIp2\Database\Reader;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sends visitors from configured countries to their regional site, keeping the
 * path and query string: /work/foo?x=1 → /uae/work/foo?x=1
 */
class GeoRedirect
{
    private const BOT_PATTERN = '/bot|crawl|spider|slurp|facebookexternalhit|embedly|preview|lighthouse|headless/i';

    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->shouldCheck($request)) {
            return $next($request);
        }

        $country = $this->lookupCountry($request->ip());
        $prefix = config('geo.redirects')[$country] ?? null;

        if ($prefix) {
            $path = $request->path() === '/' ? '' : $request->path();
            $query = $request->getQueryString();
            $target = $request->getSchemeAndHttpHost() . rtrim($prefix, '/') . '/' . $path . ($query ? '?' . $query : '');

            return redirect()->away($target, 302)->header('Cache-Control', 'private, no-store');
        }

        return $next($request);
    }

    private function shouldCheck(Request $request): bool
    {
        return config('geo.enabled')
            && ($request->isMethod('GET') || $request->isMethod('HEAD'))
            && ! $this->isExcludedPath($request)
            // Crawlers are never redirected so both sites stay indexable
            && ! preg_match(self::BOT_PATTERN, (string) $request->userAgent());
    }

    private function isExcludedPath(Request $request): bool
    {
        return $request->is(
            'admin*',
            'livewire*',
            'loops-internal-portal*',
            config('services.filament.path', 'loops-internal-portal') . '*',
            'sitemap.xml',
            'robots.txt',
            '_debugbar*',
        );
    }

    private function lookupCountry(?string $ip): ?string
    {
        $database = config('geo.database');

        if (! $ip || ! is_file($database)) {
            return null;
        }

        try {
            return (new Reader($database))->country($ip)->country->isoCode;
        } catch (\Throwable $e) {
            // Unknown/private IP or unreadable database — never block the page
            return null;
        }
    }
}
