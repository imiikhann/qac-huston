<?php

namespace App\Filament\Resources\Sections\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
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
                Filament\Forms\Components\KeyValue::make('content')
                    ->columnSpanFull(),
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
