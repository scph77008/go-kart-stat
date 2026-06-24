<?php

namespace App\Observers;

use App\Filament\Resources\Events\EventType;
use App\Models\Participant;
use App\Models\Team;

class TeamObserver
{

    /**
     * Создаём запись участника после создания команды
     *
     * @param \App\Models\Team $team
     * @return void
     */
    public function created(Team $team): void
    {
        Participant::create([
            'team_id' => $team->id,
            'type' => EventType::TEAM,
        ]);
    }
}
