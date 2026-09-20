<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * HTML's own constraint validation runs in the browser, before the form is
 * submitted, so an empty `required` field never reaches Laravel — the browser
 * shows its own unstyled bubble instead of the app's inline error. Every form
 * carries `novalidate` so the server stays the single source of truth for
 * messages, matching the auth pages, which have done this from the start.
 *
 * No HTTP test can catch a regression here: they post directly and skip the
 * browser entirely, which is how a form without `novalidate` passed everything
 * else. Hence asserting on the rendered markup instead.
 */
class FormValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    /**
     * @return array<string, array{string}>
     */
    public static function formPages(): array
    {
        return [
            'create user' => ['admin.users.create'],
            'edit user' => ['admin.users.edit'],
            'profile' => ['settings.profile'],
            'password' => ['settings.password'],
        ];
    }

    #[DataProvider('formPages')]
    public function test_every_form_opts_out_of_native_validation(string $route): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $url = $route === 'admin.users.edit'
            ? route($route, User::factory()->create())
            : route($route);

        $response = $this->actingAs($admin)->get($url);
        $response->assertOk();

        $html = $response->getContent();
        $this->assertIsString($html);

        // The shared layout contributes a form of its own, so this is never
        // zero — a blank count would mean the page stopped rendering.
        $forms = substr_count($html, '<form');
        $this->assertGreaterThan(0, $forms, "No form rendered on {$url}.");

        // Counted rather than merely present: /settings/profile renders two
        // forms, so a contains-check would pass with one of them opted out.
        $this->assertSame(
            $forms,
            substr_count($html, 'novalidate'),
            "A form on {$url} will be validated by the browser before Laravel sees it.",
        );
    }
}
