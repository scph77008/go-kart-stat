<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EntryPilot extends AuditableModel
{
    protected $fillable = [
        'entry_id',
        'pilot_id',
    ];

    public function entry(): BelongsTo
    {
        return $this->belongsTo(Entry::class);
    }

    public function pilot(): BelongsTo
    {
        return $this->belongsTo(Pilot::class);
    }
}
