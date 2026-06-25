<?php

namespace App\Filament\Resources\Events\Schemas;

use App\Enums\EventDurationType;
use App\Enums\EventType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),

                // Чемпионат
                Select::make('championship_id')
                    ->label('Чемпионат')
                    ->relationship('championship', 'name')
                    ->searchable()
                    ->preload()

                    // Создание чемпионата на лету
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Название')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->required(),

                DatePicker::make('date')
                    ->required(),

                // Длительность
                TextInput::make('duration')
                    ->required()
                    ->numeric(),

                // Тип длительности
                Select::make('duration_type')
                    ->required()
                    ->options(EventDurationType::class)
                    ->default(EventDurationType::LAPS->value),

                // Картодром
                Select::make('track_id')
                    ->label('Картодром')
                    ->relationship('track', 'name')
                    ->searchable()
                    ->preload()

                    // Создание картодромов на лету
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Название')
                            ->required(),

                        TextInput::make('location')
                            ->label('Локация')
                            ->required(),

                        TextInput::make('length')
                            ->label('Длина (м)')
                            ->numeric()
                            ->required(),
                    ])
                    ->required(),

                Toggle::make('reverse')
                    ->required(),

                TextInput::make('participants')
                    ->required()
                    ->numeric()
                    ->default(0),

                Select::make('type')
                    ->required()
                    ->options(EventType::class)
                    ->default(EventType::TEAM->value),

                Repeater::make('resultCategories')
                    ->label('Result categories')
                    ->relationship('resultCategories')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('participants')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(fn(Get $get) => $get->integer('../../participants')),
                    ])
                    ->addActionLabel('Add category')
                    ->itemLabel(fn(array $state): ?string => $state['name'] ?? null)
                    ->columnSpanFull(),
            ]);
    }
}
