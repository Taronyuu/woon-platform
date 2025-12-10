<?php

namespace App\Filament\Resources\PropertyInquiries\Pages;

use App\Filament\Resources\PropertyInquiries\PropertyInquiryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPropertyInquiry extends EditRecord
{
    protected static string $resource = PropertyInquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
