<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property $event_id
 * @property $name
 * @property $participants
 * @property $is_required
 */
class ResultCategory extends AuditableModel
{
    protected $fillable = [
        'event_id',
        'name',
        'participants',
        'is_required',
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function entryResults()
    {
        return $this->hasMany(EntryResult::class);
    }
}
