<?php

namespace App\Filament\Resources\Events\RelationManagers;

use App\Filament\Resources\Events\EventType;
use App\Models\Event;
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
 * @property-read Event $ownerRecord
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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Тип участника совпадает с типом события, поэтому предзаполним его
        $data['entrant_type'] = $this->ownerRecord->type;

        return $data;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([

            Select::make('entrant_id')
                ->label('Участник')
                ->searchable()
                ->preload()
                ->options(function (EntriesRelationManager $livewire) {
                    if ($livewire->ownerRecord->type === EventType::TEAM) {
                        return Team::query()->pluck('name', 'id');
                    }

                    return Pilot::query()
                        ->get()
                        ->mapWithKeys(fn ($p) => [
                            $p->id => $p->surname.' '.$p->name,
                        ]);
                })
                ->createOptionForm(function (EntriesRelationManager $livewire) {
                    if ($livewire->ownerRecord->type === EventType::TEAM) {
                        return [
                            TextInput::make('name')
                                ->label('Название команды')
                                ->required()
                                ->maxLength(255),
                        ];
                    }

                    return [
                        TextInput::make('surname')
                            ->label('Фамилия')
                            ->required(),

                        TextInput::make('name')
                            ->label('Имя')
                            ->required(),
                    ];
                })
                ->createOptionUsing(function (array $data, EntriesRelationManager $livewire) {
                    if ($livewire->ownerRecord->type === EventType::TEAM) {
                        return Team::create($data)->id;
                    }

                    return Pilot::create([
                        'name' => $data['name'],
                        'surname' => $data['surname'],
                    ])->id;
                }),

            TextInput::make('number')
                ->numeric(),

            Repeater::make('results')
                ->relationship()
                ->schema([
                    Select::make('result_category_id')
                        ->label('Зачёт')
                        ->options(fn (EntriesRelationManager $livewire) => $livewire->ownerRecord
                            ->resultCategories
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
                TextColumn::make('participant.display_name')
                    ->label('Participant'),
                TextColumn::make('number')
                    ->numeric(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->visible()
                    ->authorize(fn () => true),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
