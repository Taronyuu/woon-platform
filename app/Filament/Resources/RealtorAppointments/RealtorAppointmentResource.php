<?php

namespace App\Filament\Resources\RealtorAppointments;

use App\Filament\Resources\RealtorAppointments\Pages\EditRealtorAppointment;
use App\Filament\Resources\RealtorAppointments\Pages\ListRealtorAppointments;
use App\Filament\Resources\RealtorAppointments\Schemas\RealtorAppointmentForm;
use App\Filament\Resources\RealtorAppointments\Tables\RealtorAppointmentsTable;
use App\Models\RealtorAppointment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RealtorAppointmentResource extends Resource
{
    protected static ?string $model = RealtorAppointment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendar;

    protected static ?string $navigationLabel = 'Realtor Appointments';

    protected static ?string $modelLabel = 'Realtor Appointment';

    protected static ?string $pluralModelLabel = 'Realtor Appointments';

    public static function form(Schema $schema): Schema
    {
        return RealtorAppointmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RealtorAppointmentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRealtorAppointments::route('/'),
            'edit' => EditRealtorAppointment::route('/{record}/edit'),
        ];
    }
}
