<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultCategory extends Model
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

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function entryResults()
    {
        return $this->hasMany(EntryResult::class);
    }
}
