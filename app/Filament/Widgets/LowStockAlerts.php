<?php

namespace App\Filament\Widgets;

use App\Models\Material;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LowStockAlerts extends BaseWidget
{
    protected function getHeading(): string
    {
        return 'Low Stock Alerts';
    }

    protected int | string | array $columnSpan = 12;

    protected function getTableQuery(): Builder
    {
        return Material::query()
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')->label('Item'),
            Tables\Columns\TextColumn::make('stock')->label('Units Left')
                ->formatStateUsing(fn($state) => (string) $state)
                ->badge()
                ->color(fn($state) => $state <= 5 ? 'danger' : ($state <= 12 ? 'warning' : 'success')),
        ];
    }
}
