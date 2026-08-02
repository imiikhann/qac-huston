<?php

namespace App\Filament\Widgets;

use App\Models\Service;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class ServicesTableWidget extends TableWidget
{
    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(Service::query())
            ->heading('Our Services')
            ->description('All available services')
            ->columns([
                TextColumn::make('name')
                    ->label('Service')
                    ->searchable(),
                TextColumn::make('category')
                    ->label('Category')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('description')
                    ->label('Description')
                    ->limit(60)
                    ->searchable(),
                TextColumn::make('price_range')
                    ->label('Price Range'),
            ])
            ->paginated(false)
            ->searchable(false);
    }
}
