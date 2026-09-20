<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->applyMailSettings();
        $this->applyDomainSettings();
    }

    /**
     * Overrides the smtp mailer / from-address from the admin-managed
     * Setting row, when present. Guarded by Schema::hasTable() so a fresh
     * install (migrations not yet run) or a DB hiccup never takes mail down
     * — it just falls back to .env. Only non-null DB values override the
     * .env defaults, so an incomplete settings row doesn't blank the rest.
     *
     * Safe under `config:cache`: that command only merges config/*.php file
     * contents, it doesn't disable service providers or prevent a runtime
     * config() call here — and every provider boots before any
     * controller/job/notification code could send mail.
     *
     * Public (not private) so a test can invoke it directly, since a test
     * seeding the Setting row happens after the app — and this boot() call
     * with it — has already run once for that test.
     */
    public function applyMailSettings(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        $setting = Setting::query()->first();

        if (! $setting) {
            return;
        }

        if ($setting->outgoing_mail_server) {
            config(['mail.mailers.smtp.host' => $setting->outgoing_mail_server]);
        }

        if ($setting->smtp_port) {
            config(['mail.mailers.smtp.port' => $setting->smtp_port]);
        }

        if ($setting->reply_email) {
            config([
                'mail.mailers.smtp.username' => $setting->reply_email,
                'mail.from.address' => $setting->reply_email,
            ]);
        }

        if ($setting->reply_email_password) {
            config(['mail.mailers.smtp.password' => $setting->reply_email_password]);
        }
    }

    /**
     * Overrides route()/url()/asset() generation from the admin-managed
     * domain_name, when present. Never touches .env — this is a runtime-only
     * override, same guards as applyMailSettings().
     *
     * Plain config(['app.url' => ...]) isn't enough on its own:
     * Illuminate\Routing\UrlGenerator caches its resolved root URL in a
     * property the first time it's needed, so a later config() write in this
     * same request wouldn't be picked up. URL::forceRootUrl() is the
     * intended mechanism — it resets that cache too. forceRootUrl() alone
     * doesn't fix the scheme, though: UrlGenerator::formatRoot() resolves
     * http/https separately and substitutes it into whatever root string
     * exists — so forceScheme() is needed as well, or an https:// root gets
     * silently downgraded back to http.
     *
     * Public for the same reason as applyMailSettings(): tests invoke it
     * directly after seeding the Setting row.
     */
    public function applyDomainSettings(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        $setting = Setting::query()->first();

        if (! $setting || ! $setting->domain_name) {
            return;
        }

        $url = str_contains($setting->domain_name, '://')
            ? $setting->domain_name
            : 'https://'.$setting->domain_name;

        $scheme = str_starts_with($url, 'http://') ? 'http' : 'https';

        config(['app.url' => $url]);
        URL::forceRootUrl($url);
        URL::forceScheme($scheme);
    }
}
