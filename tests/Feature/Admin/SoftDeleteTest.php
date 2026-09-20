<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Deleting a user soft-deletes the row rather than removing it.
 *
 * There is deliberately no UI for deleted rows right now — no list, no restore
 * action, no route. These cover what the model-level behaviour still means to
 * the app: the user disappears from the list, can't log in, and keeps their
 * email reserved.
 */
class SoftDeleteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    private function admin(): User
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        return $user;
    }

    public function test_deleting_soft_deletes_the_row(): void
    {
        $victim = User::factory()->create();

        $this->actingAs($this->admin())
            ->delete(route('admin.users.destroy', $victim))
            ->assertRedirect();

        $this->assertSoftDeleted('users', ['id' => $victim->id]);
    }

    public function test_a_deleted_user_drops_off_the_list(): void
    {
        $victim = User::factory()->create(['name' => 'Gone Away']);
        $victim->delete();

        $this->actingAs($this->admin())->get('/admin/users')
            ->assertOk()
            ->assertDontSee('Gone Away');
    }

    public function test_a_deleted_user_cannot_log_in(): void
    {
        // The auth provider queries through the model, so the soft-delete scope
        // hides them — a deleted account can't authenticate.
        $user = User::factory()->create(['password' => 'secret-password']);
        $user->delete();

        $this->post('/login', ['email' => $user->email, 'password' => 'secret-password']);

        $this->assertGuest();
    }

    public function test_a_deleted_users_email_stays_reserved(): void
    {
        // Known and deliberate. Laravel's `unique` rule queries through the
        // query builder, which has no soft-delete scope, and users.email has a
        // real unique index — so a trashed row still holds its address. Freeing
        // it would mean dropping the index (MySQL has no partial indexes), so
        // the reservation is kept and pinned here rather than left as a
        // surprise.
        $user = User::factory()->create(['email' => 'reserved@example.com']);
        $user->delete();

        $this->post('/register', [
            'name' => 'Someone Else',
            'email' => 'reserved@example.com',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
        ])->assertSessionHasErrors('email');
    }

    public function test_deleted_users_are_not_reachable_from_the_list(): void
    {
        // The ?trashed=1 view and its restore route were removed. Typing the old
        // parameter should simply show the live list, not resurrect them.
        $victim = User::factory()->create(['name' => 'Gone Away']);
        $victim->delete();

        $this->actingAs($this->admin())->get('/admin/users?trashed=1')
            ->assertOk()
            ->assertDontSee('Gone Away');
    }

    public function test_the_restore_route_no_longer_exists(): void
    {
        $victim = User::factory()->create();
        $victim->delete();

        $this->actingAs($this->admin())
            ->put('/admin/users/'.$victim->id.'/restore')
            ->assertNotFound();
    }
}
