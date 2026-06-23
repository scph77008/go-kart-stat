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

class PilotsRelationManager extends RelationManager
{
    protected static string $relationship = 'pilots';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('pilot_id')
                ->relationship('pilot', 'surname')
                ->getOptionLabelFromRecordUsing(fn ($record) => trim($record->surname . ' ' . $record->name))
                ->searchable()
                ->preload()
                ->required(),
            TextInput::make('role'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('pilot.surname')
                    ->label('Pilot'),
                TextColumn::make('role'),
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
