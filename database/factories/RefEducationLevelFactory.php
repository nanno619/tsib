<?php

namespace Database\Factories;

use App\Models\RefEducationLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RefEducationLevel>
 */
class RefEducationLevelFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'UPSR', 'PT3/PMR', 'SPM', 'STPM/STAM', 'Sijil', 'Diploma',
                'Ijazah Sarjana Muda', 'Ijazah Sarjana', 'Kedoktoran (PhD)', 'Lain-lain',
            ]),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
