<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-receipt-refund';
    protected static ?string $navigationGroup = 'Orders';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('customer_id')->relationship('customer', 'name')->required(),
            Forms\Components\Select::make('user_id')->relationship('user', 'name')->required(),
            Forms\Components\TextInput::make('invoice_code')->required()->maxLength(60),
            Forms\Components\Select::make('status')->options([
                'draft' => 'Draft',
                'dp' => 'DP',
                'in_production' => 'In Production',
                'done' => 'Done',
                'paid' => 'Paid',
            ])->required(),
            Forms\Components\TextInput::make('dp_amount')->numeric()->minValue(0),
            Forms\Components\TextInput::make('total_amount')->numeric()->minValue(0),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                return $query
                    ->with(['customer', 'items.product'])
                    ->withSum('payments', 'amount')
                    ->latest();
            })
            ->searchPlaceholder('Search orders...')
            ->columns([
                Tables\Columns\TextColumn::make('invoice_code')
                    ->label('Order ID')
                    ->prefix('#')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->description(fn($record) => $record->customer?->phone)
                    ->searchable(),
                Tables\Columns\TextColumn::make('products_summary')
                    ->label('Products')
                    ->state(function (Order $record) {
                        $names = $record->items->map(fn($item) => $item->product?->name)
                            ->filter()
                            ->values();
                        $first = $names->take(3)->implode(', ');
                        return $first;
                    })
                    ->description(fn(Order $record) => $record->items->count() . ' items')
                    ->wrap(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('idr')
                    ->alignEnd(),
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Payment')
                    ->colors([
                        'success' => 'paid',
                        'warning' => 'pending',
                    ])
                    ->formatStateUsing(fn($state) => ucfirst($state)),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'draft',
                        'info' => 'dp',
                        'primary' => 'in_production',
                        'success' => 'paid',
                        'secondary' => 'done',
                    ])
                    ->formatStateUsing(function (string $state) {
                        return match ($state) {
                            'in_production' => 'Processing',
                            'done' => 'Ready',
                            'draft' => 'Draft',
                            'dp' => 'DP',
                            'paid' => 'Paid',
                            default => ucfirst(str_replace('_', ' ', $state)),
                        };
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->date('M j, Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('All Status')
                    ->placeholder('All Status')
                    ->options([
                        'draft' => 'Draft',
                        'dp' => 'DP',
                        'in_production' => 'Processing',
                        'done' => 'Ready',
                        'paid' => 'Paid',
                    ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])->icon('heroicon-m-ellipsis-horizontal'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
