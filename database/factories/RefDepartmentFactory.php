<?php

namespace Database\Factories;

use App\Models\RefDepartment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RefDepartment>
 */
class RefDepartmentFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Babyschool', 'Playschool', 'Kindergarten']),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
