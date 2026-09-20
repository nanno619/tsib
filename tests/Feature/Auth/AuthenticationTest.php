<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_login_page_renders(): void
    {
        $this->get('/login')->assertOk();
    }

    public function test_a_user_can_log_in_with_valid_credentials(): void
    {
        $user = User::factory()->create(['password' => 'secret-password']);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret-password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/dashboard');
    }

    public function test_a_user_cannot_log_in_with_an_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_a_user_can_log_out(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/logout');

        $this->assertGuest();
    }

    public function test_the_registration_page_renders(): void
    {
        $this->get('/register')->assertOk();
    }

    public function test_a_user_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'New Person',
            'email' => 'new@example.com',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/dashboard');

        $this->assertDatabaseHas('users', [
            'name' => 'New Person',
            'email' => 'new@example.com',
        ]);
    }

    public function test_registration_rejects_a_duplicate_email(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $this->post('/register', [
            'name' => 'New Person',
            'email' => 'taken@example.com',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_registration_rejects_a_mismatched_password_confirmation(): void
    {
        $this->post('/register', [
            'name' => 'New Person',
            'email' => 'new@example.com',
            'password' => 'secret-password',
            'password_confirmation' => 'different-password',
        ])->assertSessionHasErrors('password');

        $this->assertGuest();
    }

    public function test_a_password_reset_link_can_be_requested(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_a_password_can_be_reset_with_a_valid_token(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        // The DB stores a hash of the token, so the plain value has to come
        // from the notification the user would actually receive.
        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use ($user) {
            $this->post('/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'brand-new-password',
                'password_confirmation' => 'brand-new-password',
            ])->assertSessionHasNoErrors();

            return true;
        });

        $this->assertTrue(Hash::check('brand-new-password', $user->refresh()->password));
    }

    public function test_a_user_can_update_their_password(): void
    {
        $user = User::factory()->create(['password' => 'current-password']);

        $this->actingAs($user)
            ->from('/settings/password')
            ->put('/user/password', [
                'current_password' => 'current-password',
                'password' => 'brand-new-password',
                'password_confirmation' => 'brand-new-password',
            ])
            ->assertRedirect('/settings/password');

        $this->assertTrue(Hash::check('brand-new-password', $user->refresh()->password));
    }

    public function test_updating_the_password_requires_the_current_one(): void
    {
        $user = User::factory()->create(['password' => 'current-password']);

        $this->actingAs($user)->put('/user/password', [
            'current_password' => 'not-the-current-password',
            'password' => 'brand-new-password',
            'password_confirmation' => 'brand-new-password',
        ])->assertSessionHasErrors('current_password', errorBag: 'updatePassword');
    }

    public function test_a_user_can_update_their_profile_details(): void
    {
        $user = User::factory()->create(['name' => 'Old Name']);

        $this->actingAs($user)
            ->from('/settings/profile')
            ->put('/user/profile-information', [
                'name' => 'New Name',
                'email' => $user->email,
            ])
            ->assertRedirect('/settings/profile');

        $this->assertSame('New Name', $user->refresh()->name);
    }

    public function test_the_settings_pages_require_authentication(): void
    {
        foreach (['/settings/profile', '/settings/password', '/settings/activity', '/starter-kit'] as $path) {
            $this->get($path)->assertRedirect('/login');
        }
    }
}
