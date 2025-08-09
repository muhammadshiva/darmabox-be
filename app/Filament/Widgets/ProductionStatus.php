<?php

namespace App\Filament\Widgets;

use App\Models\Production;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class ProductionStatus extends BaseWidget
{
    protected int | string | array $columnSpan = [
        'md' => 6,
        'xl' => 6,
    ];

    protected function getTableHeading(): string
    {
        return 'Production Status';
    }

    protected function getTableQuery(): Builder
    {
        return Production::query()->orderByDesc('start_date')->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('order.invoice_code')->label('Order'),
            Tables\Columns\TextColumn::make('user.name')->label('Assigned To'),
            Tables\Columns\BadgeColumn::make('status')->colors([
                'info' => 'in_progress',
                'warning' => 'waiting',
                'success' => 'done',
            ])->label('Status'),
        ];
    }
}
