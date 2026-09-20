<?php

namespace Database\Factories;

use App\Models\RefIllness;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RefIllness>
 */
class RefIllnessFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Lelah', 'Batuk Kering', 'Sakit Jantung', 'Gastrik', 'Barah', 'Sawan', 'Lain-lain']),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
