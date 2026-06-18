<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name',
        'championship_id',
        'track_id',
        'date',
        'duration',
        'duration_in_minutes',
        'reverse',
    ];

    public function championship()
    {
        return $this->belongsTo(Championship::class);
    }

    public function track()
    {
        return $this->belongsTo(Track::class);
    }
}
