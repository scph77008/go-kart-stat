<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntryPilot extends Model
{
    protected $fillable = [
        'entry_id',
        'pilot_id',
    ];

    public function entry()
    {
        return $this->belongsTo(Entry::class);
    }

    public function pilot()
    {
        return $this->belongsTo(Pilot::class);
    }
}
