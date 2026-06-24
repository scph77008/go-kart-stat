<?php

namespace App\Filament\Resources\Events\RelationManagers;

use App\Models\Participant;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EntriesRelationManager extends RelationManager
{
    protected static string $relationship = 'entries';

    /**
     * Переопределяем, чтобы можно было добавлять новые участия прямо из просмотра события
     *
     * @return bool
     */
    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('participant_id')
                ->relationship('participant', 'display_name')
                ->options(fn() => Participant::query()
                    ->with(['team', 'pilot'])
                    // Только тех, кто подходит по типу события (команда или пилот)
                    ->where('type', '=', $this->ownerRecord->type)
                    ->get()
                    ->mapWithKeys(fn(Participant $participant) => [
                        $participant->id => $participant->display_name,
                    ]))
                ->searchable()
                ->preload()
                ->required(),
            TextInput::make('number')
                ->numeric(),

            Repeater::make('results')
                ->relationship()
                ->schema([
                    Select::make('result_category_id')
                        ->label('Зачёт')
                        ->options(fn($livewire) => $livewire->ownerRecord
                            ->resultCategories
                            ->pluck('name', 'id')
                        )
                        ->required(),

                    TextInput::make('position')
                        ->numeric()
                        ->required(),

                    TextInput::make('gap')
                        ->label('Отставание, %'),
                ])

        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('participant.display_name')
                    ->label('Participant'),
                TextColumn::make('number')
                    ->numeric(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->visible(true)
                    ->authorize(fn() => true),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
