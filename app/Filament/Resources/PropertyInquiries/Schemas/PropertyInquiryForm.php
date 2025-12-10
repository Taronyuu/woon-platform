<?php

namespace App\Filament\Resources\PropertyInquiries\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PropertyInquiryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Inquiry Details')
                    ->columns(2)
                    ->schema([
                        Placeholder::make('property_title')
                            ->label('Property')
                            ->content(fn ($record) => $record->property?->title ?? '-'),
                        Placeholder::make('created_at_display')
                            ->label('Created At')
                            ->content(fn ($record) => $record->created_at?->format('Y-m-d H:i') ?? '-'),
                        TextInput::make('name')
                            ->label('Name')
                            ->disabled(),
                        TextInput::make('email')
                            ->label('Email')
                            ->disabled(),
                        TextInput::make('phone')
                            ->label('Phone')
                            ->disabled(),
                        TextInput::make('agent_email')
                            ->label('Agent Email')
                            ->disabled(),
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
                        Toggle::make('sent_to_agent')
                            ->label('Sent to Agent'),
                        DateTimePicker::make('sent_at')
                            ->label('Sent At'),
                    ]),
            ]);
    }
}
