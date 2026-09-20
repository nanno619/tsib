<?php

namespace Tests\Feature\Admin;

use App\Enums\PermissionName;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The policy in isolation, through the Gate — no HTTP involved. The controller
 * tests then prove the same rules are actually wired to the routes.
 */
class UserPolicyTest extends TestCase
{
    use RefreshDatabase;

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }

    public function test_an_admin_may_browse_users(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $this->assertTrue($this->userWithRole('admin')->can('viewAny', User::class));
    }

    public function test_a_user_without_a_role_may_not_browse_users(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $this->assertFalse(User::factory()->create()->can('viewAny', User::class));
    }

    public function test_anyone_may_view_themselves(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $user = User::factory()->create();

        $this->assertTrue($user->can('view', $user));
    }

    public function test_viewing_someone_else_needs_the_panel_permission(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $plain = User::factory()->create();
        $other = User::factory()->create();

        $this->assertFalse($plain->can('view', $other));
        $this->assertTrue($this->userWithRole('admin')->can('view', $other));
    }

    public function test_only_manage_users_may_delete(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $victim = User::factory()->create();

        $this->assertTrue($this->userWithRole('admin')->can('delete', $victim));

        $this->assertFalse(User::factory()->create()->can('delete', $victim));
    }

    public function test_nobody_may_delete_themselves(): void
    {
        $this->seed(RolePermissionSeeder::class);

        // Otherwise an admin removes their own access mid-session, and a sole
        // admin would lock everyone out of user management.
        $admin = $this->userWithRole('admin');

        $this->assertFalse($admin->can('delete', $admin));
    }

    public function test_missing_permissions_deny_rather_than_throw(): void
    {
        // Deliberately unseeded. hasPermissionTo() would throw
        // PermissionDoesNotExist and 500 the request; the policy uses
        // checkPermissionTo() so an unseeded database simply denies.
        $user = User::factory()->create();

        $this->assertFalse($user->can('viewAny', User::class));
        $this->assertFalse($user->can('delete', User::factory()->create()));

        // And the names are the ones the seeder actually creates.
        $this->seed(RolePermissionSeeder::class);

        $this->assertDatabaseHas('permissions', ['name' => PermissionName::ViewAdminPanel->value]);
        $this->assertDatabaseHas('permissions', ['name' => PermissionName::ManageUsers->value]);
    }
}
