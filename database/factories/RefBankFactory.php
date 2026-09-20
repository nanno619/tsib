<?php

namespace Database\Factories;

use App\Models\RefBank;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RefBank>
 */
class RefBankFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Maybank', 'CIMB Bank', 'Public Bank', 'RHB Bank', 'Bank Islam', 'Bank Rakyat']),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
