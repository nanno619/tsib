<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Error pages are easy to get wrong silently: a Blade syntax error or a broken
 * include in one of them only surfaces when that specific error happens, which
 * in production is the worst moment to find out. These render each view
 * directly so a mistake fails the build instead.
 */
class ErrorPagesTest extends TestCase
{
    /**
     * @return array<string, array{view-string, string}>
     */
    public static function errorPages(): array
    {
        return [
            'forbidden' => ['errors.403', 'Access denied'],
            'not found' => ['errors.404', 'Page not found'],
            'page expired' => ['errors.419', 'Page expired'],
            'too many requests' => ['errors.429', 'Too many requests'],
            'server error' => ['errors.500', 'Something went wrong'],
            'maintenance' => ['errors.503', 'Temporarily down for maintenance'],
            '4xx fallback' => ['errors.4xx', 'That request could not be processed'],
            '5xx fallback' => ['errors.5xx', 'Something went wrong'],
        ];
    }

    /**
     * @param  view-string  $view
     */
    #[DataProvider('errorPages')]
    public function test_each_error_view_renders_a_styled_page(string $view, string $heading): void
    {
        $this->view($view)
            ->assertSee('empty', escape: false)
            // A real heading, not the <p> a nested empty state uses.
            ->assertSee('<h1 class="empty-title">', escape: false)
            ->assertSee($heading)
            ->assertSee('Take me home');
    }

    /**
     * @param  view-string  $view
     */
    #[DataProvider('errorPages')]
    public function test_each_error_view_ships_its_illustration(string $view, string $heading): void
    {
        $this->view($view)
            ->assertSee('empty-img', escape: false)
            ->assertSee('tblr-illustrations', escape: false);
    }

    /**
     * @param  view-string  $view
     */
    #[DataProvider('errorPages')]
    public function test_illustrations_follow_the_theme_attribute_not_the_os(string $view, string $heading): void
    {
        // The app's theming is attribute-only. Tabler's artwork also ships a
        // prefers-color-scheme media query, which would fight a manual
        // light/dark choice — it's stripped on extraction, so guard against
        // it coming back with a future copy-paste.
        $this->view($view)
            ->assertSee("[data-bs-theme='dark']", escape: false)
            ->assertDontSee('prefers-color-scheme', escape: false);
    }

    public function test_a_missing_page_returns_the_styled_404(): void
    {
        $response = $this->get('/no-such-page-exists');

        $response->assertNotFound();
        $response->assertSee('Page not found');
        $response->assertSee('<h1 class="empty-title">', escape: false);
        $response->assertSee('tblr-illustrations', escape: false);
    }

    public function test_error_pages_render_for_guests(): void
    {
        // A 404 can happen before login — the layout must not need auth.
        $this->assertGuest();

        $this->get('/no-such-page-exists')->assertNotFound();
    }

    public function test_error_pages_do_not_leak_internal_details(): void
    {
        // Nothing here should expose paths, stack traces or exception text.
        $html = $this->get('/no-such-page-exists')->getContent();

        $this->assertIsString($html);
        $this->assertStringNotContainsString('/Users/', $html);
        $this->assertStringNotContainsString('vendor/laravel', $html);
        $this->assertStringNotContainsString('No query results', $html);
    }
}
