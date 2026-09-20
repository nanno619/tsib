<?php

namespace Database\Factories;

use App\Models\RefGender;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RefGender>
 */
class RefGenderFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Lelaki', 'Perempuan']),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
