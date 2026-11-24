<?php

namespace App\Filament\Resources\NavigationMenus\Tables;

use App\Support\TableExport;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NavigationMenusTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('url')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('icon')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('parent_id')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('location')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'header' => 'success',
                        'footer' => 'warning',
                        'sidebar' => 'info',
                        default => 'gray',
                    })
                    ->toggleable(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
                TableExport::excelAction(),
                TableExport::pdfAction(),
            ]);
    }
}
