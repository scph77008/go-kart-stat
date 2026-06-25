<?php

namespace App\Models;

use App\Filament\Resources\Events\EventType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $event_id
 * @property int|null $number
 * @property string|null $comment
 *
 * @property-read \App\Models\Event $event
 */
class Entry extends Model
{
    protected $fillable = [
        'event_id',
        'entrant_type',
        'entrant_id',
        'number',
        'comment',
    ];

    protected $casts = [
        'entrant_type' => EventType::class
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function team(): HasOne
    {
        return $this->hasOne(Team::class);
    }

    public function pilot(): HasOne
    {
        return $this->hasOne(Pilot::class);
    }

    public function pilots(): HasMany
    {
        return $this->hasMany(EntryPilot::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(EntryResult::class);
    }
}
