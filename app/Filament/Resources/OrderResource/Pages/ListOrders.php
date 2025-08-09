<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('export')
                ->label('Export')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function (): StreamedResponse {
                    $fileName = 'orders-export-' . now()->format('Ymd_His') . '.csv';
                    $orders = Order::with('customer')->latest()->get();

                    return response()->streamDownload(function () use ($orders) {
                        $handle = fopen('php://output', 'w');
                        fputcsv($handle, ['Order ID', 'Customer', 'Total', 'Payment', 'Status', 'Date']);
                        foreach ($orders as $order) {
                            fputcsv($handle, [
                                $order->invoice_code,
                                $order->customer?->name,
                                $order->total_amount,
                                ucfirst($order->payment_status),
                                $order->status,
                                optional($order->created_at)?->format('Y-m-d'),
                            ]);
                        }
                        fclose($handle);
                    }, $fileName, [
                        'Content-Type' => 'text/csv',
                    ]);
                }),
            Actions\CreateAction::make()->label('New Order'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Orders'),
            'draft' => Tab::make('Draft')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'draft')),
            'processing' => Tab::make('Processing')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'in_production')),
            'ready' => Tab::make('Ready')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'done')),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\OrdersOverview::class,
        ];
    }
}
