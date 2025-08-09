<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationGroup = 'Sales';
    protected static ?string $navigationIcon = 'heroicon-o-receipt-refund';

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
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),
            Tables\Columns\TextColumn::make('invoice_code')->searchable(),
            Tables\Columns\TextColumn::make('customer.name')->label('Customer')->searchable(),
            Tables\Columns\BadgeColumn::make('status')->colors([
                'warning' => 'draft',
                'info' => 'dp',
                'primary' => 'in_production',
                'success' => 'paid',
                'secondary' => 'done',
            ]),
            Tables\Columns\TextColumn::make('total_amount')->money('idr'),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
        ])->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
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
