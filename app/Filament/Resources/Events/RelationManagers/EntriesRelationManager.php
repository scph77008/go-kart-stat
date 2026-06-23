<?php

namespace App\Filament\Resources\Events\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EntriesRelationManager extends RelationManager
{
    protected static string $relationship = 'entries';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('team_id')
                ->relationship('team', 'name')
                ->required(),
            TextInput::make('position')
                ->required()
                ->minValue(1)
                ->maxValue(fn($livewire) => $livewire->ownerRecord->participants)
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('team.name')
                    ->label('Команда'),
                TextColumn::make('position')
                    ->label('Позиция')
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
