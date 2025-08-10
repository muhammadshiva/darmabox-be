<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductionTaskResource\Pages;
use App\Models\Order;
use App\Models\User;
use App\Models\ProductionTask;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class ProductionTaskResource extends Resource
{
    protected static ?string $model = ProductionTask::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Production';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Task Information')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Select::make('order_id')
                                ->label('Order')
                                ->options(Order::query()->pluck('invoice_code', 'id'))
                                ->searchable(),
                            Forms\Components\Select::make('assigned_to')
                                ->label('Assign To')
                                ->options(User::query()->pluck('name', 'id'))
                                ->searchable(),
                        ]),
                        Forms\Components\TextInput::make('title')->required()->maxLength(255),
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\Select::make('priority')->options([
                                'low' => 'Low',
                                'normal' => 'Normal',
                                'high' => 'High',
                            ])->default('normal'),
                            Forms\Components\TextInput::make('estimated_hours')->numeric()->minValue(0),
                            Forms\Components\Select::make('status')->options([
                                'not_started' => 'Not Started',
                                'waiting' => 'Waiting',
                                'in_progress' => 'In Progress',
                                'completed' => 'Completed',
                                'blocked' => 'Blocked',
                            ])->default('not_started'),
                        ]),
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\DatePicker::make('start_date'),
                            Forms\Components\DatePicker::make('due_date'),
                        ]),
                        Forms\Components\TextInput::make('progress')->numeric()->minValue(0)->maxValue(100)->suffix('%')->default(0),
                        Forms\Components\Textarea::make('description')->columnSpanFull(),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.invoice_code')->label('Order'),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\BadgeColumn::make('priority')->colors([
                    'success' => 'low',
                    'warning' => 'normal',
                    'danger' => 'high',
                ]),
                Tables\Columns\TextColumn::make('due_date')->date(),
                Tables\Columns\BadgeColumn::make('status')->colors([
                    'secondary' => 'not_started',
                    'info' => 'in_progress',
                    'success' => 'completed',
                    'warning' => 'waiting',
                    'danger' => 'blocked',
                ])->label('Status'),
                Tables\Columns\TextColumn::make('progress')->numeric()->suffix('%'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'not_started' => 'Not Started',
                    'waiting' => 'Waiting',
                    'in_progress' => 'In Progress',
                    'completed' => 'Completed',
                    'blocked' => 'Blocked',
                ]),
                Tables\Filters\TrashedFilter::make()->hidden(),
            ])
            ->actions([
                Tables\Actions\Action::make('start')
                    ->icon('heroicon-o-play')
                    ->visible(fn(ProductionTask $record) => $record->status === 'not_started' || $record->status === 'waiting')
                    ->color('info')
                    ->action(fn(ProductionTask $record) => $record->update(['status' => 'in_progress'])),
                Tables\Actions\Action::make('complete')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn(ProductionTask $record) => $record->status !== 'completed')
                    ->action(fn(ProductionTask $record) => $record->update(['status' => 'completed', 'progress' => 100])),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductionTasks::route('/'),
            'create' => Pages\CreateProductionTask::route('/create'),
            'edit' => Pages\EditProductionTask::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            ProductionTaskResource\RelationManagers\MaterialsRelationManager::class,
            ProductionTaskResource\RelationManagers\TeamRelationManager::class,
            ProductionTaskResource\RelationManagers\AttachmentsRelationManager::class,
        ];
    }
}
