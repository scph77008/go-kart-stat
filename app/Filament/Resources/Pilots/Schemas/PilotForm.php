<?php

namespace App\Filament\Resources\Pilots\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PilotForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('surname')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                DatePicker::make('birthday'),
            ]);
    }
}
