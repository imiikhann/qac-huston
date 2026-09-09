<?php

namespace App\Http\Middleware;

use App\Models\SiteSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMode
{
    private array $exemptPrefixes = [
        'api',
        'admin',
        'livewire',
        'storage',
        'build',
        'vendor',
        'js',
        'css',
        'fonts',
        'wp-content',
        'maintenance.html',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->isEnabled()) {
            return $next($request);
        }

        if ($this->isExempt($request)) {
            return $next($request);
        }

        $html = @file_get_contents(public_path('maintenance.html')) ?: '<h1>Under Maintenance</h1>';

        return response($html, 503)->header('Content-Type', 'text/html; charset=utf-8');
    }

    private function isEnabled(): bool
    {
        return (bool) SiteSetting::query()->value('is_maintenance_mode');
    }

    private function isExempt(Request $request): bool
    {
        $path = trim($request->getPathInfo(), '/');

        foreach ($this->exemptPrefixes as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix . '/')) {
                return true;
            }
        }

        return false;
    }
}