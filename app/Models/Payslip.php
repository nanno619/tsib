<?php

namespace App\Models;

use App\Enums\PayslipStatus;
use Database\Factories\PayslipFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\RouteKey;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property PayslipStatus $status Larastan doesn't reliably resolve enum
 *                                 types declared only in casts() — see .ai/rules/app.md.
 */
#[Fillable([
    'staff_id', 'salary_month', 'salary_date', 'start_date', 'end_date',
    'basic_salary', 'overtime', 'allowances', 'advance', 'epf_staff',
    'epf_employer', 'socso_staff', 'socso_employer', 'eis_staff', 'eis_employer',
    'status', 'return_reason', 'created_by', 'submitted_at', 'reviewed_by',
    'reviewed_at', 'notified_at',
])]
#[RouteKey('ulid')]
class Payslip extends Model implements HasMedia
{
    /** @use HasFactory<PayslipFactory> */
    use HasFactory, HasUlids, InteractsWithMedia, LogsActivity;

    /**
     * `pdf` is the mPDF-rendered payslip, attached once the payslip is
     * published — not user-uploaded.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('pdf')->singleFile()->acceptsMimeTypes(['application/pdf']);
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
     * `basic_salary + overtime + allowances − advance − epf_staff −
     * socso_staff − eis_staff`. Intentionally not a stored column — see
     * `05-backend-schema.md` (payslips). Employer-side statutory
     * contributions (`*_employer`) don't reduce the employee's net pay.
     *
     * @return Attribute<string, never>
     */
    protected function netPay(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                $additions = (string) $this->basic_salary;
                $additions = bcadd($additions, (string) ($this->overtime ?? 0), 2);
                $additions = bcadd($additions, (string) ($this->allowances ?? 0), 2);

                $deductions = (string) ($this->advance ?? 0);
                $deductions = bcadd($deductions, (string) $this->epf_staff, 2);
                $deductions = bcadd($deductions, (string) $this->socso_staff, 2);
                $deductions = bcadd($deductions, (string) $this->eis_staff, 2);

                return bcsub($additions, $deductions, 2);
            },
        );
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
            'salary_date' => 'date',
            'start_date' => 'date',
            'end_date' => 'date',
            'basic_salary' => 'decimal:2',
            'overtime' => 'decimal:2',
            'allowances' => 'decimal:2',
            'advance' => 'decimal:2',
            'epf_staff' => 'decimal:2',
            'epf_employer' => 'decimal:2',
            'socso_staff' => 'decimal:2',
            'socso_employer' => 'decimal:2',
            'eis_staff' => 'decimal:2',
            'eis_employer' => 'decimal:2',
            'status' => PayslipStatus::class,
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'notified_at' => 'datetime',
        ];
    }
}
