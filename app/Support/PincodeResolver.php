<?php

namespace App\Support;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PincodeResolver
{
    /**
     * Resolve city/state/country information for a given pincode.
     *
     * @param  string  $pincode
     * @return array{city:?string,state:?string,country:?string,source:string}|null
     */
    public static function resolve(string $pincode): ?array
    {
        $digits = preg_replace('/\D+/', '', $pincode);
        if ($digits === '') {
            return null;
        }

        $local = Config::get("pincodes.$digits");
        if ($local) {
            return [
                'city' => $local['city'] ?? null,
                'state' => $local['state'] ?? null,
                'country' => $local['country'] ?? null,
                'source' => 'local',
            ];
        }

        $remote = self::queryRemote($digits);
        if ($remote) {
            return $remote;
        }

        return null;
    }

    protected static function queryRemote(string $digits): ?array
    {
        $endpoints = [
            "https://api.postalpincode.in/pincode/{$digits}",
            "http://api.postalpincode.in/pincode/{$digits}",
        ];

        foreach ($endpoints as $endpoint) {
            try {
                $response = Http::retry(3, 250)
                    ->timeout(6)
                    ->acceptJson()
                    ->get($endpoint);

                if ($response->ok()) {
                    $payload = $response->json();
                    $entry = $payload[0]['PostOffice'][0] ?? null;

                    if ($entry) {
                        return [
                            'city' => $entry['District'] ?? null,
                            'state' => $entry['State'] ?? null,
                            'country' => $entry['Country'] ?? null,
                            'source' => 'remote',
                        ];
                    }
                }
            } catch (\Throwable $exception) {
                Log::warning('Failed resolving pincode', [
                    'pincode' => $digits,
                    'endpoint' => $endpoint,
                    'message' => $exception->getMessage(),
                ]);
            }
        }

        return null;
    }
}
