<?php

namespace App\Policies;

use App\Enums\PermissionName;
use App\Models\User;

/**
 * There's only ever one Setting row, so both abilities are class-level
 * (no model instance parameter) — same shape as UserPolicy::create.
 */
class SettingPolicy
{
    public function view(User $user): bool
    {
        return $user->checkPermissionTo(PermissionName::ManageSettings);
    }

    public function update(User $user): bool
    {
        return $user->checkPermissionTo(PermissionName::ManageSettings);
    }
}
