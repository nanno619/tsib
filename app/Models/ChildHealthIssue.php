<?php

namespace App\Models;

use Database\Factories\ChildHealthIssueFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\RouteKey;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['child_id', 'ref_health_issue_id', 'detail'])]
#[RouteKey('ulid')]
class ChildHealthIssue extends Model
{
    /** @use HasFactory<ChildHealthIssueFactory> */
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
     * @return BelongsTo<RefHealthIssue, $this>
     */
    public function healthIssue(): BelongsTo
    {
        return $this->belongsTo(RefHealthIssue::class, 'ref_health_issue_id');
    }
}
