<?php

namespace Database\Seeders;

use App\Enums\PermissionName;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * The three fixed KMS roles (principal/admin/teacher) plus the two
 * permissions from App\Enums\PermissionName — safe to run in production,
 * unlike RolePermissionSeeder (which is demo-only and also creates an
 * 'editor' role used purely by the starter-kit's own tests).
 *
 * Only 'admin' gets ViewAdminPanel/ManageUsers: Pengguna & Akaun is
 * admin-only per 03-app-flow.md.
 */
class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $viewAdminPanel = Permission::findOrCreate(PermissionName::ViewAdminPanel->value);
        $manageUsers = Permission::findOrCreate(PermissionName::ManageUsers->value);

        foreach (['principal', 'admin', 'teacher'] as $name) {
            Role::findOrCreate($name);
        }

        Role::findOrCreate('admin')->syncPermissions([$viewAdminPanel, $manageUsers]);
    }
}
