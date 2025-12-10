<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Account')
                    ->columns(2)
                    ->schema([
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->maxLength(255),
                        Select::make('type')
                            ->label('Type')
                            ->options([
                                'consumer' => 'Consumer',
                                'realtor' => 'Realtor',
                            ])
                            ->default('consumer')
                            ->required(),
                    ]),
                Section::make('Personal Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('first_name')
                            ->label('First Name')
                            ->maxLength(255),
                        TextInput::make('last_name')
                            ->label('Last Name')
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('Phone')
                            ->tel()
                            ->maxLength(255),
                        TextInput::make('address')
                            ->label('Address')
                            ->maxLength(255),
                        TextInput::make('postal_code')
                            ->label('Postal Code')
                            ->maxLength(10),
                        TextInput::make('city')
                            ->label('City')
                            ->maxLength(255),
                    ]),
                Section::make('Notifications')
                    ->columns(2)
                    ->schema([
                        Toggle::make('notify_new_properties')
                            ->label('New Properties')
                            ->default(true),
                        Toggle::make('notify_price_changes')
                            ->label('Price Changes')
                            ->default(true),
                        Toggle::make('notify_newsletter')
                            ->label('Newsletter')
                            ->default(false),
                        Toggle::make('notify_marketing')
                            ->label('Marketing')
                            ->default(false),
                    ]),
            ]);
    }
}
