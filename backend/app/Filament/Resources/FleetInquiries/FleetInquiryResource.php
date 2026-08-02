<?php

namespace App\Filament\Resources\FleetInquiries;

use App\Filament\Resources\FleetInquiries\Pages\CreateFleetInquiry;
use App\Filament\Resources\FleetInquiries\Pages\EditFleetInquiry;
use App\Filament\Resources\FleetInquiries\Pages\ListFleetInquiries;
use App\Filament\Resources\FleetInquiries\Schemas\FleetInquiryForm;
use App\Filament\Resources\FleetInquiries\Tables\FleetInquiriesTable;
use App\Models\FleetInquiry;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FleetInquiryResource extends Resource
{
    protected static ?string $model = FleetInquiry::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    public static function form(Schema $schema): Schema
    {
        return FleetInquiryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FleetInquiriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFleetInquiries::route('/'),
            'create' => CreateFleetInquiry::route('/create'),
            'edit' => EditFleetInquiry::route('/{record}/edit'),
        ];
    }
}
