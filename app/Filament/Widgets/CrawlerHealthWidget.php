<?php

namespace App\Filament\Widgets;

use App\Models\CrawlJob;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Schema;

class CrawlerHealthWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        if (!Schema::hasTable('crawl_jobs')) {
            return [
                Stat::make('Crawler', 'Not configured')
                    ->description('Database table missing')
                    ->color('gray'),
            ];
        }

        $running = CrawlJob::query()->where('status', 'running')->count();
        $completedToday = CrawlJob::query()
            ->where('status', 'completed')
            ->where('completed_at', '>=', now()->startOfDay())
            ->count();
        $failedToday = CrawlJob::query()
            ->where('status', 'failed')
            ->where('completed_at', '>=', now()->startOfDay())
            ->count();
        $propertiesExtractedToday = CrawlJob::query()
            ->where('completed_at', '>=', now()->startOfDay())
            ->sum('properties_extracted');
        $lastJob = CrawlJob::query()
            ->where('status', 'completed')
            ->latest('completed_at')
            ->first();
        $lastRunText = $lastJob?->completed_at?->diffForHumans() ?? 'Not started yet';

        return [
            Stat::make('Active Crawls', $running)
                ->description($completedToday . ' completed today')
                ->color($running > 0 ? 'warning' : 'success'),
            Stat::make('Properties Today', number_format($propertiesExtractedToday))
                ->description($failedToday . ' failed')
                ->color($failedToday > 0 ? 'danger' : 'success'),
            Stat::make('Last Crawl', $lastRunText)
                ->description($lastJob?->website_id ?? '-')
                ->color('gray'),
        ];
    }
}
