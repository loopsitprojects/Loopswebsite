<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UpdateGeoIpDatabase extends Command
{
    protected $signature = 'geoip:update';

    protected $description = 'Download the latest MaxMind GeoLite2-Country database';

    public function handle(): int
    {
        $accountId = config('geo.account_id');
        $key = config('geo.license_key');

        if (! $accountId || ! $key) {
            $this->error('MAXMIND_ACCOUNT_ID and MAXMIND_LICENSE_KEY must be set in .env');

            return self::FAILURE;
        }

        $database = config('geo.database');
        $dir = dirname($database);
        $archive = $dir . '/GeoLite2-Country.tar.gz';

        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $response = Http::timeout(120)
            ->withBasicAuth($accountId, $key)
            ->sink($archive)
            ->get('https://download.maxmind.com/geoip/databases/GeoLite2-Country/download', ['suffix' => 'tar.gz']);

        if (! $response->successful()) {
            @unlink($archive);
            $this->error('Download failed: HTTP ' . $response->status());

            return self::FAILURE;
        }

        // Archive contains GeoLite2-Country_YYYYMMDD/GeoLite2-Country.mmdb
        $phar = new \PharData($archive);
        foreach (new \RecursiveIteratorIterator($phar) as $file) {
            if (str_ends_with($file->getFilename(), '.mmdb')) {
                copy($file->getPathname(), $database . '.tmp');
                rename($database . '.tmp', $database);
                break;
            }
        }

        @unlink($archive);

        if (! is_file($database)) {
            $this->error('No .mmdb file found in the archive');

            return self::FAILURE;
        }

        $this->info('GeoLite2-Country database updated: ' . $database);

        return self::SUCCESS;
    }
}
