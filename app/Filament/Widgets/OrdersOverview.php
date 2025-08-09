<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrdersOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 12;

    protected function getStats(): array
    {
        $total = Order::count();
        $draft = Order::where('status', 'draft')->count();
        $processing = Order::where('status', 'in_production')->count();
        $ready = Order::where('status', 'done')->count();

        return [
            Stat::make('Total Orders', (string) $total)
                ->description('All time'),
            Stat::make('Draft Orders', (string) $draft)
                ->description('Awaiting confirmation'),
            Stat::make('Processing', (string) $processing)
                ->description('In production'),
            Stat::make('Ready', (string) $ready)
                ->description('Completed'),
        ];
    }
}
