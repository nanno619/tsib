<?php

namespace Database\Factories;

use App\Models\Child;
use App\Models\ChildHealthIssue;
use App\Models\RefHealthIssue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ChildHealthIssue>
 */
class ChildHealthIssueFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'child_id' => Child::factory(),
            'ref_health_issue_id' => RefHealthIssue::factory(),
            'detail' => null,
        ];
    }
}
