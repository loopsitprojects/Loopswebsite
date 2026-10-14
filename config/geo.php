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

];
