<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EventInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('championship_id')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('date')
                    ->date(),
                TextEntry::make('duration')
                    ->numeric(),
                IconEntry::make('duration_in_minutes')
                    ->boolean(),
                TextEntry::make('track_id')
                    ->numeric(),
                IconEntry::make('reverse')
                    ->boolean(),
            ]);
    }
}
