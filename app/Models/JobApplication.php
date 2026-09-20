<?php

namespace App\Models;

use App\Enums\JobApplicationStatus;
use Database\Factories\JobApplicationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\RouteKey;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property JobApplicationStatus $status Larastan doesn't reliably resolve
 *                                        enum types declared only in casts() — see .ai/rules/app.md.
 */
#[Fillable([
    'applicant_name', 'gender_id', 'date_of_birth', 'race_id', 'religion_id',
    'siblings_count', 'mobile_number', 'education_level_id', 'education_detail',
    'ambition', 'marital_status_id', 'field_experience', 'previous_work_experience',
    'reason_left_previous_job', 'has_mental_illness', 'illness_details',
    'family_member_name', 'family_member_ic', 'family_member_occupation',
    'family_member_employer_address', 'family_member_phone', 'status',
    'reviewed_by', 'reviewed_at', 'created_user_id',
])]
#[RouteKey('ulid')]
class JobApplication extends Model implements HasMedia
{
    /** @use HasFactory<JobApplicationFactory> */
    use HasFactory, HasUlids, InteractsWithMedia, LogsActivity;

    /**
     * `academic_certificate` is the only collection that accepts multiple
     * files — an applicant may attach more than one certificate.
     */
    public function registerMediaCollections(): void
    {
        $mimes = ['application/pdf', 'image/jpeg', 'image/png'];

        $this->addMediaCollection('ic_copy')->singleFile()->acceptsMimeTypes($mimes);
        $this->addMediaCollection('academic_certificate')->acceptsMimeTypes($mimes);
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
     * @return BelongsTo<RefRace, $this>
     */
    public function race(): BelongsTo
    {
        return $this->belongsTo(RefRace::class);
    }

    /**
     * @return BelongsTo<RefReligion, $this>
     */
    public function religion(): BelongsTo
    {
        return $this->belongsTo(RefReligion::class);
    }

    /**
     * @return BelongsTo<RefEducationLevel, $this>
     */
    public function educationLevel(): BelongsTo
    {
        return $this->belongsTo(RefEducationLevel::class);
    }

    /**
     * @return BelongsTo<RefMaritalStatus, $this>
     */
    public function maritalStatus(): BelongsTo
    {
        return $this->belongsTo(RefMaritalStatus::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function createdUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_user_id');
    }

    /**
     * @return MorphOne<Address, $this>
     */
    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
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
            'has_mental_illness' => 'boolean',
            'status' => JobApplicationStatus::class,
            'reviewed_at' => 'datetime',
        ];
    }
}
