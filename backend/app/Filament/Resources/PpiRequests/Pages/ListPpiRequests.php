<?php

namespace App\Filament\Resources\PpiRequests\Pages;

use App\Filament\Resources\PpiRequests\PpiRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPpiRequests extends ListRecords
{
    protected static string $resource = PpiRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
