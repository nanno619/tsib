<?php

namespace Database\Factories;

use App\Models\Child;
use App\Models\ChildIllness;
use App\Models\RefIllness;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ChildIllness>
 */
class ChildIllnessFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'child_id' => Child::factory(),
            'ref_illness_id' => RefIllness::factory(),
            'detail' => null,
        ];
    }
}
