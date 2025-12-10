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
                        'pending' => 'Pending',
                        'running' => 'Running',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'cancelled' => 'Cancelled',
                        default => ucfirst($state),
                    }),
                TextColumn::make('pages_crawled')
                    ->label('Pages')
                    ->sortable()
                    ->formatStateUsing(fn ($record) => $record->pages_crawled . ' / ' . $record->pages_failed . ' failed'),
                TextColumn::make('properties_extracted')
                    ->label('Properties')
                    ->sortable(),
                TextColumn::make('property_limit')
                    ->label('Limit')
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('avg_response_time_ms')
                    ->label('Avg. Response')
                    ->suffix(' ms')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('started_at')
                    ->label('Started')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
                TextColumn::make('completed_at')
                    ->label('Completed')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
                TextColumn::make('duration')
                    ->label('Duration')
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
                        'pending' => 'Pending',
                        'running' => 'Running',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'cancelled' => 'Cancelled',
                    ]),
                SelectFilter::make('website_id')
                    ->label('Website')
                    ->options(fn () => collect(array_keys(config('websites', [])))->mapWithKeys(fn ($key) => [$key => ucfirst($key)])->toArray()),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
