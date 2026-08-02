<?php

namespace App\Filament\Resources\MembershipPrograms\Pages;

use App\Filament\Resources\MembershipPrograms\MembershipProgramResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMembershipPrograms extends ListRecords
{
    protected static string $resource = MembershipProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
