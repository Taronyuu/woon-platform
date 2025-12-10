<?php

namespace App\Filament\Resources\RealtorAppointments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RealtorAppointmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Contact Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->disabled(),
                        TextInput::make('email')
                            ->label('Email')
                            ->disabled(),
                        TextInput::make('phone')
                            ->label('Phone')
                            ->disabled(),
                        Placeholder::make('created_at_display')
                            ->label('Created At')
                            ->content(fn ($record) => $record->created_at?->format('Y-m-d H:i') ?? '-'),
                    ]),
                Section::make('Message')
                    ->schema([
                        Textarea::make('message')
                            ->label('Message')
                            ->disabled()
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),
                Section::make('Status')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'contacted' => 'Contacted',
                                'completed' => 'Completed',
                            ])
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state === 'contacted' || $state === 'completed') {
                                    $set('contacted_at', now());
                                }
                            }),
                        DateTimePicker::make('contacted_at')
                            ->label('Contacted At'),
                    ]),
            ]);
    }
}
