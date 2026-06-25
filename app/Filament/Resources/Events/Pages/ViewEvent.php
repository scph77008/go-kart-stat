<?php

namespace App\Filament\Resources\Events\Pages;

use App\Filament\Resources\Events\EventResource;
use App\Models\Event;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;

/**
 * @property-read Event $record
 */
class ViewEvent extends ViewRecord
{
    protected static string $resource = EventResource::class;

    public function getTitle(): string|Htmlable
    {
        return sprintf('%s, %s',
            $this->record->name,
            $this->record->date->format('d.m.Y')
        );
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('championship.name')
                ->label('Чемпионат'),

            TextEntry::make('track.name')
                ->label('Трек')
                ->formatStateUsing(fn($state, Event $record) => sprintf(
                    '%s (%s)',
                    $record->track->name,
                    $record->reverse ? 'в обратку' : 'в тудатку'
                )),

            TextEntry::make('duration_label')
                ->label('Длительность'),

            TextEntry::make('participants')
                ->label('Участников'),
        ]);
    }

}
