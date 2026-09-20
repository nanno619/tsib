<?php

namespace App\Models;

use App\Enums\LeaveApplicationStatus;
use Database\Factories\LeaveApplicationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\RouteKey;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[Fillable([
    'staff_id', 'ref_leave_type_id', 'other_type_detail', 'date_from', 'date_to',
    'duration_days', 'reason', 'status', 'return_reason', 'submitted_at',
    'reviewed_by', 'reviewed_at',
])]
#[RouteKey('ulid')]
class LeaveApplication extends Model implements HasMedia
{
    /** @use HasFactory<LeaveApplicationFactory> */
    use HasFactory, HasUlids, InteractsWithMedia, LogsActivity;

    /**
     * `supporting_document` — required to submit when the leave type is
     * `cuti-sakit` (MC); enforced by the Form Request, not here.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('supporting_document')
            ->singleFile()
            ->acceptsMimeTypes(['application/pdf', 'image/jpeg', 'image/png']);
    }

    /**
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ['ulid'];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    /**
     * @return BelongsTo<RefLeaveType, $this>
     */
    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(RefLeaveType::class, 'ref_leave_type_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
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
            'date_from' => 'date',
            'date_to' => 'date',
            'duration_days' => 'decimal:1',
            'status' => LeaveApplicationStatus::class,
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }
}
