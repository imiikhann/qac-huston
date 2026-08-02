<?php

namespace App\Filament\Resources\Leads\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LeadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('source')
                    ->options([
            'website' => 'Website',
            'ai-chat' => 'Ai chat',
            'referral' => 'Referral',
            'social-media' => 'Social media',
            'search' => 'Search',
            'other' => 'Other',
        ])
                    ->default('website')
                    ->required(),
                TextInput::make('name')
                    ->default(null),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->default(null),
                TextInput::make('phone')
                    ->tel()
                    ->default(null),
                TextInput::make('service_interest')
                    ->default(null),
                Textarea::make('message')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('status')
                    ->options([
            'new' => 'New',
            'contacted' => 'Contacted',
            'qualified' => 'Qualified',
            'converted' => 'Converted',
            'closed' => 'Closed',
        ])
                    ->default('new')
                    ->required(),
            ]);
    }
}
