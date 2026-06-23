<?php

namespace App\Models;

use App\Filament\Resources\Events\EventType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = [
        'type',
        'team_id',
        'pilot_id'
    ];

    protected $casts = [
        'type' => EventType::class,
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function pilot()
    {
        return $this->belongsTo(Pilot::class);
    }

    public function entries()
    {
        return $this->hasMany(Entry::class);
    }

    protected function displayName(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                if ($this->type === EventType::TEAM) {
                    return $this->team?->name ?? 'Team participant';
                }

                $pilotName = trim(sprintf('%s %s', $this->pilot?->surname, $this->pilot?->name));

                return $pilotName !== '' ? $pilotName : 'Individual participant';
            }
        );
    }
}
