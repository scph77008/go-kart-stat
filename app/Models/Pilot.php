<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $surname
 * @property string $name
 * @property \DateTime $birthday
 */
class Pilot extends Model
{
    protected $fillable = [
        'surname',
        'name',
        'birthday'
    ];

    protected $casts = [
        'birthday' => 'date'
    ];

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function entryPilots()
    {
        return $this->hasMany(EntryPilot::class);
    }

    protected function fullName()
    {
        return trim(sprintf('%s %s', $this->surname, $this->name));
    }
}
