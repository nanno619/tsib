<?php

namespace Database\Factories;

use App\Models\RefMaritalStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RefMaritalStatus>
 */
class RefMaritalStatusFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Bujang', 'Berkahwin', 'Bercerai', 'Duda/Balu']),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
