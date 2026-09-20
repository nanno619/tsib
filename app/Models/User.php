<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\RouteKey;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'short_name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
#[RouteKey('ulid')]
class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, HasUlids, InteractsWithMedia, LogsActivity, Notifiable, SoftDeletes;

    /**
     * The `id` column stays the primary key (and what other tables' foreign
     * keys reference) — `ulid` is only for route-model binding.
     *
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ['ulid'];
    }

    /**
     * @return HasOne<StaffProfile, $this>
     */
    public function staffProfile(): HasOne
    {
        return $this->hasOne(StaffProfile::class);
    }

    /**
     * @return HasOne<LeaveBalance, $this>
     */
    public function leaveBalance(): HasOne
    {
        return $this->hasOne(LeaveBalance::class, 'staff_id');
    }

    /**
     * @return HasMany<LeaveApplication, $this>
     */
    public function leaveApplications(): HasMany
    {
        return $this->hasMany(LeaveApplication::class, 'staff_id');
    }

    /**
     * @return HasMany<Child, $this>
     */
    public function childrenRegistered(): HasMany
    {
        return $this->hasMany(Child::class, 'created_by');
    }

    /**
     * @return HasMany<Child, $this>
     */
    public function childrenResponsibleFor(): HasMany
    {
        return $this->hasMany(Child::class, 'responsible_teacher_id');
    }

    /**
     * @return HasMany<Payslip, $this>
     */
    public function payslips(): HasMany
    {
        return $this->hasMany(Payslip::class, 'staff_id');
    }

    /**
     * @return HasOne<JobApplication, $this>
     */
    public function jobApplicationHired(): HasOne
    {
        return $this->hasOne(JobApplication::class, 'created_user_id');
    }

    /**
     * Only the avatar collection is queryable per user, so a single-file
     * collection needs no explicit conversions — the raw upload is served
     * as-is.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')->singleFile();
    }

    /**
     * Only name/email changes are worth an audit trail — password hashes and
     * Fortify's internal fields would otherwise show up as noisy, unreadable
     * diffs.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
