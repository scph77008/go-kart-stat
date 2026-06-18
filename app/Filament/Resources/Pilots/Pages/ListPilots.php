<?php

namespace App\Filament\Resources\Pilots\Pages;

use App\Filament\Resources\Pilots\PilotResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPilots extends ListRecords
{
    protected static string $resource = PilotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
