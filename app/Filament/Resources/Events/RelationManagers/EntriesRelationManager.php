<?php

namespace App\Filament\Resources\Events\RelationManagers;

use App\Enums\EventType;
use App\Models\Entry;
use App\Models\EntryPilot;
use App\Models\Pilot;
use App\Models\Team;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Hidden;
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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Тип участника совпадает с типом события, поэтому предзаполним его
        $data['entrant_type'] = $this->ownerRecord->type;

        return $data;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([

            TextInput::make('number')
                ->numeric(),

            Hidden::make('entrant_type')
                ->default(function (EntriesRelationManager $livewire) {
                    return $livewire->ownerRecord->type;
                }),

            Select::make('entrant_id')
                ->label('Участник')
                ->searchable()
                ->preload()
                ->options(function () {
                    return match ($this->ownerRecord->type) {
                        EventType::TEAM => Team::query()
                            ->orderBy('name')
                            ->pluck('name', 'id'),

                        EventType::INDIVIDUAL => Pilot::query()
                            ->orderBy(['surname', 'name'])
                            ->get()
                            ->mapWithKeys(fn($pilot) => [
                                $pilot->id => $pilot->surname . ' ' . $pilot->name,
                            ]),

                        default => [],
                    };
                })
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
                ->required(),

            Repeater::make('pilots')
                ->relationship()
                ->schema([
                    Select::make('pilot_id')
                        ->relationship('pilot', 'id')
                        ->getOptionLabelFromRecordUsing(
                            fn(Pilot $record) => $record->getDisplayName()
                        )
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            TextInput::make('surname')->required(),
                            TextInput::make('name')->required(),
                        ])
                ]),

            Repeater::make('results')
                ->relationship()
                ->schema([
                    Select::make('result_category_id')
                        ->label('Зачёт')
                        ->options(fn() => $this->ownerRecord->resultCategories
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
            ->columns(array(
                /** @see Entry::getEntrantNameAttribute */
                TextColumn::make('entrant_name')
                    ->label('Участник')
                    ->html()
                    ->formatStateUsing(function (Entry $record) {
                        $html = "<strong>{$record->entrant->getDisplayName()}</strong>";

                        if ($record->entrant_type === EventType::TEAM) {
                            $html .= '<div class="text-sm text-gray-500 mt-1">';

                            $html .= $record->pilots
                                // todo: ссылка на карточку пилота
                                ->map(fn(EntryPilot $entryPilot) => e('• ' . $entryPilot->pilot->getDisplayName()))
                                ->implode('<br>');

                            $html .= '</div>';
                        }

                        return $html;
                    }),

                TextColumn::make('number')
                    ->numeric(),
            ))
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
