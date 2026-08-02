<?php

namespace App\Filament\Widgets;

use App\Models\VisitorTracking;
use Filament\Widgets\ChartWidget;

class VisitorsChartWidget extends ChartWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'bar';
    }

    public function getHeading(): ?string
    {
        return 'Daily Visitors (Last 14 Days)';
    }

    protected function getData(): array
    {
        $days = 14;
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
            'datasets' => [
                [
                    'label' => 'Total Visits',
                    'data' => $visits,
                    'backgroundColor' => '#FF9000',
                    'borderColor' => '#FF9000',
                ],
                [
                    'label' => 'Unique Visitors',
                    'data' => $uniques,
                    'backgroundColor' => '#313639',
                    'borderColor' => '#313639',
                ],
            ],
            'labels' => $labels,
        ];
    }
}
