<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * The admin-triggered "send a password reset link" action on a user's detail
 * page — reuses Fortify's own reset-link broker/notification rather than a
 * separate admin-sets-a-new-password flow.
 */
class UserPasswordResetTest extends TestCase
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
        $target = User::factory()->create();

        $this->post(route('admin.users.reset-password', $target))->assertRedirect('/login');
    }

    public function test_an_admin_can_trigger_a_password_reset(): void
    {
        Notification::fake();

        $admin = $this->userWithRole('admin');
        $target = User::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.users.reset-password', $target))
            ->assertRedirect()
            ->assertSessionHas('status', 'user-password-reset-sent');

        Notification::assertSentTo($target, ResetPassword::class);
    }

    public function test_a_plain_user_cannot_trigger_a_password_reset(): void
    {
        Notification::fake();

        $plain = User::factory()->create();
        $target = User::factory()->create();

        $this->actingAs($plain)
            ->post(route('admin.users.reset-password', $target))
            ->assertForbidden();

        Notification::assertNothingSent();
    }
}
