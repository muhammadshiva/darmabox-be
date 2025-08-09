<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseOrderResource\Pages;
use App\Models\PurchaseOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PurchaseOrderResource extends Resource
{
    protected static ?string $model = PurchaseOrder::class;
    protected static ?string $navigationGroup = 'Purchasing';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('supplier_id')->relationship('supplier', 'name')->required(),
            Forms\Components\Select::make('created_by')->relationship('creator', 'name')->required(),
            Forms\Components\TextInput::make('po_number')->required()->maxLength(60),
            Forms\Components\Select::make('status')->options([
                'draft' => 'Draft',
                'sent' => 'Sent',
                'partially_received' => 'Partially Received',
                'received' => 'Received',
                'closed' => 'Closed',
                'cancelled' => 'Cancelled'
            ])->required(),
            Forms\Components\DatePicker::make('expected_date'),
            Forms\Components\Textarea::make('notes')->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),
            Tables\Columns\TextColumn::make('po_number')->searchable(),
            Tables\Columns\TextColumn::make('supplier.name')->label('Supplier')->searchable(),
            Tables\Columns\BadgeColumn::make('status'),
            Tables\Columns\TextColumn::make('expected_date')->date(),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
        ])->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPurchaseOrders::route('/'),
            'create' => Pages\CreatePurchaseOrder::route('/create'),
            'edit' => Pages\EditPurchaseOrder::route('/{record}/edit'),
        ];
    }
}
