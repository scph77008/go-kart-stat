<?php

namespace App\Policies;

class InvitePolicy
{
    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin();
    }
}
