<?php

namespace App\Filament\Resources\NavigationMenus\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class NavigationMenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('label')
                    ->required(),
                TextInput::make('url')
                    ->default(null),
                TextInput::make('icon')
                    ->default(null),
                Select::make('parent_id')
                    ->relationship('parent', 'label')
                    ->searchable()
                    ->preload(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('location')
                    ->options([
                        'header' => 'Header',
                        'footer' => 'Footer',
                        'sidebar' => 'Sidebar',
                    ])
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
