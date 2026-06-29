<?php

namespace App\Policies;

use App\Models\AuditableModel;
use App\Models\ResultCategory;
use App\Models\User;

class ResultCategoryPolicy extends BasePolicy
{
    /**
     * @param User $user
     * @param BasePolicy|\App\Models\AuditableModel $model
     * @return bool
     *
     *
     * Нельзя удалять 'абсолют' и прочие обязательные зачёты
     * Также скрыта сама кнопка в
     * @see ResultCategoriesRelationManager::table()
     */
    public function delete(User $user, AuditableModel|ResultCategory $model): bool
    {
        return !$model->is_required && parent::delete($user, $model);
    }
}
