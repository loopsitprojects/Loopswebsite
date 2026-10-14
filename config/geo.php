<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Country-Based Redirect
    |--------------------------------------------------------------------------
    |
    | Every page request from one of the listed countries is sent (302) to the
    | same path on that country's regional site, e.g. /work → /uae/work.
    |
    */

    'enabled' => env('GEO_REDIRECT_ENABLED', false), // keep false on the /uae deployment to avoid a redirect loop

    // MaxMind GeoLite2-Country database (kept fresh by `php artisan geoip:update`)
    'database' => env('GEOIP_DATABASE', storage_path('app/geoip/GeoLite2-Country.mmdb')),

    'account_id' => env('MAXMIND_ACCOUNT_ID'),

    'license_key' => env('MAXMIND_LICENSE_KEY'),

    // ISO country code => regional site prefix
    'redirects' => [
        'AE' => '/uae/',
    ],

    // Paths renamed on the way to the regional site (exact match)
    'aliases' => [
        'works' => 'work',
    ],

    // Pages that exist on the regional site (mirrors frontend/src/router).
    // Any other path is sent to the regional homepage instead.
    'allowed_paths' => [
        '#^$#',
        '#^(about|work|creative|digital|play|tech|ai-content|performance-marketing|events|press|pr|contact|careers|privacy|terms)$#',
        '#^(work|press|pr)/[A-Za-z0-9_-]+$#',
    ],

];
