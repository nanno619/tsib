<?php

namespace App\Enums;

/**
 * The permission names seeded by RolePermissionSeeder.
 *
 * These are shared between the seeder, the policies and the views. Keeping them
 * as an enum rather than bare strings matters more than usual here: a policy
 * that checks a permission which no longer exists denies everyone *silently*
 * (see checkPermissionTo() in UserPolicy), so a typo or a rename on only one
 * side would look like a permissions bug rather than a code one.
 */
enum PermissionName: string
{
    case ViewAdminPanel = 'view admin panel';
    case ManageUsers = 'manage users';
}
