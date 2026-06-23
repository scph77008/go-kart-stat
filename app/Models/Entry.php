<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $position
 * @property int $event_id
 * @property int $team_id
 *
 * @property-read \App\Models\Event $event
 * @property-read \App\Models\Team $team
 */
class Entry extends Model
{
    protected $fillable = [
        'event_id',
        'team_id',
        'position'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
