<?php

namespace App\Filament\Widgets;

use App\Models\PropertyUnit;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PropertyStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $total = PropertyUnit::query()->count();
        $newThisWeek = PropertyUnit::query()
            ->where('created_at', '>=', now()->subWeek())
            ->count();
        $available = PropertyUnit::query()->where('status', 'available')->count();
        $reserved = PropertyUnit::query()->where('status', 'reserved')->count();
        $unavailable = PropertyUnit::query()->where('status', 'unavailable')->count();
        $forSale = PropertyUnit::query()->where('transaction_type', 'sale')->count();
        $forRent = PropertyUnit::query()->where('transaction_type', 'rent')->count();

        return [
            Stat::make('Total Properties', number_format($total))
                ->description($newThisWeek . ' new this week')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary'),
            Stat::make('Available', number_format($available))
                ->description($reserved . ' reserved, ' . $unavailable . ' unavailable')
                ->color('success'),
            Stat::make('For Sale', number_format($forSale))
                ->description($forRent . ' for rent')
                ->color('info'),
        ];
    }
}
