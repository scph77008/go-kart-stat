<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntryResult extends Model
{
    protected $fillable = [
        'entry_id',
        'result_category_id',
        'position',
        'gap',
    ];

    public function entry()
    {
        return $this->belongsTo(Entry::class);
    }

    public function resultCategory()
    {
        return $this->belongsTo(ResultCategory::class);
    }
}
