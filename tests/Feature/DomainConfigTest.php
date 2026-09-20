<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Providers\AppServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * App\Providers\AppServiceProvider::applyDomainSettings() — called from
 * boot(), invoked directly here for the same reason as MailConfigTest: a
 * test seeding the Setting row happens after the app's one boot() call.
 */
class DomainConfigTest extends TestCase
{
    use RefreshDatabase;

    private function applyDomainSettings(): void
    {
        (new AppServiceProvider($this->app))->applyDomainSettings();
    }

    public function test_a_bare_domain_name_gets_an_https_scheme(): void
    {
        Setting::factory()->create(['domain_name' => 'tsib.test']);

        $this->applyDomainSettings();

        $this->assertSame('https://tsib.test', config('app.url'));
    }

    public function test_a_domain_name_with_an_explicit_scheme_is_used_as_is(): void
    {
        Setting::factory()->create(['domain_name' => 'http://tsib.test']);

        $this->applyDomainSettings();

        $this->assertSame('http://tsib.test', config('app.url'));
    }

    public function test_route_generation_reflects_the_overridden_domain(): void
    {
        // Proves URL::forceRootUrl() actually took effect, not just the
        // config value — UrlGenerator caches its root separately from config().
        Setting::factory()->create(['domain_name' => 'tsib.test']);

        $this->applyDomainSettings();

        $this->assertStringStartsWith('https://tsib.test', route('login'));
    }

    public function test_an_empty_settings_row_leaves_the_app_url_untouched(): void
    {
        $originalUrl = config('app.url');

        Setting::factory()->create(['domain_name' => null]);

        $this->applyDomainSettings();

        $this->assertSame($originalUrl, config('app.url'));
    }

    public function test_a_missing_settings_table_leaves_the_app_url_untouched(): void
    {
        $originalUrl = config('app.url');

        Schema::drop('settings');

        $this->applyDomainSettings();

        $this->assertSame($originalUrl, config('app.url'));
    }
}
