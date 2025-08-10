<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentOrders extends BaseWidget
{
    protected int | string | array $columnSpan = 12;

    protected function getTableQuery(): Builder
    {
        return Order::query()
            ->with(['customer', 'items.product'])
            ->latest();
    }

    protected function getTableHeading(): string
    {
        return 'Recent Orders';
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('invoice_code')
                ->label('Order ID')
                ->toggleable()
                ->sortable(),
            Tables\Columns\TextColumn::make('customer.name')
                ->label('Customer')
                ->searchable(),
            Tables\Columns\TextColumn::make('product_types')
                ->label('Product Type')
                ->state(function (Order $record): string {
                    $types = $record->items
                        ->map(fn($item) => optional($item->product)->type)
                        ->filter()
                        ->unique()
                        ->values();

                    return $types->isNotEmpty() ? $types->join(', ') : '-';
                })
                ->toggleable()
                ->searchable(false),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Date')
                ->date()
                ->sortable(),
            Tables\Columns\TextColumn::make('total_amount')
                ->label('Total')
                ->money('idr')
                ->alignEnd(),
            Tables\Columns\BadgeColumn::make('status')
                ->label('Status')
                ->colors([
                    'info' => 'dp',
                    'primary' => 'in_production',
                    'success' => 'paid',
                    'secondary' => 'done',
                    'warning' => 'draft',
                ])
                ->formatStateUsing(function ($state) {
                    return match ($state) {
                        'dp' => 'DP Paid',
                        'in_production' => 'In Production',
                        'done' => 'Ready',
                        default => ucfirst(str_replace('_', ' ', (string) $state)),
                    };
                }),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('view')
                ->label('View')
                ->icon('heroicon-o-eye')
                ->url(fn(Order $record) => route('filament.admin.resources.orders.edit', ['record' => $record]))
                ->openUrlInNewTab(),
        ];
    }
}
