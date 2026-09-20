<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Seeds the 'superadmin' role and one account holding it.
 *
 * Run it *after* RolePermissionSeeder — this grants every permission that
 * exists, so on a database where that hasn't run it would grant nothing.
 *
 * The permissions are granted explicitly rather than through the other common
 * super-admin pattern, a `Gate::before` bypass. A Gate::before short-circuits
 * before the policy method is ever called, so it would also skip
 * UserPolicy::delete()'s self-guard and let a superadmin delete their own
 * account. Granting the rows keeps every policy guard intact, and
 * syncPermissions() re-reads the table each run, so a permission added later
 * is picked up by re-seeding.
 *
 * The seeded password is the factory default ('password'). This is a demo
 * account — delete it, or set a real password, before this ever runs in
 * production.
 */
class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $superadmin = Role::findOrCreate('superadmin');
        $superadmin->syncPermissions(Permission::all());

        // withTrashed: users soft-delete, and a deleted superadmin would be
        // invisible to this lookup — the factory would then try to insert a
        // duplicate email against a unique index and the seed would crash.
        $user = User::withTrashed()->where('email', 'superadmin@example.com')->first()
            ?? User::factory()->create([
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
            ]);

        $user->assignRole($superadmin);
    }
}
