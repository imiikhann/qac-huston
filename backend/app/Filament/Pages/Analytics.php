<?php

namespace App\Filament\Pages;

use App\Models\VisitorTracking;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;

class Analytics extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?int $navigationSort = 1;

    protected ?string $heading = 'Analytics';

    protected static ?string $navigationLabel = 'Analytics';

    protected string $view = 'filament.pages.analytics';

    public ?string $period = 'overview';

    /** @var array<string, array> */
    protected array $barChartCache = [];

    protected ?array $deviceCache = null;

    public static function human(int $number): string
    {
        return $number >= 1000
            ? (string) Number::compact($number)
            : number_format($number);
    }

    public function periodLabel(): string
    {
        return match ($this->period) {
            'weekly' => 'Weekly',
            'monthly' => 'Monthly',
            'daily' => 'Daily',
            default => 'Overview',
        };
    }

    public function chartDays(): int
    {
        return match ($this->period) {
            'weekly' => 7,
            'monthly' => 30,
            default => 14,
        };
    }

    public function chartSubtitle(): string
    {
        return 'Last ' . $this->chartDays() . ' days';
    }

    public function getPeriodQuery(?string $period = null)
    {
        $period ??= $this->period;

        return match ($period) {
            'weekly' => VisitorTracking::whereBetween('visited_at', [now()->startOfWeek(), now()->endOfWeek()]),
            'monthly' => VisitorTracking::whereBetween('visited_at', [now()->startOfMonth(), now()->endOfMonth()]),
            default => VisitorTracking::whereDate('visited_at', today()),
        };
    }

    protected function currentQuery()
    {
        if ($this->period === 'overview') {
            return VisitorTracking::query();
        }

        return $this->getPeriodQuery();
    }

    public function getTotalVisits(): int
    {
        return VisitorTracking::count();
    }

    public function getUniqueVisitors(): int
    {
        return VisitorTracking::distinct('ip_address')->count('ip_address');
    }

    public function getTodayVisits(): int
    {
        return VisitorTracking::whereDate('visited_at', today())->count();
    }

    public function getTodayUnique(): int
    {
        return VisitorTracking::whereDate('visited_at', today())
            ->distinct('ip_address')
            ->count('ip_address');
    }

    public function getPeriodVisits(): int
    {
        if ($this->period === 'overview') {
            return $this->getTodayVisits();
        }

        return $this->getPeriodQuery()->count();
    }

    public function getPeriodUnique(): int
    {
        if ($this->period === 'overview') {
            return $this->getTodayUnique();
        }

        return $this->getPeriodQuery()->distinct('ip_address')->count('ip_address');
    }

    public function getAvgPerDay(): int|float
    {
        $days = match ($this->period) {
            'weekly' => 7,
            'monthly' => 30,
            default => 1,
        };

        return $days > 0 ? round($this->getPeriodVisits() / $days, 1) : 0;
    }

    public function getPreviousPeriodVisits(): int
    {
        return match ($this->period) {
            'weekly' => VisitorTracking::whereBetween('visited_at', [
                now()->subWeek()->startOfWeek(),
                now()->subWeek()->endOfWeek(),
            ])->count(),
            'monthly' => VisitorTracking::whereBetween('visited_at', [
                now()->subMonthsNoOverflow()->startOfMonth(),
                now()->subMonthsNoOverflow()->endOfMonth(),
            ])->count(),
            default => VisitorTracking::whereDate('visited_at', today()->subDay())->count(),
        };
    }

    /**
     * @return array{pct: float, direction: string}
     */
    public function getPeriodChange(): array
    {
        $previous = $this->getPreviousPeriodVisits();
        $current = $this->getPeriodVisits();

        if ($previous <= 0) {
            return ['pct' => $current > 0 ? 100.0 : 0.0, 'direction' => $current > 0 ? 'up' : 'flat'];
        }

        $pct = round((($current - $previous) / $previous) * 100, 1);

        return ['pct' => abs($pct), 'direction' => $pct > 0 ? 'up' : ($pct < 0 ? 'down' : 'flat')];
    }

    public function getBarChartData(?string $period = null): array
    {
        $period ??= $this->period === 'overview' ? 'daily' : $this->period;

        if (isset($this->barChartCache[$period])) {
            return $this->barChartCache[$period];
        }

        $days = match ($period) {
            'weekly' => 7,
            'monthly' => 30,
            default => 14,
        };

        $start = now()->subDays($days - 1)->startOfDay();

        $rows = VisitorTracking::where('visited_at', '>=', $start)
            ->selectRaw('DATE(visited_at) as day')
            ->selectRaw('COUNT(*) as visits')
            ->selectRaw('COUNT(DISTINCT ip_address) as uniques')
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $labels = [];
        $visits = [];
        $uniques = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $key = $date->toDateString();
            $labels[] = $date->format('M d');
            $visits[] = (int) ($rows[$key]->visits ?? 0);
            $uniques[] = (int) ($rows[$key]->uniques ?? 0);
        }

        return $this->barChartCache[$period] = [
            'labels' => $labels,
            'visits' => $visits,
            'uniques' => $uniques,
            'days' => $days,
        ];
    }

    public function getDeviceBreakdown(): array
    {
        if ($this->deviceCache !== null) {
            return $this->deviceCache;
        }

        $agents = $this->currentQuery()->latest('visited_at')->limit(3000)->pluck('user_agent');

        $counts = [
            'Desktop' => 0,
            'Mobile' => 0,
            'Tablet' => 0,
            'Bot' => 0,
            'Other' => 0,
        ];

        foreach ($agents as $ua) {
            $ua = (string) $ua;

            $type = match (true) {
                preg_match('/bot|crawl|spider|slurp|bingpreview|facebookexternalhit|bytespider|crawler|Googlebot/i', $ua) === 1 => 'Bot',
                preg_match('/ipad|tablet|kindle|silk|playbook|windows nt 10\.0; arm|android(?!.*mobile)/i', $ua) === 1 => 'Tablet',
                preg_match('/mobile|iphone|ipod|android|blackberry|windows phone|opera mini|iemobile/i', $ua) === 1 => 'Mobile',
                blank($ua) => 'Other',
                default => 'Desktop',
            };

            $counts[$type]++;
        }

        $colors = [
            'Desktop' => '#313639',
            'Mobile' => '#FF9000',
            'Tablet' => '#3b82f6',
            'Bot' => '#a855f7',
            'Other' => '#64748b',
        ];

        $labels = array_keys($counts);
        $values = array_values($counts);

        return $this->deviceCache = [
            'labels' => $labels,
            'values' => $values,
            'colors' => array_map(fn (string $label): string => $colors[$label], $labels),
            'total' => array_sum($values),
        ];
    }

    public function getTopPages(): array
    {
        return $this->currentQuery()
            ->whereNotNull('url')
            ->where('url', '!=', '')
            ->select('url', DB::raw('count(*) as count'))
            ->groupBy('url')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function getTopLocations(): array
    {
        return $this->currentQuery()
            ->whereNotNull('zip_code')
            ->select('zip_code', 'city', 'region', 'country', DB::raw('count(*) as count'))
            ->groupBy('zip_code', 'city', 'region', 'country')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function getTopReferrers(): array
    {
        $urls = $this->currentQuery()
            ->whereNotNull('referrer_url')
            ->where('referrer_url', '!=', '')
            ->limit(5000)
            ->pluck('referrer_url');

        $hosts = [];

        foreach ($urls as $url) {
            $host = parse_url($url, PHP_URL_HOST);

            if (blank($host)) {
                continue;
            }

            $host = preg_replace('/^www\./', '', $host) ?? $host;
            $hosts[$host] = ($hosts[$host] ?? 0) + 1;
        }

        arsort($hosts);

        return array_slice($hosts, 0, 8, true);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(VisitorTracking::query()->latest('visited_at'))
            ->columns([
                TextColumn::make('url')
                    ->label('Page')
                    ->limit(40),
                TextColumn::make('ip_address')
                    ->label('IP Address'),
                TextColumn::make('city')
                    ->label('City')
                    ->placeholder('-'),
                TextColumn::make('zip_code')
                    ->label('Zip')
                    ->placeholder('-'),
                TextColumn::make('country')
                    ->label('Country')
                    ->placeholder('-'),
                TextColumn::make('visited_at')
                    ->label('Visited')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),
                TextColumn::make('referrer_url')
                    ->label('Referrer')
                    ->limit(30)
                    ->placeholder('-'),
            ])
            ->defaultSort('visited_at', 'desc')
            ->paginated([10, 25, 50]);
    }
}