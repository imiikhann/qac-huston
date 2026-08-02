<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Review;
use App\Models\Service;
use App\Models\VisitorTracking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected ?string $heading = 'Dashboard Overview';

    protected function getStats(): array
    {
        $totalVisits = VisitorTracking::count();
        $uniqueVisitors = VisitorTracking::distinct('ip_address')->count('ip_address');
        $dailyUnique = VisitorTracking::whereDate('visited_at', today())->distinct('ip_address')->count('ip_address');
        $dailyVisits = VisitorTracking::whereDate('visited_at', today())->count();

        return [
            Stat::make('Total Visits', number_format($totalVisits))
                ->icon('heroicon-o-eye')
                ->color('info')
                ->description('All time page views'),
            Stat::make('Unique Visitors', number_format($uniqueVisitors))
                ->icon('heroicon-o-users')
                ->color('success')
                ->description('Distinct IP addresses'),
            Stat::make('Today', number_format($dailyVisits))
                ->icon('heroicon-o-calendar')
                ->color('warning')
                ->description("{$dailyUnique} unique today"),
            Stat::make('Services', Service::count())
                ->icon('heroicon-o-wrench')
                ->color('primary')
                ->description('Total services offered'),
            Stat::make('Customers', Customer::count())
                ->icon('heroicon-o-user-group')
                ->color('success')
                ->description('Registered customers'),
            Stat::make('Appointments', Appointment::count())
                ->icon('heroicon-o-calendar-days')
                ->color('warning')
                ->description('Total appointments'),
            Stat::make('Reviews', Review::count())
                ->icon('heroicon-o-star')
                ->color('danger')
                ->description('Customer reviews'),
        ];
    }
}
