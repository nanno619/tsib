<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserEditingTest extends TestCase
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

    // ------------------------------------------------------------------ show

    public function test_an_admin_can_view_a_users_detail_page(): void
    {
        $admin = $this->userWithRole('admin');
        $target = User::factory()->create(['name' => 'Ada Lovelace']);

        $response = $this->actingAs($admin)->get(route('admin.users.show', $target));

        $response->assertOk();
        $response->assertSee('Ada Lovelace');
        $response->assertSee($target->email);
    }

    public function test_a_user_can_view_their_own_detail_page(): void
    {
        // The policy allows self-view regardless of permissions.
        $plain = User::factory()->create(['name' => 'Plain Person']);

        $this->actingAs($plain)->get(route('admin.users.show', $plain))
            ->assertOk()
            ->assertSee('Plain Person');
    }

    public function test_a_user_without_permission_cannot_view_someone_else(): void
    {
        $plain = User::factory()->create();
        $other = User::factory()->create();

        $this->actingAs($plain)->get(route('admin.users.show', $other))->assertForbidden();
    }

    // ------------------------------------------------------------------ edit

    public function test_an_admin_can_open_the_edit_form(): void
    {
        $admin = $this->userWithRole('admin');
        $target = User::factory()->create();

        $response = $this->actingAs($admin)->get(route('admin.users.edit', $target));

        $response->assertOk();
        $response->assertSee('Sunting pengguna');
    }

    public function test_a_user_without_permission_cannot_open_the_edit_form(): void
    {
        $plain = User::factory()->create();
        $target = User::factory()->create();

        $this->actingAs($plain)->get(route('admin.users.edit', $target))->assertForbidden();
    }

    public function test_the_edit_form_offers_the_roles_field_for_other_users(): void
    {
        $admin = $this->userWithRole('admin');
        $target = User::factory()->create();

        $this->actingAs($admin)->get(route('admin.users.edit', $target))
            ->assertOk()
            ->assertSee('name="roles[]"', escape: false);
    }

    public function test_the_edit_form_hides_the_roles_field_for_yourself(): void
    {
        $admin = $this->userWithRole('admin');

        $response = $this->actingAs($admin)->get(route('admin.users.edit', $admin));

        $response->assertOk();
        $response->assertSee('Anda tidak boleh menukar peranan anda sendiri');
        $response->assertDontSee('name="roles[]"', escape: false);
    }

    // ---------------------------------------------------------------- update

    public function test_an_admin_can_update_a_users_details(): void
    {
        $admin = $this->userWithRole('admin');
        $target = User::factory()->create(['name' => 'Old Name', 'email' => 'old@example.com']);

        $this->actingAs($admin)
            ->put(route('admin.users.update', $target), [
                'name' => 'New Name',
                'email' => 'new@example.com',
            ])
            ->assertRedirect(route('admin.users.show', $target));

        $target->refresh();
        $this->assertSame('New Name', $target->name);
        $this->assertSame('new@example.com', $target->email);
    }

    public function test_updating_requires_a_name_and_a_valid_email(): void
    {
        $admin = $this->userWithRole('admin');
        $target = User::factory()->create();

        $this->actingAs($admin)
            ->put(route('admin.users.update', $target), ['name' => '', 'email' => 'not-an-email'])
            ->assertSessionHasErrors(['name', 'email']);
    }

    public function test_the_email_must_be_unique_but_not_against_the_user_themselves(): void
    {
        $admin = $this->userWithRole('admin');
        $target = User::factory()->create(['email' => 'target@example.com']);
        User::factory()->create(['email' => 'taken@example.com']);

        // Colliding with someone else is rejected...
        $this->actingAs($admin)
            ->put(route('admin.users.update', $target), ['name' => $target->name, 'email' => 'taken@example.com'])
            ->assertSessionHasErrors('email');

        // ...but resubmitting their own address is not, which is the whole
        // point of Rule::unique()->ignore().
        $this->actingAs($admin)
            ->put(route('admin.users.update', $target), ['name' => 'Renamed', 'email' => 'target@example.com'])
            ->assertSessionHasNoErrors();

        $this->assertSame('Renamed', $target->refresh()->name);
    }

    public function test_an_admin_can_change_another_users_roles(): void
    {
        Role::findOrCreate('teacher');

        $admin = $this->userWithRole('admin');
        $target = User::factory()->create();

        $this->actingAs($admin)
            ->put(route('admin.users.update', $target), [
                'name' => $target->name,
                'email' => $target->email,
                'roles' => ['teacher'],
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame(['teacher'], $target->refresh()->getRoleNames()->all());
    }

    public function test_an_admin_cannot_change_their_own_roles(): void
    {
        Role::findOrCreate('teacher');

        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)->put(route('admin.users.update', $admin), [
            'name' => 'Still Me',
            'email' => $admin->email,
            'roles' => ['teacher'],
        ]);

        // Name still saves; the roles field is ignored rather than honoured.
        $admin->refresh();
        $this->assertSame('Still Me', $admin->name);
        $this->assertSame(['admin'], $admin->getRoleNames()->all());
    }

    public function test_an_unknown_role_is_rejected(): void
    {
        // Without validation, syncRoles() would happily *create* a role named
        // after whatever the request contained.
        $admin = $this->userWithRole('admin');
        $target = User::factory()->create();

        $this->actingAs($admin)
            ->put(route('admin.users.update', $target), [
                'name' => $target->name,
                'email' => $target->email,
                'roles' => ['superuser'],
            ])
            ->assertSessionHasErrors('roles.0');

        $this->assertDatabaseMissing('roles', ['name' => 'superuser']);
    }

    public function test_a_user_without_permission_cannot_update(): void
    {
        $plain = User::factory()->create();
        $target = User::factory()->create(['name' => 'Untouchable']);

        $this->actingAs($plain)
            ->put(route('admin.users.update', $target), ['name' => 'Changed', 'email' => $target->email])
            ->assertForbidden();

        $this->assertSame('Untouchable', $target->refresh()->name);
    }
}
