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
                    ->label('Photo')
                    ->circular()
                    ->defaultImageUrl('/images/no-property.png'),
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->limit(40),
                TextColumn::make('full_address')
                    ->label('Address')
                    ->searchable(['address_street', 'address_city', 'address_postal_code'])
                    ->limit(30),
                TextColumn::make('formatted_price')
                    ->label('Price')
                    ->sortable(['buyprice', 'rentprice_month']),
                TextColumn::make('property_type_label')
                    ->label('Type')
                    ->badge(),
                TextColumn::make('transaction_type_label')
                    ->label('Transaction')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'For Sale' => 'success',
                        'For Rent' => 'info',
                        'Auction' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('status_label')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Available' => 'success',
                        'Sold' => 'danger',
                        'Rented' => 'warning',
                        'Pending' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('surface')
                    ->label('Surface')
                    ->suffix(' m²')
                    ->sortable(),
                TextColumn::make('bedrooms')
                    ->label('Rooms')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('transaction_type')
                    ->label('Transaction Type')
                    ->options([
                        'sale' => 'Sale',
                        'rent' => 'Rent',
                        'auction' => 'Auction',
                    ]),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'available' => 'Beschikbaar',
                        'reserved' => 'Gereserveerd',
                        'unavailable' => 'Niet beschikbaar',
                    ]),
                SelectFilter::make('property_type')
                    ->label('Property Type')
                    ->options([
                        'house' => 'House',
                        'apartment' => 'Apartment',
                        'villa' => 'Villa',
                        'townhouse' => 'Townhouse',
                        'farm' => 'Farm',
                        'commercial' => 'Commercial',
                        'land' => 'Land',
                        'parking' => 'Parking',
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
