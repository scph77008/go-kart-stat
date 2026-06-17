<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name',
        'championship_id'
    ];

    public function championship()
    {
        return $this->belongsTo(Championship::class);
    }
}
