<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Country-Based Homepage Redirect
    |--------------------------------------------------------------------------
    |
    | Visitors landing on the homepage from one of the listed countries are
    | sent (302) to that country's regional site. Visitors can opt out with
    | ?region=global, which sets a long-lived cookie.
    |
    */

    'enabled' => env('GEO_REDIRECT_ENABLED', true),

    // MaxMind GeoLite2-Country database (kept fresh by `php artisan geoip:update`)
    'database' => env('GEOIP_DATABASE', storage_path('app/geoip/GeoLite2-Country.mmdb')),

    'account_id' => env('MAXMIND_ACCOUNT_ID'),

    'license_key' => env('MAXMIND_LICENSE_KEY'),

    // ISO country code => destination
    'redirects' => [
        'AE' => '/uae/',
    ],

    'cookie' => 'region',

];
