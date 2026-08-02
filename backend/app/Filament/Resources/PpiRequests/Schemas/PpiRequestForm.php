<?php

namespace App\Filament\Resources\PpiRequests\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class PpiRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('customer_name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->default(null),
                TextInput::make('vehicle_make')
                    ->default(null),
                TextInput::make('vehicle_model')
                    ->default(null),
                TextInput::make('vehicle_year')
                    ->numeric()
                    ->default(null),
                DatePicker::make('preferred_date'),
                TimePicker::make('preferred_time'),
                Select::make('tier')
                    ->options(['basic' => 'Basic', 'standard' => 'Standard', 'comprehensive' => 'Comprehensive'])
                    ->default('basic')
                    ->required(),
                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
