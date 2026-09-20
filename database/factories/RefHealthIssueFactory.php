<?php

namespace Database\Factories;

use App\Models\RefHealthIssue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RefHealthIssue>
 */
class RefHealthIssueFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Cepat Penat', 'Sakit Dada', 'Selalu Pitam/Pening Kepala',
                'Kurang Penglihatan', 'Kurang Pendengaran', 'Alahan', 'Lain-lain',
            ]),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
