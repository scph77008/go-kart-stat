<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 */
class Team extends Model
{
    protected $fillable = [
        'name',
        'image'
    ];

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
}
