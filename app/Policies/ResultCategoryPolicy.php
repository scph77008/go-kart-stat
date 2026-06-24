<?php

namespace App\Policies;

use App\Models\ResultCategory;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ResultCategoryPolicy
{
    /**
     * Нельзя удалять "абсолют" и прочие обязательные зачёты
     * Также скрыта сама кнопка в
     * @see ResultCategoriesRelationManager::table()
     */
    public function delete(User $user, ResultCategory $resultCategory): bool
    {
        return !$resultCategory->is_required;
    }
}
