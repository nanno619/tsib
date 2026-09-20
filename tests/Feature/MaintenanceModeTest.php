<?php

namespace Tests\Feature;

use App\Enums\WebAppStatus;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * App\Http\Middleware\CheckMaintenanceMode sits on the shared `auth` route
 * group in routes/web.php — these tests exist specifically to catch a
 * mistake there affecting every other authenticated route in the app.
 */
class MaintenanceModeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
    }

    public function test_a_non_admin_is_blocked_while_under_maintenance(): void
    {
        Setting::factory()->create(['web_app_status' => WebAppStatus::UnderMaintenance]);

        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $this->actingAs($teacher)->get('/dashboard')->assertStatus(503);
    }

    public function test_an_admin_is_unaffected_and_can_still_reach_settings(): void
    {
        Setting::factory()->create(['web_app_status' => WebAppStatus::UnderMaintenance]);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin)->get('/dashboard')->assertOk();
        $this->actingAs($admin)->get('/admin/system-setting')->assertOk();
    }

    public function test_a_guest_still_reaches_login_normally(): void
    {
        Setting::factory()->create(['web_app_status' => WebAppStatus::UnderMaintenance]);

        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_nobody_is_blocked_when_the_app_is_online(): void
    {
        Setting::factory()->create(['web_app_status' => WebAppStatus::Online]);

        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $this->actingAs($teacher)->get('/dashboard')->assertOk();
    }

    public function test_a_plain_user_with_no_role_is_also_blocked(): void
    {
        Setting::factory()->create(['web_app_status' => WebAppStatus::UnderMaintenance]);

        $plain = User::factory()->create();

        $this->actingAs($plain)->get('/dashboard')->assertStatus(503);
    }
}
