<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\RecentAppointmentsTableWidget;
use App\Filament\Widgets\ServicesTableWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\VisitorsChartWidget;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;

class Dashboard extends BaseDashboard
{
    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Dashboard')
                    ->persistTabInQueryString()
                    ->tabs([
                        Tab::make('Overview')
                            ->schema([
                                Grid::make($this->getColumns())
                                    ->schema(fn (): array => $this->getWidgetsSchemaComponents([
                                        StatsOverviewWidget::class,
                                        VisitorsChartWidget::class,
                                        AccountWidget::class,
                                        FilamentInfoWidget::class,
                                    ])),
                            ]),
                        Tab::make('Appointments')
                            ->badge(\App\Models\Appointment::count())
                            ->schema(fn (): array => $this->getWidgetsSchemaComponents([
                                RecentAppointmentsTableWidget::class,
                            ])),
                        Tab::make('Services')
                            ->badge(\App\Models\Service::count())
                            ->schema(fn (): array => $this->getWidgetsSchemaComponents([
                                ServicesTableWidget::class,
                            ])),
                    ]),
            ]);
    }
}
