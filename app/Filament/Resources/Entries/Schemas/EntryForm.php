<?php

namespace App\Filament\Resources\Entries\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EntryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('event_id')
                    ->relationship('event', 'name')
                    ->required(),
                Select::make('team_id')
                    ->relationship('team', 'name')
                    ->required(),
            ]);
    }
}
