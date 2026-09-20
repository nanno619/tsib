<?php

namespace Tests\Feature\Settings;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/settings/activity')->assertRedirect('/login');
    }

    public function test_the_page_shows_an_empty_state_with_no_activity(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/settings/activity');

        $response->assertOk();
        $response->assertSee('No activity yet');
    }

    public function test_updating_the_profile_records_the_change(): void
    {
        $user = User::factory()->create(['name' => 'Before Name']);

        $this->actingAs($user)->put('/user/profile-information', [
            'name' => 'After Name',
            'email' => $user->email,
        ]);

        $activity = Activity::where('causer_id', $user->id)->latest('id')->first();

        $this->assertNotNull($activity, 'Expected the profile update to be logged.');
        $this->assertSame('updated', $activity->description);

        $changes = $activity->attribute_changes;
        $this->assertNotNull($changes, 'Expected the change diff to be recorded.');
        $this->assertSame('After Name', $changes['attributes']['name']);
        $this->assertSame('Before Name', $changes['old']['name']);
    }

    public function test_the_page_renders_the_change_with_its_previous_value(): void
    {
        $user = User::factory()->create(['name' => 'Before Name']);

        $this->actingAs($user)->put('/user/profile-information', [
            'name' => 'After Name',
            'email' => $user->email,
        ]);

        $response = $this->actingAs($user)->get('/settings/activity');

        $response->assertOk();
        // The diff reads from `attribute_changes`; activitylog v5 dropped the
        // v4 `properties`/`changes()` API this page originally used.
        $response->assertSee('Before Name');
        $response->assertSee('After Name');
    }

    public function test_activity_is_attributed_to_the_acting_user(): void
    {
        $actor = User::factory()->create();
        $other = User::factory()->create(['name' => 'Other User']);

        $this->actingAs($other)->put('/user/profile-information', [
            'name' => 'Other Renamed',
            'email' => $other->email,
        ]);

        $this->actingAs($actor)->get('/settings/activity')
            ->assertDontSee('Other Renamed');
    }

    public function test_saving_without_changes_records_nothing(): void
    {
        $user = User::factory()->create(['name' => 'Unchanged']);

        $this->actingAs($user)->put('/user/profile-information', [
            'name' => 'Unchanged',
            'email' => $user->email,
        ]);

        // Scoped to 'updated': LogsActivity also records 'created', so the
        // factory's own insert would otherwise be counted here.
        $this->assertSame(0, Activity::where('description', 'updated')->count());
    }

    public function test_password_changes_are_not_logged(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->put('/user/password', [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $this->assertSame(0, Activity::where('description', 'updated')->count());
    }
}
