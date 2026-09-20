<?php

namespace Tests\Feature\Admin;

use App\Models\Setting;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SystemSettingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        Storage::fake('public');
    }

    private function admin(): User
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        return $user;
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/admin/system-setting')->assertRedirect('/login');
    }

    public function test_an_admin_can_view_the_settings_page(): void
    {
        $response = $this->actingAs($this->admin())->get('/admin/system-setting');

        $response->assertOk();
        $response->assertSee('General');
        $response->assertSee('Email');
    }

    public function test_a_user_without_permission_is_forbidden(): void
    {
        $plain = User::factory()->create();

        $this->actingAs($plain)->get('/admin/system-setting')->assertForbidden();
    }

    public function test_an_admin_can_update_general_settings(): void
    {
        $this->actingAs($this->admin())
            ->put(route('admin.system-setting.update-general'), [
                'web_app_status' => 'under_maintenance',
                'domain_name' => 'tsib.test',
                'copyright_by' => 'TSIB',
                'copyright_year' => '2026',
            ])
            ->assertRedirect()
            ->assertSessionHas('status', 'setting-general-updated');

        $setting = Setting::current();
        $this->assertSame('under_maintenance', $setting->web_app_status->value);
        $this->assertSame('tsib.test', $setting->domain_name);
        $this->assertSame('TSIB', $setting->copyright_by);
        $this->assertSame('2026', $setting->copyright_year);
    }

    public function test_an_admin_can_upload_a_logo_and_favicon(): void
    {
        $this->actingAs($this->admin())
            ->put(route('admin.system-setting.update-general'), [
                'web_app_status' => 'online',
                'logo' => UploadedFile::fake()->image('logo.png'),
                'favicon' => UploadedFile::fake()->image('favicon.png'),
            ])
            ->assertRedirect();

        $setting = Setting::current();
        $this->assertSame(1, $setting->getMedia('logo')->count());
        $this->assertSame(1, $setting->getMedia('favicon')->count());
    }

    public function test_an_admin_can_update_email_settings(): void
    {
        $this->actingAs($this->admin())
            ->put(route('admin.system-setting.update-email'), [
                'enquiry_email' => 'enquiry@tsib.test',
                'outgoing_mail_server' => 'smtp.tsib.test',
                'smtp_port' => '587',
                'reply_email' => 'noreply@tsib.test',
                'reply_email_password' => 'super-secret',
            ])
            ->assertRedirect()
            ->assertSessionHas('status', 'setting-email-updated');

        $setting = Setting::current();
        $this->assertSame('enquiry@tsib.test', $setting->enquiry_email);
        $this->assertSame('smtp.tsib.test', $setting->outgoing_mail_server);
        $this->assertSame(587, $setting->smtp_port);
        $this->assertSame('noreply@tsib.test', $setting->reply_email);
        $this->assertSame('super-secret', $setting->reply_email_password);
    }

    public function test_leaving_the_password_blank_keeps_the_existing_value(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->put(route('admin.system-setting.update-email'), [
            'reply_email_password' => 'original-secret',
        ]);

        $this->actingAs($admin)->put(route('admin.system-setting.update-email'), [
            'reply_email' => 'changed@tsib.test',
            'reply_email_password' => '',
        ]);

        $setting = Setting::current();
        $this->assertSame('changed@tsib.test', $setting->reply_email);
        $this->assertSame('original-secret', $setting->reply_email_password);
    }

    public function test_the_password_is_encrypted_at_rest(): void
    {
        $this->actingAs($this->admin())->put(route('admin.system-setting.update-email'), [
            'reply_email_password' => 'plain-text-secret',
        ]);

        $raw = DB::table('settings')->value('reply_email_password');

        $this->assertNotNull($raw);
        $this->assertStringNotContainsString('plain-text-secret', $raw);
    }

    public function test_the_navigation_link_follows_the_policy(): void
    {
        $admin = $this->admin();
        $plain = User::factory()->create();
        $url = route('admin.system-setting.edit');

        $adminHtml = $this->actingAs($admin)->get('/dashboard')->getContent();
        $plainHtml = $this->actingAs($plain)->get('/dashboard')->getContent();

        $this->assertIsString($adminHtml);
        $this->assertIsString($plainHtml);

        $this->assertStringContainsString($url, $adminHtml);
        $this->assertStringNotContainsString($url, $plainHtml);
        $this->assertStringContainsString('System Setting', $adminHtml);
    }
}
