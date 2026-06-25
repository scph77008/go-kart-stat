<?php

namespace App\Models;

use App\Contracts\EntrantInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 */
class Team extends Model implements EntrantInterface
{
    protected $fillable = [
        'name',
        'image'
    ];

    public function getDisplayName(): string
    {
        return $this->name;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
