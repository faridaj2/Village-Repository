<?php

namespace App\Policies;

use App\Models\Penduduk;
use App\Models\User;

class PendudukPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManageAll() || $user->isKadus();
    }

    public function view(User $user, Penduduk $penduduk): bool
    {
        return $user->canManageAll() || $user->isKadus();
    }

    public function create(User $user): bool
    {
        return $user->canManageAll();
    }

    public function update(User $user, Penduduk $penduduk): bool
    {
        return $user->canManageAll();
    }

    public function delete(User $user, Penduduk $penduduk): bool
    {
        return $user->isSuperAdmin();
    }
}
