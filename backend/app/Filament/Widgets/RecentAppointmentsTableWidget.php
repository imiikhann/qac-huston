<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentAppointmentsTableWidget extends TableWidget
{
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(Appointment::with(['customer', 'service']))
            ->heading('Recent Appointments')
            ->description('Latest 10 appointments')
            ->columns([
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable(),
                TextColumn::make('service.name')
                    ->label('Service')
                    ->searchable(),
                TextColumn::make('requested_date')
                    ->label('Date')
                    ->date('M d, Y')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'in_progress' => 'warning',
                        'scheduled' => 'info',
                        'confirmed' => 'info',
                        'cancelled' => 'danger',
                        'pending' => 'gray',
                        default => 'gray',
                    }),
            ])
            ->paginated(false)
            ->searchable(false);
    }
}
