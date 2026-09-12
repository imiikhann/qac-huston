<?php

namespace App\Filament\Resources\Sections\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class SectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->options([
                        'stats' => 'Stats Bar',
                        'services' => 'Services Heading',
                        'image_break' => 'Image Break',
                        'clientele' => 'Clientele Section',
                        'membership' => 'Membership Heading',
                        'reviews' => 'Reviews Heading',
                        'cta' => 'Call to Action',
                        'custom' => 'Custom',
                    ])
                    ->required(),
                TextInput::make('title')
                    ->default(null),
                Textarea::make('subtitle')
                    ->default(null)
                    ->columnSpanFull(),
                KeyValue::make('content')
                    ->columnSpanFull()
                    ->dehydrated(function (Get $get): bool {
                        return ! in_array($get('type'), ['clientele', 'stats']);
                    })
                    ->visible(fn (Get $get): bool => $get('type') !== 'clientele'),
                TextInput::make('content.badge')
                    ->label('Badge')
                    ->default(null)
                    ->visible(fn (Get $get): bool => $get('type') === 'clientele'),
                Repeater::make('content.tabs')
                    ->label('Clientele Tabs')
                    ->schema([
                        TextInput::make('id')
                            ->label('ID (slug)')
                            ->required()
                            ->helperText('e.g. individual, fleet, commercial, luxury, walk-in'),
                        TextInput::make('label')
                            ->label('Tab Label')
                            ->required(),
                        Textarea::make('content')
                            ->label('Description')
                            ->rows(4)
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                    ->defaultItems(0)
                    ->collapsible()
                    ->columnSpanFull()
                    ->visible(fn (Get $get): bool => $get('type') === 'clientele'),
                TextInput::make('content.card_title')
                    ->label('Side Card Title')
                    ->default('Join Our Family')
                    ->visible(fn (Get $get): bool => $get('type') === 'clientele'),
                Textarea::make('content.card_text')
                    ->label('Side Card Text')
                    ->default('Over 70% of our customers are loyal regulars')
                    ->rows(2)
                    ->visible(fn (Get $get): bool => $get('type') === 'clientele'),
                FileUpload::make('image')
                    ->image(),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
