<?php

namespace App\Filament\Resources\Tickets\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ticket Details')
                    ->description('Customer information and ticket status.')
                    ->schema([
                        TextInput::make('uuid')
                            ->label('Ticket ID')
                            ->default(fn ($record) => $record ? '#' . strtoupper(substr($record->uuid, 0, 8)) : null)
                            ->disabled()
                            ->dehydrated(false),
                        Select::make('status')
                            ->options([
                                'open' => 'Open',
                                'pending' => 'Pending',
                                'resolved' => 'Resolved',
                                'closed' => 'Closed',
                            ])
                            ->required()
                            ->default('open'),
                        TextInput::make('name')
                            ->label('Customer name')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('email')
                            ->label('Email address')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('phone')
                            ->label('Phone')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('last_message_at')
                            ->label('Last activity')
                            ->default(fn ($record) => $record?->last_message_at?->format('M j, Y g:i A'))
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('subject')
                            ->placeholder('Auto-generated from the first message')
                            ->helperText('Shown in the ticket list. You can edit it.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
