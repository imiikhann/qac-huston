<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IpLocationService
{
    protected string $apiUrl = 'http://ip-api.com/json';

    public function resolve(string $ip): ?array
    {
        if ($this->isPrivateIp($ip)) {
            return null;
        }

        $cacheKey = "ip_location:{$ip}";
        $cached = Cache::get($cacheKey);
        if ($cached) {
            return $cached;
        }

        try {
            $response = Http::timeout(2)->get("{$this->apiUrl}/{$ip}");

            if ($response->failed() || $response->json('status') !== 'success') {
                return null;
            }

            $data = [
                'country' => $response->json('country'),
                'region' => $response->json('regionName'),
                'city' => $response->json('city'),
                'zip_code' => $response->json('zip'),
            ];

            Cache::put($cacheKey, $data, 86400 * 7);

            return $data;
        } catch (\Exception $e) {
            Log::warning('IP location lookup failed', ['ip' => $ip, 'error' => $e->getMessage()]);
            return null;
        }
    }

    protected function isPrivateIp(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
    }
}
