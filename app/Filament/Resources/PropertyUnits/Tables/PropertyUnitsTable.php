<?php

namespace App\Filament\Resources\PropertyUnits\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PropertyUnitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('main_image')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl('/images/no-property.png'),
                TextColumn::make('title')
                    ->label('Titel')
                    ->searchable()
                    ->sortable()
                    ->limit(40),
                TextColumn::make('full_address')
                    ->label('Adres')
                    ->searchable(['address_street', 'address_city', 'address_postal_code'])
                    ->limit(30),
                TextColumn::make('formatted_price')
                    ->label('Prijs')
                    ->sortable(['buyprice', 'rentprice_month']),
                TextColumn::make('property_type_label')
                    ->label('Type')
                    ->badge(),
                TextColumn::make('transaction_type_label')
                    ->label('Transactie')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Te koop' => 'success',
                        'Te huur' => 'info',
                        'Veiling' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('status_label')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Beschikbaar' => 'success',
                        'Verkocht' => 'danger',
                        'Verhuurd' => 'warning',
                        'In optie' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('surface')
                    ->label('Oppervlakte')
                    ->suffix(' m²')
                    ->sortable(),
                TextColumn::make('bedrooms')
                    ->label('Kamers')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Aangemaakt')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('transaction_type')
                    ->label('Transactietype')
                    ->options([
                        'sale' => 'Koop',
                        'rent' => 'Huur',
                        'auction' => 'Veiling',
                    ]),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'available' => 'Beschikbaar',
                        'sold' => 'Verkocht',
                        'rented' => 'Verhuurd',
                        'pending' => 'In optie',
                        'withdrawn' => 'Uit de handel',
                    ]),
                SelectFilter::make('property_type')
                    ->label('Type woning')
                    ->options([
                        'house' => 'Woonhuis',
                        'apartment' => 'Appartement',
                        'villa' => 'Villa',
                        'townhouse' => 'Herenhuis',
                        'farm' => 'Boerderij',
                        'commercial' => 'Bedrijfspand',
                        'land' => 'Bouwgrond',
                        'parking' => 'Parkeerplaats',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
