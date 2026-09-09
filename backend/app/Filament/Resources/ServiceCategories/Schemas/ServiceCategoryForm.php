<?php

namespace App\Filament\Resources\ServiceCategories\Schemas;

use App\Models\ServiceCategory;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ServiceCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (TextInput $component, ?string $state): void {
                        $slugInput = $component->getContainer()->getComponent('slug');

                        if ($slugInput && blank($slugInput->getState())) {
                            $slugInput->state(Str::slug($state));
                        }
                    })
                    ->unique(table: ServiceCategory::class, ignoreRecord: true),
                TextInput::make('slug')
                    ->required()
                    ->unique(table: ServiceCategory::class, ignoreRecord: true)
                    ->helperText('Used to identify this category in the API and frontend. Auto-generated from the name.'),
                Toggle::make('is_active')
                    ->default(true),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
