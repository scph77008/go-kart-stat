<?php

namespace App\Policies;

use App\Models\AuditableModel;
use App\Models\User;

abstract class BasePolicy
{

    public function delete(User $user, AuditableModel $model): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $this->canDeleteOwnWithinTime($user, $model);
    }

    /**
     * @param \App\Models\User $user
     * @param $model
     * @return bool
     *
     * Базово можно удалять только свои и созданные не более чем час назад записи
     */
    protected function canDeleteOwnWithinTime(User $user, $model): bool
    {
        return $this->isOwner($user, $model)
            && $this->withinTimeLimit($model);
    }

    protected function isOwner(User $user, $model): bool
    {
        return isset($model->created_by)
            && $model->created_by === $user->id;
    }

    /**
     * @param $model
     * @param int $minutes
     * @return bool
     */
    protected function withinTimeLimit($model, int $minutes = 60): bool
    {
        return $model->created_at?->gt(now()->subMinutes($minutes));
    }
}
