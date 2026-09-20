<?php

namespace Database\Factories;

use App\Models\RefRace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RefRace>
 */
class RefRaceFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Melayu', 'Cina', 'India', 'Lain-lain']),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
