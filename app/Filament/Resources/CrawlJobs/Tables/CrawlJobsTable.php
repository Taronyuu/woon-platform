<?php

namespace App\Filament\Resources\CrawlJobs\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CrawlJobsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('website_id')
                    ->label('Website')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'running' => 'warning',
                        'completed' => 'success',
                        'failed' => 'danger',
                        'cancelled' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Wachtend',
                        'running' => 'Bezig',
                        'completed' => 'Voltooid',
                        'failed' => 'Mislukt',
                        'cancelled' => 'Geannuleerd',
                        default => ucfirst($state),
                    }),
                TextColumn::make('pages_crawled')
                    ->label('Paginas')
                    ->sortable()
                    ->formatStateUsing(fn ($record) => $record->pages_crawled . ' / ' . $record->pages_failed . ' mislukt'),
                TextColumn::make('properties_extracted')
                    ->label('Woningen')
                    ->sortable(),
                TextColumn::make('property_limit')
                    ->label('Limiet')
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('avg_response_time_ms')
                    ->label('Gem. respons')
                    ->suffix(' ms')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('started_at')
                    ->label('Gestart')
                    ->dateTime('d-m-Y H:i')
                    ->sortable(),
                TextColumn::make('completed_at')
                    ->label('Voltooid')
                    ->dateTime('d-m-Y H:i')
                    ->sortable(),
                TextColumn::make('duration')
                    ->label('Duur')
                    ->getStateUsing(function ($record) {
                        if (!$record->started_at || !$record->completed_at) {
                            return '-';
                        }
                        $diff = $record->started_at->diff($record->completed_at);
                        if ($diff->h > 0) {
                            return $diff->format('%hh %im');
                        }
                        return $diff->format('%im %ss');
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Wachtend',
                        'running' => 'Bezig',
                        'completed' => 'Voltooid',
                        'failed' => 'Mislukt',
                        'cancelled' => 'Geannuleerd',
                    ]),
                SelectFilter::make('website_id')
                    ->label('Website')
                    ->options(fn () => \App\Models\Website::query()->pluck('name', 'id')->toArray()),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
