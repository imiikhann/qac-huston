<?php

namespace App\Filament\Resources\PpiRequests\Pages;

use App\Filament\Resources\PpiRequests\PpiRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPpiRequest extends EditRecord
{
    protected static string $resource = PpiRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
