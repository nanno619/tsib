<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    private function admin(): User
    {
        $user = User::factory()->create(['name' => 'Zzz Admin']);
        $user->assignRole('admin');

        return $user;
    }

    public function test_the_filter_panel_renders(): void
    {
        $response = $this->actingAs($this->admin())->get('/admin/users');

        $response->assertOk();
        $response->assertSee('id="users-filter"', escape: false);
        $response->assertSee('data-bs-target="#users-filter"', escape: false);
        $response->assertSee('id="users-filter-form"', escape: false);
    }

    public function test_it_filters_by_name(): void
    {
        $this->admin();
        User::factory()->create(['name' => 'Ada Lovelace']);
        User::factory()->create(['name' => 'Grace Hopper']);

        $response = $this->actingAs($this->admin())->get('/admin/users?name=Ada');

        $response->assertOk();
        $response->assertSee('Ada Lovelace');
        $response->assertDontSee('Grace Hopper');
    }

    public function test_the_name_filter_matches_a_substring(): void
    {
        User::factory()->create(['name' => 'Ada Lovelace']);

        $this->actingAs($this->admin())->get('/admin/users?name=love')
            ->assertOk()
            ->assertSee('Ada Lovelace');
    }

    public function test_like_wildcards_in_the_name_are_escaped(): void
    {
        User::factory()->create(['name' => 'Literal 50% Person']);
        User::factory()->create(['name' => 'Someone Else']);

        // Unescaped, "%" would be a wildcard and match every row.
        $this->actingAs($this->admin())->get('/admin/users?name='.urlencode('50%'))
            ->assertOk()
            ->assertSee('Literal 50% Person')
            ->assertDontSee('Someone Else');

        // Same for "_", which would otherwise match any single character.
        $this->actingAs($this->admin())->get('/admin/users?name='.urlencode('S_meone Else'))
            ->assertOk()
            ->assertDontSee('Someone Else');
    }

    public function test_it_filters_by_role(): void
    {
        $editor = User::factory()->create(['name' => 'Ed Editor']);
        $editor->assignRole('editor');
        User::factory()->create(['name' => 'Norm Nobody']);

        $this->actingAs($this->admin())->get('/admin/users?roles[]=editor')
            ->assertOk()
            ->assertSee('Ed Editor')
            ->assertDontSee('Norm Nobody');
    }

    public function test_several_roles_match_any_of_them(): void
    {
        $editor = User::factory()->create(['name' => 'Ed Editor']);
        $editor->assignRole('editor');
        $other = User::factory()->create(['name' => 'Addy Admin']);
        $other->assignRole('admin');
        User::factory()->create(['name' => 'Norm Nobody']);

        $this->actingAs($this->admin())
            ->get('/admin/users?roles[]=editor&roles[]=admin')
            ->assertOk()
            ->assertSee('Ed Editor')
            ->assertSee('Addy Admin')
            ->assertDontSee('Norm Nobody');
    }

    public function test_no_matches_shows_an_empty_state_with_a_way_out(): void
    {
        $this->admin();

        $this->actingAs($this->admin())->get('/admin/users?name=nobody-matches-this')
            ->assertOk()
            ->assertSee('Tiada pengguna sepadan')
            ->assertSee('Set semula penapis');
    }

    public function test_it_sorts(): void
    {
        $this->admin();
        User::factory()->create(['name' => 'Alpha Person']);
        User::factory()->create(['name' => 'Zulu Person']);

        $ascending = $this->actingAs($this->admin())->get('/admin/users?sort=name')->getContent();
        $descending = $this->actingAs($this->admin())->get('/admin/users?sort=name_desc')->getContent();

        $this->assertIsString($ascending);
        $this->assertIsString($descending);

        $this->assertLessThan(
            strpos($ascending, 'Zulu Person'),
            strpos($ascending, 'Alpha Person'),
            'Name ascending should put Alpha first.',
        );
        $this->assertLessThan(
            strpos($descending, 'Alpha Person'),
            strpos($descending, 'Zulu Person'),
            'Name descending should put Zulu first.',
        );
    }

    public function test_an_unknown_sort_falls_back_instead_of_reaching_the_query(): void
    {
        // The column comes from the query string, so it's allow-listed.
        $this->actingAs($this->admin())
            ->get('/admin/users?sort=password')
            ->assertOk()
            ->assertSee('Semua pengguna');
    }

    public function test_the_filter_count_is_announced_on_the_button(): void
    {
        $this->admin();

        $plain = $this->actingAs($this->admin())->get('/admin/users')->getContent();
        $filtered = $this->actingAs($this->admin())->get('/admin/users?name=Ada&roles[]=admin')->getContent();

        $this->assertIsString($plain);
        $this->assertIsString($filtered);

        $this->assertStringContainsString('aria-label="Tapis pengguna"', $plain);
        $this->assertStringContainsString('aria-label="Tapis pengguna (2 aktif)"', $filtered);
    }
}
