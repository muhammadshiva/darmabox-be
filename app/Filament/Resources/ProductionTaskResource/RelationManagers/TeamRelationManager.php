<?php

namespace App\Filament\Resources\ProductionTaskResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TeamRelationManager extends RelationManager
{
    protected static string $relationship = 'teamMembers';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->options(User::query()->pluck('name', 'id'))
                ->searchable()
                ->required(),
            Forms\Components\TextInput::make('role')->maxLength(255),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('user.name')->label('Member'),
            Tables\Columns\TextColumn::make('role'),
        ])->headerActions([
            Tables\Actions\CreateAction::make(),
        ])->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ]);
    }
}
