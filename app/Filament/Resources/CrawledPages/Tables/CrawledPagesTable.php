<?php

namespace App\Filament\Resources\CrawledPages\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CrawledPagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('crawlJob.website_id')
                    ->label('Website')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('crawl_job_id')
                    ->label('Job ID')
                    ->sortable(),
                TextColumn::make('url')
                    ->label('URL')
                    ->searchable()
                    ->limit(60)
                    ->url(fn ($record) => $record->url, shouldOpenInNewTab: true),
                TextColumn::make('status_code')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state): string => match (true) {
                        $state >= 200 && $state < 300 => 'success',
                        $state >= 300 && $state < 400 => 'info',
                        $state >= 400 && $state < 500 => 'warning',
                        $state >= 500 => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('mime_type')
                    ->label('MIME Type')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('scraped_at')
                    ->label('Scraped At')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status_code')
                    ->label('Status Code')
                    ->options([
                        '200' => '200 OK',
                        '301' => '301 Redirect',
                        '302' => '302 Redirect',
                        '403' => '403 Forbidden',
                        '404' => '404 Not Found',
                        '500' => '500 Server Error',
                    ]),
                SelectFilter::make('crawl_job_id')
                    ->label('Crawl Job')
                    ->relationship('crawlJob', 'id')
                    ->searchable()
                    ->preload(),
            ])
            ->defaultSort('scraped_at', 'desc');
    }
}
