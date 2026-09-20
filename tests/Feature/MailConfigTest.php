<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Providers\AppServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * App\Providers\AppServiceProvider::applyMailSettings() — called from
 * boot(), invoked directly here since these tests seed the Setting row
 * after the app (and its one boot() call) already ran for this test.
 */
class MailConfigTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Service providers aren't container-resolvable by class name the way
     * ordinary classes are — the framework instantiates them itself with the
     * Application instance, so tests do the same.
     */
    private function applyMailSettings(): void
    {
        (new AppServiceProvider($this->app))->applyMailSettings();
    }

    public function test_smtp_settings_override_the_mail_config(): void
    {
        Setting::factory()->create([
            'outgoing_mail_server' => 'smtp.tsib.test',
            'smtp_port' => 2525,
            'reply_email' => 'noreply@tsib.test',
            'reply_email_password' => 'super-secret',
        ]);

        $this->applyMailSettings();

        $this->assertSame('smtp.tsib.test', config('mail.mailers.smtp.host'));
        $this->assertSame(2525, config('mail.mailers.smtp.port'));
        $this->assertSame('noreply@tsib.test', config('mail.mailers.smtp.username'));
        $this->assertSame('noreply@tsib.test', config('mail.from.address'));
        $this->assertSame('super-secret', config('mail.mailers.smtp.password'));
    }

    public function test_an_empty_settings_row_leaves_the_mail_config_untouched(): void
    {
        $originalHost = config('mail.mailers.smtp.host');

        Setting::factory()->create([
            'outgoing_mail_server' => null,
            'smtp_port' => null,
            'reply_email' => null,
            'reply_email_password' => null,
        ]);

        $this->applyMailSettings();

        $this->assertSame($originalHost, config('mail.mailers.smtp.host'));
    }

    public function test_a_missing_settings_table_leaves_the_mail_config_untouched(): void
    {
        $originalHost = config('mail.mailers.smtp.host');

        Schema::drop('settings');

        $this->applyMailSettings();

        $this->assertSame($originalHost, config('mail.mailers.smtp.host'));
    }

    public function test_a_missing_settings_row_leaves_the_mail_config_untouched(): void
    {
        $originalHost = config('mail.mailers.smtp.host');

        $this->applyMailSettings();

        $this->assertSame($originalHost, config('mail.mailers.smtp.host'));
    }
}
