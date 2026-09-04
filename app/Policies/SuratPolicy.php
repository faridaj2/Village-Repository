<?php

namespace App\Policies;

use App\Models\Surat;
use App\Models\User;

class SuratPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManageAll() || $user->isKadus();
    }

    public function view(User $user, Surat $surat): bool
    {
        return $user->canManageAll() || $user->isKadus();
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Surat $surat): bool
    {
        return $user->canManageAll();
    }

    public function delete(User $user, Surat $surat): bool
    {
        return $user->isSuperAdmin();
    }

    public function proses(User $user, Surat $surat): bool
    {
        return $user->canManageAll();
    }
}
