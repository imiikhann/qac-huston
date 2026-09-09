<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Models\ServiceCategory;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('category')
                    ->label('Category')
                    ->options(fn () => ServiceCategory::query()->orderBy('sort_order')->orderBy('name')->pluck('name', 'slug')->all())
                    ->searchable()
                    ->required()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Category Name')
                            ->required(),
                    ])
                    ->createOptionUsing(function (array $data): string {
                        $name = trim((string) ($data['name'] ?? ''));
                        $slug = Str::slug($name);

                        $category = ServiceCategory::firstOrCreate(
                            ['slug' => $slug],
                            ['name' => $name, 'is_active' => true, 'sort_order' => (ServiceCategory::max('sort_order') ?? 0) + 1],
                        );

                        return $category->slug;
                    })
                    ->helperText('Select an existing category or click + to create a new one.'),
                TextInput::make('price_range')
                    ->default(null),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
