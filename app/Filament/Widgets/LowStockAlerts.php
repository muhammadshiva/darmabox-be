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
        return 'Low Stock Materials';
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
            Tables\Columns\TextColumn::make('name')->label('Material'),
            Tables\Columns\TextColumn::make('stock')->label('Current'),
            Tables\Columns\TextColumn::make('minimum_stock')->label('Min'),
            Tables\Columns\BadgeColumn::make('status')
                ->label('Status')
                ->getStateUsing(fn(\App\Models\Material $record) => $record->stock <= $record->minimum_stock ? 'Low' : 'OK')
                ->colors([
                    'danger' => 'Low',
                    'success' => 'OK',
                ]),
        ];
    }
}
