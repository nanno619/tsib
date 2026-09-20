<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserCreationTest extends TestCase
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

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'New Person',
            'email' => 'new.person@example.com',
            'password' => 'secret-password-1',
            'password_confirmation' => 'secret-password-1',
        ], $overrides);
    }

    // --------------------------------------------------------------- access

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/admin/users/create')->assertRedirect('/login');
        $this->post('/admin/users', $this->validPayload())->assertRedirect('/login');
    }

    public function test_an_admin_can_open_the_create_form(): void
    {
        $response = $this->actingAs($this->userWithRole('admin'))->get('/admin/users/create');

        $response->assertOk();
        $response->assertSee('Tambah pengguna');
    }

    public function test_the_create_url_is_not_mistaken_for_a_user_id(): void
    {
        // The route has to be registered before /admin/users/{user}, or
        // "create" is bound as an id and this 404s.
        $this->actingAs($this->userWithRole('admin'))
            ->get('/admin/users/create')
            ->assertOk()
            ->assertDontSee('Page not found');
    }

    public function test_a_user_without_permission_cannot_create(): void
    {
        $plain = User::factory()->create();

        $this->actingAs($plain)->get('/admin/users/create')->assertForbidden();
        $this->actingAs($plain)->post('/admin/users', $this->validPayload())->assertForbidden();

        $this->assertDatabaseMissing('users', ['email' => 'new.person@example.com']);
    }

    // --------------------------------------------------------------- create

    public function test_an_admin_can_create_a_user(): void
    {
        $response = $this->actingAs($this->userWithRole('admin'))
            ->post('/admin/users', $this->validPayload());

        $user = User::where('email', 'new.person@example.com')->first();

        $this->assertNotNull($user);
        $this->assertSame('New Person', $user->name);
        $response->assertRedirect(route('admin.users.show', $user));
        $response->assertSessionHas('status', 'user-created');

        // And they turn up on the list.
        $this->actingAs($this->userWithRole('admin'))->get('/admin/users')
            ->assertOk()
            ->assertSee('New Person');
    }

    public function test_the_password_is_hashed_not_stored_as_typed(): void
    {
        // The model casts password as `hashed`, so the plain value from the
        // request is hashed on assignment.
        $this->actingAs($this->userWithRole('admin'))->post('/admin/users', $this->validPayload());

        $user = User::where('email', 'new.person@example.com')->firstOrFail();

        $this->assertNotSame('secret-password-1', $user->password);
        $this->assertTrue(Hash::check('secret-password-1', $user->password));
    }

    public function test_roles_can_be_assigned_on_creation(): void
    {
        Role::findOrCreate('teacher');

        $this->actingAs($this->userWithRole('admin'))
            ->post('/admin/users', $this->validPayload(['roles' => ['teacher']]))
            ->assertSessionHasNoErrors();

        $user = User::where('email', 'new.person@example.com')->firstOrFail();

        $this->assertSame(['teacher'], $user->getRoleNames()->all());
    }

    public function test_a_user_can_be_created_without_any_roles(): void
    {
        $this->actingAs($this->userWithRole('admin'))
            ->post('/admin/users', $this->validPayload())
            ->assertSessionHasNoErrors();

        $user = User::where('email', 'new.person@example.com')->firstOrFail();

        $this->assertSame([], $user->getRoleNames()->all());
    }

    // ----------------------------------------------------------- validation

    public function test_it_validates_the_basics(): void
    {
        $this->actingAs($this->userWithRole('admin'))
            ->post('/admin/users', $this->validPayload(['name' => '', 'email' => 'not-an-email']))
            ->assertSessionHasErrors(['name', 'email']);
    }

    public function test_the_email_must_be_unique(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $this->actingAs($this->userWithRole('admin'))
            ->post('/admin/users', $this->validPayload(['email' => 'taken@example.com']))
            ->assertSessionHasErrors('email');
    }

    public function test_the_password_must_be_confirmed_and_long_enough(): void
    {
        $this->actingAs($this->userWithRole('admin'))
            ->post('/admin/users', $this->validPayload(['password_confirmation' => 'something-else']))
            ->assertSessionHasErrors('password');

        $this->actingAs($this->userWithRole('admin'))
            ->post('/admin/users', $this->validPayload([
                'password' => 'short',
                'password_confirmation' => 'short',
            ]))
            ->assertSessionHasErrors('password');
    }

    public function test_the_password_rules_match_registration(): void
    {
        // StoreUserRequest reuses Fortify's PasswordValidationRules, so an admin
        // can't create an account with a password the user couldn't have set.
        $weak = 'short';

        $registered = $this->post('/register', [
            'name' => 'Via Register',
            'email' => 'via.register@example.com',
            'password' => $weak,
            'password_confirmation' => $weak,
        ]);

        $registered->assertSessionHasErrors('password');

        $this->actingAs($this->userWithRole('admin'))
            ->post('/admin/users', $this->validPayload([
                'email' => 'via.admin@example.com',
                'password' => $weak,
                'password_confirmation' => $weak,
            ]))
            ->assertSessionHasErrors('password');
    }

    public function test_an_unknown_role_is_rejected(): void
    {
        // syncRoles() would happily *create* a role named after whatever was
        // posted, so the request validates against the roles table first.
        $this->actingAs($this->userWithRole('admin'))
            ->post('/admin/users', $this->validPayload(['roles' => ['superuser']]))
            ->assertSessionHasErrors('roles.0');

        $this->assertDatabaseMissing('roles', ['name' => 'superuser']);
        $this->assertDatabaseMissing('users', ['email' => 'new.person@example.com']);
    }
}
