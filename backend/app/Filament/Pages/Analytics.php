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

class Analytics extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?int $navigationSort = 1;

    protected ?string $heading = 'Analytics';

    protected static ?string $navigationLabel = 'Analytics';

    protected string $view = 'filament.pages.analytics';

    public ?string $period = 'daily';

    public function getPeriodQuery()
    {
        return match ($this->period) {
            'weekly' => VisitorTracking::whereBetween('visited_at', [now()->startOfWeek(), now()->endOfWeek()]),
            'monthly' => VisitorTracking::whereMonth('visited_at', now()->month)
                ->whereYear('visited_at', now()->year),
            default => VisitorTracking::whereDate('visited_at', today()),
        };
    }

    public function getTotalVisits(): int
    {
        return VisitorTracking::count();
    }

    public function getUniqueVisitors(): int
    {
        return VisitorTracking::distinct('ip_address')->count('ip_address');
    }

    public function getPeriodVisits(): int
    {
        return $this->getPeriodQuery()->count();
    }

    public function getPeriodUnique(): int
    {
        return $this->getPeriodQuery()->distinct('ip_address')->count('ip_address');
    }

    public function getBarChartData(): array
    {
        $days = match ($this->period) {
            'weekly' => 7,
            'monthly' => 30,
            default => 14,
        };

        $labels = [];
        $visits = [];
        $uniques = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('M d');
            $visits[] = VisitorTracking::whereDate('visited_at', $date)->count();
            $uniques[] = VisitorTracking::whereDate('visited_at', $date)
                ->distinct('ip_address')
                ->count('ip_address');
        }

        return [
            'labels' => $labels,
            'visits' => $visits,
            'uniques' => $uniques,
        ];
    }

    public function getPieChartData(): array
    {
        return [
            'labels' => ['Total Visits', 'Unique Visitors', 'Period Visits', 'Period Unique'],
            'values' => [
                $this->getTotalVisits(),
                $this->getUniqueVisitors(),
                $this->getPeriodVisits(),
                $this->getPeriodUnique(),
            ],
            'colors' => ['#FF9000', '#313639', '#3b82f6', '#22c55e'],
        ];
    }

    public function getTopPages(): array
    {
        return VisitorTracking::select('url', DB::raw('count(*) as count'))
            ->groupBy('url')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function getTopLocations(): array
    {
        return VisitorTracking::whereNotNull('zip_code')
            ->select('zip_code', 'city', 'region', 'country', DB::raw('count(*) as count'))
            ->groupBy('zip_code', 'city', 'region', 'country')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->toArray();
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
