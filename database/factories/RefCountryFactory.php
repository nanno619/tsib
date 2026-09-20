<?php

namespace Database\Factories;

use App\Models\RefCountry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RefCountry>
 */
class RefCountryFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->country(),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
