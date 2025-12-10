<?php

namespace App\Filament\Resources\SearchProfiles\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SearchProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Gebruiker')
                    ->columns(2)
                    ->schema([
                        Placeholder::make('user_email')
                            ->label('Gebruiker')
                            ->content(fn ($record) => $record->user?->email ?? '-'),
                        Placeholder::make('created_at_display')
                            ->label('Aangemaakt op')
                            ->content(fn ($record) => $record->created_at?->format('d-m-Y H:i') ?? '-'),
                    ]),
                Section::make('Zoekcriteria')
                    ->columns(2)
                    ->schema([
                        Placeholder::make('name_display')
                            ->label('Naam')
                            ->content(fn ($record) => $record->name ?? '-'),
                        Placeholder::make('transaction_type_display')
                            ->label('Transactietype')
                            ->content(fn ($record) => $record->transaction_type_label ?? '-'),
                        Placeholder::make('cities_display')
                            ->label('Steden')
                            ->content(fn ($record) => is_array($record->cities) ? implode(', ', $record->cities) : '-'),
                        Placeholder::make('property_type_display')
                            ->label('Woningtype')
                            ->content(fn ($record) => $record->property_type ?? '-'),
                        Placeholder::make('price_display')
                            ->label('Prijsrange')
                            ->content(function ($record) {
                                $parts = [];
                                if ($record->min_price) {
                                    $parts[] = '€' . number_format($record->min_price, 0, ',', '.');
                                }
                                if ($record->max_price) {
                                    $parts[] = '€' . number_format($record->max_price, 0, ',', '.');
                                }
                                return $parts ? implode(' - ', $parts) : '-';
                            }),
                        Placeholder::make('surface_display')
                            ->label('Oppervlakte')
                            ->content(function ($record) {
                                $parts = [];
                                if ($record->min_surface) {
                                    $parts[] = $record->min_surface . ' m²';
                                }
                                if ($record->max_surface) {
                                    $parts[] = $record->max_surface . ' m²';
                                }
                                return $parts ? implode(' - ', $parts) : '-';
                            }),
                        Placeholder::make('bedrooms_display')
                            ->label('Min. kamers')
                            ->content(fn ($record) => $record->min_bedrooms ?? '-'),
                        Placeholder::make('energy_label_display')
                            ->label('Energielabel')
                            ->content(fn ($record) => $record->energy_label ?? '-'),
                    ]),
                Section::make('Status')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Actief'),
                    ]),
            ]);
    }
}
