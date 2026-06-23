<?php

namespace App\Filament\Resources\Entries\Schemas;

use App\Models\Participant;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
                Select::make('participant_id')
                    ->label('Participant')
                    ->options(fn () => Participant::query()
                        ->with(['team', 'pilot'])
                        ->get()
                        ->mapWithKeys(fn (Participant $participant) => [
                            $participant->id => $participant->display_name,
                        ]))
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('number')
                    ->numeric(),
                Textarea::make('comment')
                    ->columnSpanFull(),
            ]);
    }
}
