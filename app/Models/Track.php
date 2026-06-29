<?php

namespace App\Models;

class Track extends AuditableModel
{
    protected $fillable = [
        'name',
        'location',
        'length'
    ];
}
