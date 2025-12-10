<?php

namespace App\Filament\Resources\RealtorAppointments\Pages;

use App\Filament\Resources\RealtorAppointments\RealtorAppointmentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRealtorAppointment extends EditRecord
{
    protected static string $resource = RealtorAppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
