<?php

namespace App\Filament\Resources\FleetInquiries\Pages;

use App\Filament\Resources\FleetInquiries\FleetInquiryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFleetInquiries extends ListRecords
{
    protected static string $resource = FleetInquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
