<?php

namespace App\Models;

use App\Enums\WebAppStatus;
use Database\Factories\SettingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * A singleton — exactly one row ever exists, fetched via current(). No ulid /
 * route-model binding: there's no per-record URL to bind, unlike every other
 * model in this app.
 *
 * @property WebAppStatus $web_app_status Larastan doesn't reliably resolve
 *                                        enum types declared only in casts() — see .ai/rules/app.md.
 */
#[Fillable([
    'web_app_status', 'domain_name', 'copyright_by', 'copyright_year',
    'enquiry_email', 'outgoing_mail_server', 'smtp_port', 'reply_email',
    'reply_email_password',
])]
class Setting extends Model implements HasMedia
{
    /** @use HasFactory<SettingFactory> */
    use HasFactory, InteractsWithMedia, LogsActivity;

    /**
     * The fallback create is wrapped in withoutEvents() so this incidental,
     * system-triggered bootstrap row never logs an activity entry — without
     * it, whichever user happens to load the first authenticated page after
     * a fresh install gets a spurious "Setting created" row attributed to
     * them in their own activity log (LogsActivity's default causer is
     * whoever is authenticated on the request that triggers the write).
     * DatabaseSeeder also seeds this row directly so the fallback is rarely
     * exercised at all outside a fresh, unseeded database.
     */
    public static function current(): self
    {
        return static::query()->first() ?? static::withoutEvents(
            fn () => static::create(['web_app_status' => WebAppStatus::Online]),
        );
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->singleFile()
            ->acceptsMimeTypes(['image/png', 'image/jpeg', 'image/svg+xml', 'image/webp']);

        $this->addMediaCollection('favicon')
            ->singleFile()
            ->acceptsMimeTypes(['image/png', 'image/jpeg', 'image/svg+xml', 'image/x-icon', 'image/vnd.microsoft.icon']);
    }

    /**
     * `reply_email_password` is deliberately excluded — it must never land in
     * the activity log diff.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'web_app_status', 'domain_name', 'copyright_by', 'copyright_year',
                'enquiry_email', 'outgoing_mail_server', 'smtp_port', 'reply_email',
            ])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'web_app_status' => WebAppStatus::class,
            'smtp_port' => 'integer',
            'reply_email_password' => 'encrypted',
        ];
    }
}
