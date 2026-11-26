<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Country-Based Redirect
    |--------------------------------------------------------------------------
    |
    | Every page request from one of the listed countries is sent (302) to the
    | same path on that country's regional site, e.g. /work →
    | https://uae.loopsintegrated.com/work. A target may be a full URL on
    | another host or a path on this site.
    |
    */

    'enabled' => env('GEO_REDIRECT_ENABLED', false),

    // MaxMind GeoLite2-Country database (kept fresh by `php artisan geoip:update`)
    'database' => env('GEOIP_DATABASE', storage_path('app/geoip/GeoLite2-Country.mmdb')),

    'account_id' => env('MAXMIND_ACCOUNT_ID'),

    'license_key' => env('MAXMIND_LICENSE_KEY'),

    // ISO country code => regional site (path prefix or full URL)
    'redirects' => [
        'AE' => env('GEO_REDIRECT_AE', 'https://uae.loopsintegrated.com'),
    ],

    // Paths renamed on the way to the regional site; also applies to sub-paths
    // (loops-lately/foo → press/foo)
    'aliases' => [
        'works' => 'work',
        'loops-lately' => 'press',
    ],

    // Pages that exist on the regional site (mirrors frontend/src/router).
    // Any other path is sent to the regional homepage instead.
    'allowed_paths' => [
        '#^$#',
        '#^(about|work|creative|digital|play|tech|ai-content|performance-marketing|events|press|pr|contact|careers|privacy|terms)$#',
        '#^(work|press|pr)/[A-Za-z0-9_-]+$#',
    ],

];
