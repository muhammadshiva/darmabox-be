<?php

namespace App\Filament\Widgets;

use App\Models\Material;
use App\Models\Order;
use App\Models\Payable;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    protected static ?string $pollingInterval = '60s';
    protected int | string | array $columnSpan = 12;

    protected function getStats(): array
    {
        $today = Carbon::today();

        $ordersToday = Order::whereDate('created_at', $today)->count();
        $productionQueue = Order::whereIn('status', ['dp', 'in_production'])->count();

        $materialsCount = Material::count();
        $belowMinCount = Material::query()->whereColumn('stock', '<', 'minimum_stock')->count();
        $inventoryPct = $materialsCount > 0
            ? max(0, min(100, (int) round(100 * (1 - ($belowMinCount / $materialsCount)))))
            : 100;

        $outstanding = Payable::query()
            ->whereIn('status', ['open', 'partial'])
            ->sum('remaining_amount');

        return [
            Stat::make('Today', number_format($ordersToday))
                ->description('Orders Today')
                ->icon('heroicon-o-shopping-bag'),

            Stat::make('Queue', number_format($productionQueue))
                ->description('Production Queue')
                ->icon('heroicon-o-clock'),

            Stat::make('Stock', $inventoryPct . '%')
                ->description('Inventory Level')
                ->icon('heroicon-o-cube'),

            Stat::make('Pending', 'Rp ' . number_format((int) $outstanding, 0, ',', '.'))
                ->description('Outstanding Payments')
                ->icon('heroicon-o-currency-dollar'),
        ];
    }
}
