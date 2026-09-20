<?php

namespace App\Policies;

use App\Enums\PermissionName;
use App\Models\User;

/**
 * Policies are how Laravel answers "may this user do this to this model?".
 * spatie/laravel-permission answers "does this user hold this permission?" —
 * the two compose: the policy is the per-model rule, the permission is how the
 * role system grants it.
 *
 * Laravel discovers this automatically from the name and namespace
 * (App\Models\User -> App\Policies\UserPolicy), so nothing needs registering.
 *
 * Note `checkPermissionTo` rather than `hasPermissionTo`: the latter *throws*
 * PermissionDoesNotExist when the permission row is absent, so an unseeded
 * database would 500 on every check. This one returns false and denies.
 */
class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo(PermissionName::ViewAdminPanel);
    }

    public function view(User $user, User $model): bool
    {
        // You can always see yourself; otherwise it needs the panel permission.
        return $user->is($model) || $user->checkPermissionTo(PermissionName::ViewAdminPanel);
    }

    public function create(User $user): bool
    {
        return $user->checkPermissionTo(PermissionName::ManageUsers);
    }

    public function update(User $user, User $model): bool
    {
        return $user->checkPermissionTo(PermissionName::ManageUsers);
    }

    public function delete(User $user, User $model): bool
    {
        // Deleting your own account would revoke your access mid-session and,
        // for the last admin, lock everyone out of user management entirely.
        if ($user->is($model)) {
            return false;
        }

        return $user->checkPermissionTo(PermissionName::ManageUsers);
    }

    public function restore(User $user, User $model): bool
    {
        return $user->checkPermissionTo(PermissionName::ManageUsers);
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->checkPermissionTo(PermissionName::ManageUsers);
    }
}
