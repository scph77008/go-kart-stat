<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EntryResult extends AuditableModel
{
    protected $fillable = [
        'entry_id',
        'result_category_id',
        'position',
        'gap',
    ];

    protected $casts = [
        'gap' => 'float'
    ];

    public function entry(): BelongsTo
    {
        return $this->belongsTo(Entry::class);
    }

    public function resultCategory(): BelongsTo
    {
        return $this->belongsTo(ResultCategory::class);
    }
}
