<?php

namespace App\Filament\Resources\Memberships\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class MembershipForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->required(),
                Select::make('program_id')
                    ->label('Program')
                    ->relationship('program', 'name')
                    ->required(),
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date'),
                FileUpload::make('payment_proof')
                    ->label('Payment Proof')
                    ->disk('public')
                    ->directory('membership-proofs')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'image/jpeg',
                        'image/png',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    ])
                    ->maxSize(5120)
                    ->openable()
                    ->downloadable(),
                Select::make('status')
                    ->live()
                    ->options(function (string $state): array {
                        $current = $state ?: 'pending';

                        return match ($current) {
                            'expired' => ['expired' => 'Expired'],
                            'cancelled' => ['cancelled' => 'Cancelled'],
                            'active' => [
                                'active' => 'Active',
                                'expired' => 'Expired',
                                'cancelled' => 'Cancelled',
                            ],
                            default => [
                                'pending' => 'Pending',
                                'active' => 'Active',
                                'cancelled' => 'Cancelled',
                            ],
                        };
                    })
                    ->default('pending')
                    ->required(),
            ]);
    }
}
