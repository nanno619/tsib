<?php

namespace App\Models;

use App\Enums\GuardianType;
use Database\Factories\ChildGuardianFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\RouteKey;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property GuardianType $type Larastan doesn't reliably resolve enum types
 *                              declared only in casts() — see .ai/rules/app.md.
 */
#[Fillable([
    'child_id', 'type', 'full_name', 'ic_number', 'date_of_birth', 'race_id',
    'religion_id', 'nationality_id', 'marital_status_id', 'home_phone',
    'mobile_number', 'office_number', 'email', 'employer_position_address',
])]
#[RouteKey('ulid')]
class ChildGuardian extends Model
{
    /** @use HasFactory<ChildGuardianFactory> */
    use HasFactory, HasUlids;

    /**
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ['ulid'];
    }

    /**
     * @return BelongsTo<Child, $this>
     */
    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
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
     * @return BelongsTo<RefCountry, $this>
     */
    public function nationality(): BelongsTo
    {
        return $this->belongsTo(RefCountry::class, 'nationality_id');
    }

    /**
     * @return BelongsTo<RefMaritalStatus, $this>
     */
    public function maritalStatus(): BelongsTo
    {
        return $this->belongsTo(RefMaritalStatus::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => GuardianType::class,
            'date_of_birth' => 'date',
        ];
    }
}
