<?php

namespace App\Filament\Resources\Websites\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class WebsitesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('base_url')
                    ->label('URL')
                    ->url(fn ($record) => $record->base_url, shouldOpenInNewTab: true)
                    ->limit(40),
                TextColumn::make('crawler_class')
                    ->label('Crawler Class')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                TextColumn::make('property_units_count')
                    ->label('Properties')
                    ->counts('propertyUnits')
                    ->sortable(),
                TextColumn::make('crawlJobs')
                    ->label('Last Crawl')
                    ->getStateUsing(fn ($record) => $record->crawlJobs()->latest()->first()?->completed_at?->diffForHumans() ?? 'Never'),
                TextColumn::make('max_depth')
                    ->label('Max Depth')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('delay_ms')
                    ->label('Delay (ms)')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active')
                    ->placeholder('All')
                    ->trueLabel('Active')
                    ->falseLabel('Inactive'),
            ])
            ->defaultSort('name', 'asc');
    }
}
