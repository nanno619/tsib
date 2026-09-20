<?php

namespace Database\Seeders;

use App\Enums\PermissionName;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Seed a starter set of roles/permissions and assign 'admin' to the
     * demo user (test@example.com) created by UserSeeder.
     *
     * Permission names come from App\Enums\PermissionName, which the policies
     * also read — a rename on one side only would otherwise leave every policy
     * silently denying access.
     */
    public function run(): void
    {
        $viewAdminPanel = Permission::findOrCreate(PermissionName::ViewAdminPanel->value);
        $manageUsers = Permission::findOrCreate(PermissionName::ManageUsers->value);

        $admin = Role::findOrCreate('admin');
        $admin->syncPermissions([$viewAdminPanel, $manageUsers]);

        $editor = Role::findOrCreate('editor');
        $editor->syncPermissions([$viewAdminPanel]);

        User::where('email', 'test@example.com')->first()?->assignRole($admin);
    }
}
