<?php

namespace App\Models;

use App\Enums\EventDurationType;
use App\Enums\EventType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $name
 * @property int $championship_id
 * @property int $track_id
 * @property int $duration
 * @property bool $reverse
 *
 * @property \Carbon\Carbon $date
 * @property EventType $type
 * @property EventDurationType $duration_type
 * @property-read HasMany $resultCategories
 */
class Event extends AuditableModel
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
        'date' => 'date',
        'type' => EventType::class,
        'duration_type' => EventDurationType::class,
    ];

    public function championship(): BelongsTo
    {
        return $this->belongsTo(Championship::class);
    }

    public function track(): BelongsTo
    {
        return $this->belongsTo(Track::class);
    }

    public function entries(): HasMany
    {
        return $this->hasMany(Entry::class);
    }

    public function resultCategories(): HasMany
    {
        return $this->hasMany(ResultCategory::class);
    }

    protected function durationLabel(): Attribute
    {
        if ($this->duration_type === EventDurationType::MINUTES) {
            return $this->durationLabelInMinutes();
        }

        return $this->durationLabelInLaps();
    }

    private function durationLabelInMinutes(): Attribute
    {
        return Attribute::make(
            get: fn () => sprintf('%2.f ч.', $this->duration / 60)
        );
    }

    private function durationLabelInLaps(): Attribute
    {
        return Attribute::make(
            get: fn () => sprintf('%d кр', $this->duration)
        );
    }
}
