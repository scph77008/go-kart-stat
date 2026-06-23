<?php

namespace App\Filament\Resources\Participants\Schemas;

use App\Filament\Resources\Events\EventType;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class ParticipantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->options(EventType::class)
                    ->default(EventType::TEAM->value)
                    ->required(),
                Select::make('team_id')
                    ->relationship('team', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('pilot_id')
                    ->relationship('pilot', 'surname')
                    ->getOptionLabelFromRecordUsing(fn ($record) => trim($record->surname . ' ' . $record->name))
                    ->searchable()
                    ->preload(),
            ]);
    }
}
