<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentOrders extends BaseWidget
{
    protected int | string | array $columnSpan = [
        'md' => 6,
        'xl' => 6,
    ];

    protected function getTableQuery(): Builder
    {
        return Order::query()->latest()->limit(5);
    }

    protected function getTableHeading(): string
    {
        return 'Recent Orders';
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('invoice_code')->label('#')->sortable(),
            Tables\Columns\TextColumn::make('customer.name')->label('Customer')->searchable(),
            Tables\Columns\BadgeColumn::make('status')->colors([
                'warning' => 'draft',
                'info' => 'dp',
                'primary' => 'in_production',
                'success' => 'paid',
                'secondary' => 'done',
            ]),
            Tables\Columns\TextColumn::make('total_amount')->money('idr')->alignEnd(),
        ];
    }
}

