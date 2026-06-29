<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property string $token
 * @property int $created_by
 * @property Carbon $used_at
 */
class Invite extends Model
{
    protected $fillable = [
        'token',
        'created_by',
        'used_at',
    ];

    protected static function booted()
    {
        static::creating(function (Invite $invite) {
            $invite->token = (string)Str::uuid();
            $invite->created_by = auth()->id() ?? User::ADMIN_ID;
        });
    }

    public function isValid(): bool
    {
        return !$this->used_at;
    }

    protected function casts(): array
    {
        return [
            'used_at' => 'datetime',
        ];
    }
}
