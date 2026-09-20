<?php

namespace App\Models;

use App\Enums\ChildStatus;
use Database\Factories\ChildFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\RouteKey;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property ChildStatus $status Larastan doesn't reliably resolve enum types
 *                               declared only in casts() — see .ai/rules/app.md.
 */
#[Fillable([
    'full_name', 'ic_number', 'birth_certificate_number', 'date_of_birth',
    'gender_id', 'religion_id', 'race_id', 'nationality_id', 'department_id',
    'responsible_teacher_id', 'has_disability', 'disability_details', 'status',
    'return_reason', 'created_by', 'reviewed_by', 'reviewed_at', 'parent_confirmed_at',
])]
#[RouteKey('ulid')]
class Child extends Model implements HasMedia
{
    /** @use HasFactory<ChildFactory> */
    use HasFactory, HasUlids, InteractsWithMedia, LogsActivity, SoftDeletes;

    /**
     * Attachments named per Form 1 (Pendaftaran Kanak-kanak) — "Lampiran".
     * `parents_payslip` is the only collection that accepts multiple files.
     */
    public function registerMediaCollections(): void
    {
        $mimes = ['application/pdf', 'image/jpeg', 'image/png'];

        $this->addMediaCollection('birth_certificate')->singleFile()->acceptsMimeTypes($mimes);
        $this->addMediaCollection('father_ic')->singleFile()->acceptsMimeTypes($mimes);
        $this->addMediaCollection('mother_ic')->singleFile()->acceptsMimeTypes($mimes);
        $this->addMediaCollection('health_booklet')->singleFile()->acceptsMimeTypes($mimes);
        $this->addMediaCollection('parents_payslip')->acceptsMimeTypes($mimes);
        $this->addMediaCollection('covid_vaccine_parents')->singleFile()->acceptsMimeTypes($mimes);
        $this->addMediaCollection('covid_vaccine_child')->singleFile()->acceptsMimeTypes($mimes);
    }

    /**
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ['ulid'];
    }

    /**
     * @return BelongsTo<RefGender, $this>
     */
    public function gender(): BelongsTo
    {
        return $this->belongsTo(RefGender::class);
    }

    /**
     * @return BelongsTo<RefReligion, $this>
     */
    public function religion(): BelongsTo
    {
        return $this->belongsTo(RefReligion::class);
    }

    /**
     * @return BelongsTo<RefRace, $this>
     */
    public function race(): BelongsTo
    {
        return $this->belongsTo(RefRace::class);
    }

    /**
     * @return BelongsTo<RefCountry, $this>
     */
    public function nationality(): BelongsTo
    {
        return $this->belongsTo(RefCountry::class, 'nationality_id');
    }

    /**
     * @return BelongsTo<RefDepartment, $this>
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(RefDepartment::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function responsibleTeacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_teacher_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * @return HasMany<ChildGuardian, $this>
     */
    public function guardians(): HasMany
    {
        return $this->hasMany(ChildGuardian::class);
    }

    /**
     * @return HasMany<ChildIllness, $this>
     */
    public function illnesses(): HasMany
    {
        return $this->hasMany(ChildIllness::class);
    }

    /**
     * @return HasMany<ChildHealthIssue, $this>
     */
    public function healthIssues(): HasMany
    {
        return $this->hasMany(ChildHealthIssue::class);
    }

    /**
     * @return MorphOne<Address, $this>
     */
    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    /**
     * @param  Builder<static>  $query
     */
    #[Scope]
    protected function withStatus(Builder $query, ChildStatus $status): void
    {
        $query->where('status', $status);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnlyDirty()->dontLogEmptyChanges();
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'has_disability' => 'boolean',
            'status' => ChildStatus::class,
            'reviewed_at' => 'datetime',
            'parent_confirmed_at' => 'datetime',
        ];
    }
}
