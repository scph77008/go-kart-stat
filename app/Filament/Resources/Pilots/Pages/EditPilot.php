<?php

namespace App\Filament\Resources\Pilots\Pages;

use App\Filament\Resources\Pilots\PilotResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPilot extends EditRecord
{
    protected static string $resource = PilotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
