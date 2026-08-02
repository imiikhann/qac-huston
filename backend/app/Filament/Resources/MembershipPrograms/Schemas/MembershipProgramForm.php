<?php

namespace App\Filament\Resources\MembershipPrograms\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MembershipProgramForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('price')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Repeater::make('features')
                    ->schema([
                        TextInput::make('feature')
                            ->required(),
                    ])
                    ->defaultItems(3)
                    ->columnSpanFull(),
                TextInput::make('button_text')
                    ->default('Enroll Now'),
                TextInput::make('button_link')
                    ->default('/contact'),
                Toggle::make('is_popular'),
                Toggle::make('is_active'),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
