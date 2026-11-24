<?php

namespace App\Filament\Resources\Memberships\Tables;

use App\Support\TableExport;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MembershipsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('customer.email')
                    ->label('Customer Email')
                    ->searchable()
                    ->placeholder('-')
                    ->toggleable(),
                TextColumn::make('program.name')
                    ->label('Program')
                    ->searchable()
                    ->placeholder('-')
                    ->toggleable(),
                TextColumn::make('tier')
                    ->badge()
                    ->placeholder('-')
                    ->toggleable(),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('end_date')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->toggleable(),
                TextColumn::make('payment_proof')
                    ->label('Payment Proof')
                    ->icon('heroicon-o-paper-clip')
                    ->formatStateUsing(fn ($state) => $state ? 'View Proof' : null)
                    ->url(fn ($record) => $record->payment_proof_url, true)
                    ->placeholder('—')
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
