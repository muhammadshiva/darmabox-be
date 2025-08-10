<?php

namespace App\Filament\Resources\ProductionTaskResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AttachmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'attachments';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\FileUpload::make('path')->disk('public')->directory('production/task-attachments')->preserveFilenames()->required(),
            Forms\Components\TextInput::make('label')->maxLength(255),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\ImageColumn::make('path')->disk('public'),
            Tables\Columns\TextColumn::make('label'),
            Tables\Columns\TextColumn::make('created_at')->dateTime(),
        ])->headerActions([
            Tables\Actions\CreateAction::make(),
        ])->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ]);
    }
}
