<?php

namespace App\Filament\Resources\Appointments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class AppointmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->required(),
                Select::make('vehicle_id')
                    ->relationship('vehicle', 'id')
                    ->default(null),
                Select::make('service_id')
                    ->relationship('service', 'name')
                    ->default(null),
                DatePicker::make('requested_date')
                    ->required(),
                TimePicker::make('requested_time'),
                Select::make('status')
                    ->live()
                    ->options(function (string $state): array {
                        $current = $state ?: 'pending';

                        return match ($current) {
                            'cancelled' => ['cancelled' => 'Cancelled'],
                            'completed' => ['completed' => 'Completed'],
                            'confirmed' => [
                                'confirmed' => 'Confirmed',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ],
                            default => [
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ],
                        };
                    })
                    ->default('pending')
                    ->required(),
                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
