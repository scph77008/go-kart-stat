<?php

namespace App\Filament\Resources\Events\Schemas;

use App\Filament\Resources\Events\EventType;
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
                Select::make('championship_id')
                    ->relationship('championship', 'name')
                    ->required(),
                DatePicker::make('date')
                    ->required(),
                TextInput::make('duration')
                    ->required()
                    ->numeric(),
                Toggle::make('duration_in_minutes')
                    ->required(),
                Select::make('track_id')
                    ->relationship('track', 'name')
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
                    ->default('team'),
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
                            ->default(fn (Get $get) => $get->integer('../../participants')),
                    ])
                    ->addActionLabel('Add category')
                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                    ->columnSpanFull(),
            ]);
    }
}
