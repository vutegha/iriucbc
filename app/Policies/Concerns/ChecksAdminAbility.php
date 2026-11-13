<?php

namespace App\Policies\Concerns;

use App\Models\User;

trait ChecksAdminAbility
{
    /**
     * True when the user holds one of the given roles (or is super admin),
     * or has the named permission. A permission that does not exist in the
     * database counts as "not granted" instead of throwing.
     */
    protected function allows(User $user, string $permission, array $roles = ['admin']): bool
    {
        if ($user->isSuperAdmin() || $user->hasAnyRole(array_merge(['super-admin'], $roles))) {
            return true;
        }

        return $user->checkPermissionTo($permission);
    }
}
