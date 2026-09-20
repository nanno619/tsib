<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * /starter-kit is the live index of every component, so this doubles as a
 * smoke test for the whole library: the page renders all of them at once.
 *
 * Delete this file along with the route and view if you follow the README's
 * "delete /starter-kit" step for a real project — see also the starter-kit
 * assertions in PermissionTest and PdfExportTest.
 */
class StarterKitPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/starter-kit')->assertRedirect('/login');
    }

    public function test_every_component_section_renders(): void
    {
        $response = $this->actingAs(User::factory()->create())->get('/starter-kit');

        $response->assertOk();

        foreach ([
            'Buttons', 'Badges', 'Dropdown', 'Modal', 'Segmented control',
            'Alerts', 'Breadcrumb', 'Avatars', 'List group', 'Form elements',
            'Roles & permissions', 'Table + Pagination', 'Progress',
            'Empty state', 'Tabs', 'Spinners & statuses', 'Toast',
            'Tooltip & popover', 'Placeholder', 'Ribbon', 'Carousel',
            'Datepicker', 'Inline datepicker', 'Advanced select', 'Calendar',
        ] as $section) {
            $response->assertSee($section);
        }
    }

    public function test_the_users_table_paginates(): void
    {
        // Zero-padded so "User 01" can't accidentally match "User 010".
        foreach (range(1, 12) as $i) {
            User::factory()->create(['name' => sprintf('User %03d', $i)]);
        }

        $viewer = User::factory()->create(['name' => 'Zzz Viewer']);

        $first = $this->actingAs($viewer)->get('/starter-kit');
        $first->assertOk();
        $first->assertSee('User 001');
        $first->assertSee('User 005');
        // Five per page — the sixth belongs to page two.
        $first->assertDontSee('User 006');

        $second = $this->actingAs($viewer)->get('/starter-kit?users_page=2');
        $second->assertOk();
        $second->assertSee('User 006');
        $second->assertDontSee('User 001');
    }

    public function test_the_table_footer_reports_the_current_slice(): void
    {
        foreach (range(1, 12) as $i) {
            User::factory()->create(['name' => sprintf('User %03d', $i)]);
        }

        User::factory()->create(['name' => 'Zzz Viewer']);

        // 13 users total, 5 per page.
        $this->actingAs(User::where('name', 'Zzz Viewer')->firstOrFail())
            ->get('/starter-kit')
            ->assertOk()
            ->assertSee('Showing')
            ->assertSee('13');
    }
}
