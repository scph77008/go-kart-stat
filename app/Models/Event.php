<?php

namespace App\Models;

use App\Filament\Resources\Events\EventDurationType;
use App\Filament\Resources\Events\EventType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property int $championship_id
 * @property int $track_id
 * @property string $date
 * @property int $duration
 * @property boolean $reverse
 */
class Event extends Model
{
    protected $fillable = [
        'name',
        'championship_id',
        'track_id',
        'date',
        'type',
        'participants',
        'duration',
        'duration_type',
        'reverse',
    ];

    protected $casts = [
        'type' => EventType::class,
        'duration_type' => EventDurationType::class,
    ];

    public function championship()
    {
        return $this->belongsTo(Championship::class);
    }

    public function track()
    {
        return $this->belongsTo(Track::class);
    }

    public function entries()
    {
        return $this->hasMany(Entry::class);
    }

    public function resultCategories()
    {
        return $this->hasMany(ResultCategory::class);
    }

    protected function durationLabel(): Attribute
    {
        if($this->duration_type === EventDurationType::MINUTES) {
            return $this->durationLabelInMinutes();
        }

        return $this->durationLabelInLaps();
    }

    private function durationLabelInMinutes()
    {
        return Attribute::make(
            get: fn () => sprintf('%2.f ч.', $this->duration / 60)
        );
    }

    private function durationLabelInLaps()
    {
        return Attribute::make(
            get: fn () => sprintf('%d кр', $this->duration)
        );
    }
}
