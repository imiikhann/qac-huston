<?php

namespace App\Filament\Resources\MembershipPrograms;

use App\Filament\Resources\MembershipPrograms\Pages\CreateMembershipProgram;
use App\Filament\Resources\MembershipPrograms\Pages\EditMembershipProgram;
use App\Filament\Resources\MembershipPrograms\Pages\ListMembershipPrograms;
use App\Filament\Resources\MembershipPrograms\Schemas\MembershipProgramForm;
use App\Filament\Resources\MembershipPrograms\Tables\MembershipProgramsTable;
use App\Models\MembershipProgram;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MembershipProgramResource extends Resource
{
    protected static ?string $model = MembershipProgram::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return MembershipProgramForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MembershipProgramsTable::configure($table);
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
            'index' => ListMembershipPrograms::route('/'),
            'create' => CreateMembershipProgram::route('/create'),
            'edit' => EditMembershipProgram::route('/{record}/edit'),
        ];
    }
}
