<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * The Access Control landing page (role overview) and per-role detail page.
 * Gated by the same ability as the Users screen — see RoleController's
 * docblock for why there's only one permission boundary, not two.
 */
class RoleManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    private function userWithRole(string $role): User
    {
        Role::findOrCreate($role);

        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/admin/roles')->assertRedirect('/login');
    }

    public function test_an_admin_sees_the_role_list_with_user_counts(): void
    {
        $admin = $this->userWithRole('admin');
        $this->userWithRole('teacher');

        $response = $this->actingAs($admin)->get('/admin/roles');

        $response->assertOk();
        $response->assertSee('Admin');
        $response->assertSee('Teacher');
        // Exactly one user holds each role: the acting admin, and the one
        // teacher created above.
        $response->assertSee('1 pengguna');
    }

    public function test_the_user_count_links_to_the_filtered_user_list(): void
    {
        $admin = $this->userWithRole('admin');
        $role = Role::findByName('admin');

        $html = $this->actingAs($admin)->get('/admin/roles')->getContent();
        $this->assertIsString($html);

        $expectedUrl = route('admin.users.index', ['roles' => [$role->name]]);
        $this->assertStringContainsString(e($expectedUrl), $html);
    }

    public function test_a_user_without_permission_is_forbidden(): void
    {
        $plain = User::factory()->create();

        $this->actingAs($plain)->get('/admin/roles')->assertForbidden();
    }

    public function test_an_admin_can_view_a_role_detail_page(): void
    {
        $admin = $this->userWithRole('admin');
        $role = Role::findByName('admin');

        $response = $this->actingAs($admin)->get(route('admin.roles.show', $role));

        $response->assertOk();
        $response->assertSee('Admin');
        $response->assertSee('view admin panel');
        $response->assertSee('manage users');
        // The acting admin themselves holds the role, so they should be
        // listed in the role's user table.
        $response->assertSee($admin->email);
    }

    public function test_a_role_with_no_permissions_shows_an_empty_state(): void
    {
        $admin = $this->userWithRole('admin');
        $teacherRole = Role::findOrCreate('teacher');

        $response = $this->actingAs($admin)->get(route('admin.roles.show', $teacherRole));

        $response->assertOk();
        $response->assertSee('Tiada kebenaran ditetapkan');
    }

    public function test_a_user_without_permission_cannot_view_a_role_detail_page(): void
    {
        $plain = User::factory()->create();
        $role = Role::findByName('admin');

        $this->actingAs($plain)->get(route('admin.roles.show', $role))->assertForbidden();
    }

    public function test_the_navigation_link_follows_the_policy(): void
    {
        $admin = $this->userWithRole('admin');
        $plain = User::factory()->create();
        $url = route('admin.roles.index');

        $adminHtml = $this->actingAs($admin)->get('/dashboard')->getContent();
        $plainHtml = $this->actingAs($plain)->get('/dashboard')->getContent();

        $this->assertIsString($adminHtml);
        $this->assertIsString($plainHtml);

        $this->assertStringContainsString($url, $adminHtml);
        $this->assertStringNotContainsString($url, $plainHtml);
        $this->assertStringContainsString('Access Control', $adminHtml);
    }
}
