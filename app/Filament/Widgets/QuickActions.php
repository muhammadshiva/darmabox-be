<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class QuickActions extends StatsOverviewWidget
{
    protected ?string $heading = 'Quick Actions';

    protected int|string|array $columnSpan = 12;

    // Use widget's default responsive columns

    protected function getStats(): array
    {
        return [
            Stat::make('Create Purchase Order', '')
                ->icon('heroicon-o-document-plus')
                ->color('gray')
                ->url(route('filament.admin.resources.purchase-orders.create'))
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),

            Stat::make('Receive Goods', '')
                ->icon('heroicon-o-truck')
                ->color('gray')
                ->url(route('filament.admin.resources.purchase-orders.index'))
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),

            Stat::make('Review Draft Orders', '')
                ->icon('heroicon-o-clipboard')
                ->color('gray')
                ->url(route('filament.admin.resources.orders.index', [
                    'tableFilters' => [
                        'status' => ['value' => 'draft'],
                    ],
                ]))
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),

            Stat::make('Approve Price Rules', '')
                ->icon('heroicon-o-tag')
                ->color('gray')
                ->url(route('filament.admin.resources.products.index'))
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
        ];
    }
}
