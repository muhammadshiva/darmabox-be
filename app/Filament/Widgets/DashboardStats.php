<?php

namespace App\Filament\Widgets;

use App\Models\Material;
use App\Models\Order;
use App\Models\Payable;
use App\Models\Receivable;
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

        $outstandingAp = Payable::query()
            ->whereIn('status', ['open', 'partial'])
            ->sum('remaining_amount');

        $outstandingAr = Receivable::query()
            ->whereIn('status', ['open', 'partial'])
            ->sum('remaining_amount');

        return [
            Stat::make('Orders Today', number_format($ordersToday))
                ->description('+12% from yesterday')
                ->icon('heroicon-o-shopping-bag')
                ->extraAttributes(['class' => 'min-h-24']),

            Stat::make('Production Queue', number_format($productionQueue))
                ->description('5 due today')
                ->icon('heroicon-o-queue-list')
                ->extraAttributes(['class' => 'min-h-24']),

            Stat::make('Low Stock Items', (string) $belowMinCount)
                ->description('critical items')
                ->icon('heroicon-o-exclamation-triangle')
                ->extraAttributes(['class' => 'min-h-24']),

            Stat::make('Outstanding AR', 'Rp ' . number_format((int) $outstandingAr, 0, ',', '.'))
                ->description('overdue included')
                ->icon('heroicon-o-banknotes')
                ->extraAttributes(['class' => 'min-h-24']),

            Stat::make('Outstanding AP', 'Rp ' . number_format((int) $outstandingAp, 0, ',', '.'))
                ->description('due this week')
                ->icon('heroicon-o-receipt-percent')
                ->extraAttributes(['class' => 'min-h-24']),
        ];
    }
}
