<?php

namespace App\Filament\Resources\SearchProfiles\Pages;

use App\Filament\Resources\SearchProfiles\SearchProfileResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSearchProfile extends EditRecord
{
    protected static string $resource = SearchProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
