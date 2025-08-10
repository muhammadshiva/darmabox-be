<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    public function getHeading(): string
    {
        return 'Dashboard';
    }

    public function getSubheading(): ?string
    {
        return "Welcome back! Here's an overview of your business operations.";
    }

    public function getColumns(): int | array
    {
        return 12;
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\DashboardStats::class,
            \App\Filament\Widgets\QuickActions::class,
            \App\Filament\Widgets\RecentOrders::class,
            \App\Filament\Widgets\LowStockAlerts::class,
        ];
    }
}
