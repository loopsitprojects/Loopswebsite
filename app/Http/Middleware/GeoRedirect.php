<?php

namespace App\Http\Middleware;

use Closure;
use GeoIp2\Database\Reader;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GeoRedirect
{
    private const BOT_PATTERN = '/bot|crawl|spider|slurp|facebookexternalhit|embedly|preview|lighthouse|headless/i';

    public function handle(Request $request, Closure $next): Response
    {
        $cookie = config('geo.cookie');

        // Explicit opt-out, e.g. the "Global site" link on /uae → /?region=global
        if ($request->query('region') === 'global') {
            $response = $next($request);
            $response->headers->setCookie(cookie($cookie, 'global', 60 * 24 * 365, '/', null, null, false));

            return $response;
        }

        if (! $this->shouldCheck($request, $cookie)) {
            return $next($request);
        }

        $country = $this->lookupCountry($request->ip());
        $target = config('geo.redirects')[$country] ?? null;

        if ($target) {
            // away() keeps the trailing slash, avoiding a second hop to the /uae/ directory
            return redirect()->away($request->getSchemeAndHttpHost() . $target, 302)
                ->header('Cache-Control', 'private, no-store');
        }

        return $next($request);
    }

    private function shouldCheck(Request $request, string $cookie): bool
    {
        return config('geo.enabled')
            && $request->isMethod('GET')
            && $request->path() === '/'
            && ! $request->cookie($cookie)
            && ! preg_match(self::BOT_PATTERN, (string) $request->userAgent());
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
