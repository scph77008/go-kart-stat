<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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
            ]);
    }
}
