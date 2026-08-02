<?php

namespace App\Filament\Resources\FleetInquiries\Pages;

use App\Filament\Resources\FleetInquiries\FleetInquiryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFleetInquiry extends EditRecord
{
    protected static string $resource = FleetInquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
