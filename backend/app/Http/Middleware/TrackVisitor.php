<?php

namespace App\Http\Middleware;

use App\Models\VisitorTracking;
use App\Services\IpLocationService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    private array $excludedPrefixes = [
        '_debugbar',
        'telescope',
        'livewire',
        'vendor',
        'build',
        'fonts',
        'storage',
        'admin',
        'api',
        'sanctum',
        'ignition',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->shouldTrack($request)) {
            $ip = $request->ip();

            $location = app(IpLocationService::class)->resolve($ip);

            VisitorTracking::create([
                'url' => $request->path(),
                'ip_address' => $ip,
                'country' => $location['country'] ?? null,
                'region' => $location['region'] ?? null,
                'city' => $location['city'] ?? null,
                'zip_code' => $location['zip_code'] ?? null,
                'user_agent' => $request->userAgent(),
                'referrer_url' => $request->header('referer'),
                'visited_at' => now(),
            ]);
        }

        return $response;
    }

    private function shouldTrack(Request $request): bool
    {
        if ($request->isMethod('GET') !== true) {
            return false;
        }

        $path = $request->path();

        if (in_array($path, ['up', 'health', 'api/v1/track'], true)) {
            return false;
        }

        foreach ($this->excludedPrefixes as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return false;
            }
        }

        if (preg_match('/\.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$/i', $path)) {
            return false;
        }

        return true;
    }
}
