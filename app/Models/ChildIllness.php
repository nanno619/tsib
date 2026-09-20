<?php

namespace App\Models;

use Database\Factories\ChildIllnessFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\RouteKey;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['child_id', 'ref_illness_id', 'detail'])]
#[RouteKey('ulid')]
class ChildIllness extends Model
{
    /** @use HasFactory<ChildIllnessFactory> */
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
     * @return BelongsTo<RefIllness, $this>
     */
    public function illness(): BelongsTo
    {
        return $this->belongsTo(RefIllness::class, 'ref_illness_id');
    }
}
