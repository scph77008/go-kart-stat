<?php

namespace App\Observers;

use App\Filament\Resources\Events\EventType;
use App\Models\Participant;
use App\Models\Pilot;

class PilotObserver
{
    /**
     * Создаём запись участника после создания пилота
     *
     * @param \App\Models\Pilot $pilot
     * @return void
     */
    public function created(Pilot $pilot): void
    {
        Participant::create([
            'pilot_id' => $pilot->id,
            'type' => EventType::INDIVIDUAL,
        ]);
    }
}
