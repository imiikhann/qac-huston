<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\HeroBanner;
use App\Models\MembershipProgram;
use App\Models\NavigationMenu;
use App\Models\Page;
use App\Models\Section;
use App\Models\SiteSetting;
use App\Models\VisitorTracking;
use App\Services\IpLocationService;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    public function settings()
    {
        $settings = SiteSetting::first();
        if (!$settings) {
            return response()->json(['data' => null]);
        }
        return response()->json(['data' => $settings]);
    }

    public function heroBanners()
    {
        return response()->json([
            'data' => HeroBanner::active()->get(),
        ]);
    }

    public function navigation($location = 'header')
    {
        $items = NavigationMenu::with('children')
            ->active()
            ->location($location)
            ->whereNull('parent_id')
            ->get();

        return response()->json([
            'data' => $items,
        ]);
    }

    public function pages()
    {
        return response()->json([
            'data' => Page::published()->orderBy('sort_order')->get(),
        ]);
    }

    public function page($slug)
    {
        $page = Page::published()->where('slug', $slug)->firstOrFail();
        return response()->json(['data' => $page]);
    }

    public function sections()
    {
        return response()->json([
            'data' => Section::active()->get(),
        ]);
    }

    public function membershipPrograms()
    {
        return response()->json([
            'data' => MembershipProgram::active()->get(),
        ]);
    }

    public function track(Request $request)
    {
        $validated = $request->validate([
            'url' => 'nullable|string|max:500',
            'referrer' => 'nullable|string|max:500',
        ]);

        $ip = $request->ip();
        $location = app(IpLocationService::class)->resolve($ip);

        VisitorTracking::create([
            'url' => $validated['url'] ?? $request->path(),
            'ip_address' => $ip,
            'country' => $location['country'] ?? null,
            'region' => $location['region'] ?? null,
            'city' => $location['city'] ?? null,
            'zip_code' => $location['zip_code'] ?? null,
            'user_agent' => $request->userAgent(),
            'referrer_url' => $validated['referrer'] ?? null,
            'visited_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}
