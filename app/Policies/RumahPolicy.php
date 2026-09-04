<?php

namespace App\Policies;

use App\Models\Rumah;
use App\Models\User;

class RumahPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManageAll() || $user->isKadus();
    }

    public function view(User $user, Rumah $rumah): bool
    {
        return $user->canManageAll() || $user->isKadus();
    }

    public function create(User $user): bool
    {
        return $user->canManageAll();
    }

    public function update(User $user, Rumah $rumah): bool
    {
        return $user->canManageAll();
    }

    public function delete(User $user, Rumah $rumah): bool
    {
        return $user->isSuperAdmin();
    }
}
