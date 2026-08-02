<?php

namespace App\Filament\Resources\PpiRequests;

use App\Filament\Resources\PpiRequests\Pages\CreatePpiRequest;
use App\Filament\Resources\PpiRequests\Pages\EditPpiRequest;
use App\Filament\Resources\PpiRequests\Pages\ListPpiRequests;
use App\Filament\Resources\PpiRequests\Schemas\PpiRequestForm;
use App\Filament\Resources\PpiRequests\Tables\PpiRequestsTable;
use App\Models\PpiRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PpiRequestResource extends Resource
{
    protected static ?string $model = PpiRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    public static function form(Schema $schema): Schema
    {
        return PpiRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PpiRequestsTable::configure($table);
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
            'index' => ListPpiRequests::route('/'),
            'create' => CreatePpiRequest::route('/create'),
            'edit' => EditPpiRequest::route('/{record}/edit'),
        ];
    }
}
