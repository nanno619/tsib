<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SuperAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_seeder_creates_the_admin_role_and_permissions(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $this->assertTrue(Role::where('name', 'admin')->exists());
        $this->assertTrue(Permission::where('name', 'view admin panel')->exists());
        $this->assertTrue(Permission::where('name', 'manage users')->exists());
    }

    public function test_the_seeder_is_idempotent(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $this->seed(RolePermissionSeeder::class);

        $this->assertSame(1, Role::count());
        $this->assertSame(2, Permission::count());
    }

    public function test_the_seeder_assigns_the_admin_role_to_the_demo_user(): void
    {
        $demo = User::factory()->create(['email' => 'test@example.com']);

        $this->seed(RolePermissionSeeder::class);

        $this->assertTrue($demo->refresh()->hasRole('admin'));
    }

    public function test_a_role_grants_its_permissions(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $plain = User::factory()->create();

        $this->assertTrue($admin->can('manage users'));
        $this->assertTrue($admin->can('view admin panel'));

        $this->assertFalse($plain->can('view admin panel'));
        $this->assertFalse($plain->can('manage users'));
    }

    public function test_the_starter_kit_page_reflects_the_users_permission(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $plain = User::factory()->create();

        $this->actingAs($admin)->get('/starter-kit')
            ->assertOk()
            ->assertSee('You can see this because your role has the')
            ->assertSee('manage users');

        // escape: false — the alert copy is literal Blade template text, so
        // the apostrophe is never HTML-encoded, while assertSee would encode
        // the search string by default.
        $this->actingAs($plain)->get('/starter-kit')
            ->assertOk()
            ->assertSee("You'd need the", escape: false)
            ->assertDontSee('You can see this because your role has the');
    }

    // ------------------------------------------------------------ superadmin

    public function test_the_database_seeder_wires_up_the_superadmin(): void
    {
        // Each seeder is covered on its own above; nothing else proves
        // DatabaseSeeder actually calls this one, or that it calls it after
        // the permissions exist.
        $this->seed(DatabaseSeeder::class);

        $this->assertTrue(
            User::where('email', 'superadmin@example.com')->firstOrFail()->hasRole('superadmin'),
        );
    }

    public function test_the_superadmin_role_holds_every_permission(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $this->seed(SuperAdminSeeder::class);

        $role = Role::where('name', 'superadmin')->firstOrFail();

        $this->assertSame(
            Permission::pluck('name')->sort()->values()->all(),
            $role->permissions->pluck('name')->sort()->values()->all(),
        );
    }

    public function test_the_superadmin_seeder_creates_a_user_who_can_reach_the_panel(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $this->seed(SuperAdminSeeder::class);

        $superadmin = User::where('email', 'superadmin@example.com')->firstOrFail();

        $this->assertTrue($superadmin->hasRole('superadmin'));

        $this->actingAs($superadmin)->get('/admin/users')->assertOk();
    }

    public function test_the_superadmin_seeder_is_idempotent(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $this->seed(SuperAdminSeeder::class);
        $this->seed(SuperAdminSeeder::class);

        $this->assertSame(1, Role::where('name', 'superadmin')->count());
        $this->assertSame(1, User::where('email', 'superadmin@example.com')->count());
    }

    public function test_rerunning_the_seeder_picks_up_a_permission_added_later(): void
    {
        // The promise of "all permissions" has to keep holding as the set
        // grows, which is what syncing against the table buys over a
        // hard-coded list.
        $this->seed(RolePermissionSeeder::class);
        $this->seed(SuperAdminSeeder::class);

        Permission::findOrCreate('a permission added later');
        $this->seed(SuperAdminSeeder::class);

        $this->assertTrue(
            Role::where('name', 'superadmin')->firstOrFail()->hasPermissionTo('a permission added later'),
        );
    }

    public function test_a_superadmin_is_still_blocked_by_the_policy_self_guards(): void
    {
        // The reason for granting permissions rather than installing a
        // Gate::before bypass: a bypass short-circuits *before* the policy
        // method runs, so it would skip this guard too and let a superadmin
        // delete their own account.
        $this->seed(RolePermissionSeeder::class);
        $this->seed(SuperAdminSeeder::class);

        $superadmin = User::where('email', 'superadmin@example.com')->firstOrFail();
        $other = User::factory()->create();

        // Everything else is fair game...
        $this->assertTrue($superadmin->can('delete', $other));

        // ...but not themselves.
        $this->assertFalse($superadmin->can('delete', $superadmin));
    }
}
