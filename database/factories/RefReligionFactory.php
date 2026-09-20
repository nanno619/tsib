<?php

namespace Database\Factories;

use App\Models\RefReligion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RefReligion>
 */
class RefReligionFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Islam', 'Buddha', 'Kristian', 'Hindu', 'Lain-lain']),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
