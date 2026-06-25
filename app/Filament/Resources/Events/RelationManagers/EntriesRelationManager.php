<?php

namespace App\Filament\Resources\Events\RelationManagers;

use App\Filament\Resources\Events\EventType;
use App\Models\Pilot;
use App\Models\Team;
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

/**
 * @property-read \App\Models\Event $ownerRecord
 */
class EntriesRelationManager extends RelationManager
{
    protected static string $relationship = 'entries';

    /**
     * Переопределяем, чтобы можно было добавлять новые участия прямо из просмотра события
     */
    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([

            TextInput::make('number')
                ->numeric(),

            /**
             * Morph select (Team / Pilot)
             */
            Select::make('entrant')
                ->label('Участник')
                ->searchable()
                ->preload()

                ->options(function () {

                    return match ($this->ownerRecord->type) {

                        EventType::TEAM => Team::query()
                            ->pluck('name', 'id'),

                        EventType::INDIVIDUAL => Pilot::query()
                            ->get()
                            ->mapWithKeys(fn ($pilot) => [
                                $pilot->id => $pilot->surname . ' ' . $pilot->name,
                            ]),

                        default => [],
                    };
                })

                /**
                 * CREATE NEW OPTION
                 */
                ->createOptionForm(function () {

                    return match ($this->ownerRecord->type) {

                        EventType::TEAM => [
                            TextInput::make('name')
                                ->label('Название команды')
                                ->required()
                                ->maxLength(255),
                        ],

                        EventType::INDIVIDUAL => [
                            TextInput::make('surname')
                                ->label('Фамилия')
                                ->required(),

                            TextInput::make('name')
                                ->label('Имя')
                                ->required(),
                        ],
                    };
                })

                ->createOptionUsing(function (array $data) {
                    return match ($this->ownerRecord->type) {
                        EventType::TEAM => Team::create($data)->id,
                        EventType::INDIVIDUAL => Pilot::create($data)->id,
                    };
                })

                ->dehydrateStateUsing(function ($state) {
                    $modelClass = match ($this->ownerRecord->type) {
                        EventType::TEAM => Team::class,
                        EventType::INDIVIDUAL => Pilot::class,
                    };

                    return [
                        'entrant_type' => $modelClass,
                        'entrant_id' => $state,
                    ];
                })

                ->required(),

            Repeater::make('results')
                ->relationship()
                ->schema([
                    Select::make('result_category_id')
                        ->label('Зачёт')
                        ->options(fn () =>
                        $this->ownerRecord->resultCategories
                            ->pluck('name', 'id')
                        )
                        ->required(),

                    TextInput::make('position')
                        ->numeric()
                        ->required(),

                    TextInput::make('gap')
                        ->label('Отставание, %'),
                ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('entrant')
                    ->label('Участник')
                    ->formatStateUsing(fn ($record) =>
                        $record->entrant?->display_name
                        ?? $record->entrant?->name
                        ?? $record->entrant?->surname
                    ),

                TextColumn::make('number')
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
