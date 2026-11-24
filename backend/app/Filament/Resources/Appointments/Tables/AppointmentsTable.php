<?php

namespace App\Filament\Resources\Appointments\Tables;

use App\Support\TableExport;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AppointmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('customer.email')
                    ->searchable()
                    ->placeholder('-')
                    ->toggleable(),
                TextColumn::make('customer.phone')
                    ->label('Phone')
                    ->searchable()
                    ->placeholder('-')
                    ->toggleable(),
                TextColumn::make('vehicle.id')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('service.name')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('requested_date')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('requested_time')
                    ->time()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->badge()
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
