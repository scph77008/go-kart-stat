<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $created_by
 * @property string $updated_by
 * @property string $deleted_by
 * @property string $deleted_at
 */
class AuditableModel extends Model
{
    use SoftDeletes;
    use Auditable;
}
