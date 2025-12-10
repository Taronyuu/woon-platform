<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 4;

    protected function getStats(): array
    {
        $total = User::query()->count();
        $newThisWeek = User::query()
            ->where('created_at', '>=', now()->subWeek())
            ->count();
        $consumers = User::query()->where('type', 'consumer')->count();
        $realtors = User::query()->where('type', 'realtor')->count();
        $verified = User::query()->whereNotNull('email_verified_at')->count();

        return [
            Stat::make('Total Users', number_format($total))
                ->description($newThisWeek . ' new this week')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('primary'),
            Stat::make('Consumers', number_format($consumers))
                ->description($realtors . ' realtors')
                ->color('info'),
            Stat::make('Verified', number_format($verified))
                ->description(round($verified / max($total, 1) * 100) . '% of total')
                ->color('success'),
        ];
    }
}
