<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    public function getHeading(): string
    {
        return 'Dashboard Overview';
    }

    public function getSubheading(): ?string
    {
        return "Welcome back! Here's what's happening today.";
    }

    public function getColumns(): int | array
    {
        return 12;
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\DashboardStats::class,
            \App\Filament\Widgets\RecentOrders::class,
            \App\Filament\Widgets\ProductionStatus::class,
            \App\Filament\Widgets\LowStockAlerts::class,
        ];
    }
}

