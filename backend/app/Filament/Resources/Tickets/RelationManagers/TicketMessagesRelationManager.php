<?php

namespace App\Filament\Resources\Tickets\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TicketMessagesRelationManager extends RelationManager
{
    protected static string $relationship = 'messages';

    protected static ?string $title = 'Conversation';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sender')
                    ->label('From')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'admin' ? 'QAC' : 'Customer')
                    ->color(fn (string $state): string => $state === 'admin' ? 'amber' : 'gray'),
                TextColumn::make('message')
                    ->label('Message')
                    ->wrap()
                    ->limit(150)
                    ->tooltip(fn (string $state): string => $state),
                TextColumn::make('created_at')
                    ->label('Sent')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'asc');
    }
}
