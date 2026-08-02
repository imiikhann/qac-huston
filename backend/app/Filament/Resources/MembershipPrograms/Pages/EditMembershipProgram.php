<?php

namespace App\Filament\Resources\MembershipPrograms\Pages;

use App\Filament\Resources\MembershipPrograms\MembershipProgramResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMembershipProgram extends EditRecord
{
    protected static string $resource = MembershipProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
