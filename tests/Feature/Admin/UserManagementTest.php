<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The policy as enforced on the routes — the #[Authorize] attributes on the
 * controller, and the `can` directive in the view. A policy that exists but
 * isn't wired up protects nothing, so these matter as much as UserPolicyTest.
 */
class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/admin/users')->assertRedirect('/login');
    }

    public function test_an_admin_sees_the_user_list(): void
    {
        $admin = $this->userWithRole('admin');
        $target = User::factory()->create(['name' => 'Ada Lovelace']);

        $response = $this->actingAs($admin)->get('/admin/users');

        $response->assertOk();
        $response->assertSee('Semua pengguna');
        $response->assertSee('Ada Lovelace');
        $response->assertSee($target->email);
    }

    public function test_the_list_shows_roles(): void
    {
        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)->get('/admin/users')
            ->assertOk()
            ->assertSee('admin');
    }

    public function test_the_delete_button_is_hidden_for_users_who_cannot_delete(): void
    {
        $admin = $this->userWithRole('admin');
        $other = User::factory()->create(['name' => 'Deletable Person']);

        $html = $this->actingAs($admin)->get('/admin/users')->getContent();
        $this->assertIsString($html);

        // Match the button's accessible name, not the URL: show and destroy
        // share a path and differ only by verb, so a URL assertion can't tell
        // the two apart.
        $this->assertStringContainsString('aria-label="Padam Deletable Person"', $html);
        $this->assertStringNotContainsString('aria-label="Padam '.$admin->name.'"', $html);
    }

    public function test_a_user_without_permission_is_forbidden(): void
    {
        $plain = User::factory()->create();

        $response = $this->actingAs($plain)->get('/admin/users');

        $response->assertForbidden();
        // Rendered by the themed error page, not Laravel's default.
        $response->assertSee('Access denied');
    }

    public function test_an_admin_can_delete_another_user(): void
    {
        $admin = $this->userWithRole('admin');
        $victim = User::factory()->create();

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $victim))
            ->assertRedirect();

        // Soft-deleted, not gone — see the restore tests.
        $this->assertSoftDeleted('users', ['id' => $victim->id]);
    }

    public function test_a_user_without_permission_cannot_delete(): void
    {
        $plain = User::factory()->create();
        $victim = User::factory()->create();

        $this->actingAs($plain)
            ->delete(route('admin.users.destroy', $victim))
            ->assertForbidden();

        $this->assertDatabaseHas('users', ['id' => $victim->id]);
    }

    public function test_an_admin_cannot_delete_themselves(): void
    {
        // The hidden button is only cosmetic — the route must refuse too.
        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $admin))
            ->assertForbidden();

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_row_actions_carry_tooltips(): void
    {
        // Tooltips come from Tabler's page-load initialiser, which scans for
        // [data-bs-toggle="tooltip"] and reads data-bs-placement. The markup is
        // the whole mechanism — losing these attributes loses the hint silently.
        $admin = $this->userWithRole('admin');
        $other = User::factory()->create(['name' => 'Ada Lovelace']);

        $html = $this->actingAs($admin)->get('/admin/users')->getContent();
        $this->assertIsString($html);

        // Collapse whitespace first: Blade keeps the newlines between
        // attributes, so a contiguous substring match would depend on how the
        // markup happens to be wrapped.
        $flat = (string) preg_replace('/\s+/', ' ', $html);

        foreach (['Lihat', 'Sunting', 'Padam'] as $action) {
            $this->assertStringContainsString(
                'data-bs-toggle="tooltip" data-bs-placement="top" title="'.$action.'"',
                $flat,
            );

            // The accessible name stays descriptive even though the tooltip is
            // short, and survives Bootstrap removing `title` while showing.
            $this->assertStringContainsString('aria-label="'.$action.' Ada Lovelace"', $flat);
        }
    }

    public function test_deleting_redirects_with_a_flashed_confirmation(): void
    {
        // Confirming the modal submits the form for real, so the controller is
        // a plain redirect + flash — no JSON branch to maintain.
        $admin = $this->userWithRole('admin');
        $victim = User::factory()->create();

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $victim))
            ->assertRedirect()
            ->assertSessionHas('status', 'user-deleted');

        $this->assertSoftDeleted('users', ['id' => $victim->id]);

        // And the page the redirect lands on renders it.
        $this->actingAs($admin)->get('/admin/users')
            ->assertOk()
            ->assertSee('Pengguna telah dipadam.');
    }

    public function test_delete_forms_carry_the_attributes_the_js_reads(): void
    {
        $admin = $this->userWithRole('admin');
        $other = User::factory()->create(['name' => 'Deletable Person']);

        $html = $this->actingAs($admin)->get('/admin/users')->getContent();
        $this->assertIsString($html);

        $flat = (string) preg_replace('/\s+/', ' ', $html);

        // Isolate this row's form and assert on its attributes as a set.
        // Matching them as one adjacent string would pin the order they happen
        // to appear in, so any harmless reordering would fail the test.
        $matched = preg_match(
            '/<form [^>]*action="'.preg_quote(route('admin.users.destroy', $other), '/').'"[^>]*>/',
            $flat,
            $matches,
        );

        if ($matched !== 1) {
            $this->fail('No delete form found for that row.');
        }

        $tag = $matches[0];

        // The confirm dialog is driven from the form...
        $this->assertStringContainsString('data-confirm-delete', $tag);
        $this->assertStringContainsString('data-confirm-message="Deletable Person', $tag);

        // ...but it stays a real form, so deleting still works without JS via
        // the inline confirm(), and the method spoof is what reaches the
        // DELETE route.
        $this->assertStringContainsString('name="_method" value="DELETE"', $flat);
        $this->assertStringContainsString('onsubmit="return confirm(', $tag);
    }

    public function test_the_layout_ships_the_shared_confirm_modal(): void
    {
        // Without it the JS has nothing to drive, and every delete falls
        // through to the browser's own confirm().
        $admin = $this->userWithRole('admin');

        $html = $this->actingAs($admin)->get('/dashboard')->getContent();
        $this->assertIsString($html);

        $this->assertStringContainsString('id="confirm-modal"', $html);
        $this->assertStringContainsString('id="confirm-modal-message"', $html);
        $this->assertStringContainsString('id="confirm-modal-confirm"', $html);
        $this->assertStringContainsString('id="confirm-modal-cancel"', $html);
    }

    public function test_the_navigation_link_follows_the_policy(): void
    {
        $admin = $this->userWithRole('admin');
        $plain = User::factory()->create();
        $url = route('admin.users.index');

        $adminHtml = $this->actingAs($admin)->get('/dashboard')->getContent();
        $plainHtml = $this->actingAs($plain)->get('/dashboard')->getContent();

        $this->assertIsString($adminHtml);
        $this->assertIsString($plainHtml);

        $this->assertStringContainsString($url, $adminHtml);
        $this->assertStringNotContainsString($url, $plainHtml);
        $this->assertStringContainsString('Pengguna', $adminHtml);
    }
}
