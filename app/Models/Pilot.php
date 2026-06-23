<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pilot extends Model
{
    protected $fillable = [
        'surname',
        'name',
        'birthday'
    ];

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function entryPilots()
    {
        return $this->hasMany(EntryPilot::class);
    }
}
