<?php

namespace App\Filament\Widgets;

use App\Models\PropertyInquiry;
use App\Models\RealtorAppointment;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Schema;

class InquiryWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        $pendingInquiries = 0;
        $totalInquiries = 0;
        $recentInquiries = 0;

        if (Schema::hasTable('property_inquiries')) {
            $pendingInquiries = PropertyInquiry::query()
                ->where('sent_to_agent', false)
                ->count();
            $totalInquiries = PropertyInquiry::query()->count();
            $recentInquiries = PropertyInquiry::query()
                ->where('created_at', '>=', now()->subWeek())
                ->count();
        }

        $pendingAppointments = 0;
        $totalAppointments = 0;

        if (Schema::hasTable('realtor_appointments')) {
            $pendingAppointments = RealtorAppointment::query()
                ->where('status', 'pending')
                ->count();
            $totalAppointments = RealtorAppointment::query()->count();
        }

        return [
            Stat::make('Pending Inquiries', $pendingInquiries)
                ->description($totalInquiries . ' total')
                ->color($pendingInquiries > 0 ? 'warning' : 'success'),
            Stat::make('Realtor Appointments', $pendingAppointments . ' pending')
                ->description($totalAppointments . ' total')
                ->color($pendingAppointments > 0 ? 'warning' : 'success'),
            Stat::make('Inquiries This Week', $recentInquiries)
                ->description('New leads')
                ->color('info'),
        ];
    }
}
