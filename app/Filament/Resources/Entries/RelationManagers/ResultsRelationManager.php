<?php

namespace App\Filament\Resources\Entries\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ResultsRelationManager extends RelationManager
{
    protected static string $relationship = 'results';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('result_category_id')
                ->label('Category')
                ->options(fn ($livewire) => $livewire->ownerRecord
                    ->event
                    ->resultCategories()
                    ->pluck('name', 'id'))
                ->searchable()
                ->preload()
                ->required(),
            TextInput::make('position')
                ->required()
                ->numeric()
                ->minValue(1),
            TextInput::make('gap'),
            TextInput::make('points')
                ->numeric(),
            TextInput::make('participants_count')
                ->numeric()
                ->minValue(1),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('resultCategory.name')
                    ->label('Category'),
                TextColumn::make('position')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('gap'),
                TextColumn::make('points')
                    ->numeric(),
                TextColumn::make('participants_count')
                    ->numeric(),
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
