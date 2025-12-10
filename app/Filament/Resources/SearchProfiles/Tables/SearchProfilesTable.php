<?php

namespace App\Filament\Resources\SearchProfiles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SearchProfilesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.email')
                    ->label('Gebruiker')
                    ->searchable()
                    ->sortable()
                    ->url(fn ($record) => $record->user ? route('filament.admin.resources.users.edit', $record->user) : null),
                TextColumn::make('name')
                    ->label('Naam')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('transaction_type_label')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Koop' => 'success',
                        'Huur' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('cities')
                    ->label('Steden')
                    ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', array_slice($state, 0, 3)) . (count($state) > 3 ? '...' : '') : '-')
                    ->limit(30),
                TextColumn::make('price_range')
                    ->label('Prijsrange')
                    ->getStateUsing(function ($record) {
                        $parts = [];
                        if ($record->min_price) {
                            $parts[] = '€' . number_format($record->min_price, 0, ',', '.');
                        }
                        if ($record->max_price) {
                            $parts[] = '€' . number_format($record->max_price, 0, ',', '.');
                        }
                        return $parts ? implode(' - ', $parts) : '-';
                    }),
                TextColumn::make('min_bedrooms')
                    ->label('Min. kamers')
                    ->placeholder('-'),
                IconColumn::make('is_active')
                    ->label('Actief')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                TextColumn::make('created_at')
                    ->label('Aangemaakt')
                    ->dateTime('d-m-Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('transaction_type')
                    ->label('Transactietype')
                    ->options([
                        'sale' => 'Koop',
                        'rent' => 'Huur',
                    ]),
                TernaryFilter::make('is_active')
                    ->label('Actief')
                    ->placeholder('Alle')
                    ->trueLabel('Actief')
                    ->falseLabel('Inactief'),
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
