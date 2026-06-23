<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $event_id
 * @property int $participant_id
 * @property int|null $number
 * @property string|null $comment
 *
 * @property-read \App\Models\Event $event
 * @property-read \App\Models\Participant $participant
 */
class Entry extends Model
{
    protected $fillable = [
        'event_id',
        'participant_id',
        'number',
        'comment',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function pilots()
    {
        return $this->hasMany(EntryPilot::class);
    }

    public function results()
    {
        return $this->hasMany(EntryResult::class);
    }
}
