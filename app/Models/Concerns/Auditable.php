<?php

namespace App\Models\Concerns;

use App\Models\AuditableModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


trait Auditable
{
    protected static function bootAuditable(): void
    {
        static::creating(function (AuditableModel $model) {
            $model->created_by = auth()->id() ?? 1;
        });

        static::updating(function (AuditableModel $model) {
            $model->updated_by = auth()->id() ?? 1;
        });

        static::deleting(function (AuditableModel $model) {
            if (!method_exists($model, 'isForceDeleting')
                || !$model->isForceDeleting()) {
                $model->deleted_by = auth()->id() ?? 1;
                $model->saveQuietly();
            }
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
