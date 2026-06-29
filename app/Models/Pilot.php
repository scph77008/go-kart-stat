<?php

namespace App\Models;

use App\Contracts\EntrantInterface;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $surname
 * @property string $name
 * @property \DateTime $birthday
 */
class Pilot extends AuditableModel implements EntrantInterface
{
    protected $fillable = [
        'surname',
        'name',
        'birthday'
    ];

    protected $casts = [
        'birthday' => 'date'
    ];

    public function entryPilots(): HasMany
    {
        return $this->hasMany(EntryPilot::class);
    }

    public function getDisplayName(): string
    {
        return sprintf('%s %s', $this->surname, $this->name);
    }

    public function getId(): int
    {
        return $this->id;
    }
}
