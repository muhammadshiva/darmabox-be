<?php

namespace App\Filament\Resources\ProductionTaskResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class MaterialsRelationManager extends RelationManager
{
    protected static string $relationship = 'materials';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('material_name')->required(),
            Forms\Components\TextInput::make('quantity')->numeric()->required(),
            Forms\Components\TextInput::make('unit')->default('pcs'),
            Forms\Components\Select::make('readiness')->options([
                'not_ready' => 'Not Ready',
                'partial' => 'Partial',
                'ready' => 'Ready',
                'pending' => 'Pending',
            ])->default('not_ready'),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('material_name')->label('Material'),
            Tables\Columns\TextColumn::make('quantity')->numeric()->suffix(' ')->suffix(fn($record) => $record->unit),
            Tables\Columns\BadgeColumn::make('readiness')->colors([
                'danger' => 'not_ready',
                'warning' => 'partial',
                'success' => 'ready',
                'secondary' => 'pending',
            ]),
        ])->headerActions([
            Tables\Actions\CreateAction::make(),
        ])->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ]);
    }
}
