<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->columnSpanFull(),
                Select::make('championship_id')
                    ->relationship('championship', 'name')
                    ->required()
            ]);
    }
}
