<?php

namespace App\Filament\Resources\Tickets\Tables;

use App\Support\TableExport;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TicketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('uuid')
                    ->label('Ticket')
                    ->formatStateUsing(fn (string $state): string => '#' . strtoupper(substr($state, 0, 8)))
                    ->copyable()
                    ->copyMessage('Ticket ID copied')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Customer')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('subject')
                    ->searchable()
                    ->limit(45)
                    ->wrap()
                    ->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'info',
                        'pending' => 'warning',
                        'resolved' => 'success',
                        default => 'gray',
                    })
                    ->toggleable(),
                TextColumn::make('messages_count')
                    ->counts('messages')
                    ->label('Msgs')
                    ->sortable(),
                TextColumn::make('last_message_at')
                    ->label('Last activity')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Started')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'open' => 'Open',
                        'pending' => 'Pending',
                        'resolved' => 'Resolved',
                        'closed' => 'Closed',
                    ]),
            ])
            ->defaultSort('last_message_at', 'desc')
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
