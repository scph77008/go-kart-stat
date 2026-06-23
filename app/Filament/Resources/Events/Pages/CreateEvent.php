<?php

namespace App\Filament\Resources\Events\Pages;

use App\Filament\Resources\Events\EventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    protected function afterCreate(): void
    {
        $this->record->resultCategories()->firstOrCreate(
            ['name' => 'Абсолют'],
            ['participants' => $this->record->participants],
        );
    }
}
