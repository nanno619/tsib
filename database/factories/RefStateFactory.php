<?php

namespace Database\Factories;

use App\Models\RefCountry;
use App\Models\RefState;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RefState>
 */
class RefStateFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'country_id' => RefCountry::factory(),
            'name' => fake()->randomElement([
                'Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan', 'Pahang',
                'Perak', 'Perlis', 'Pulau Pinang', 'Sabah', 'Sarawak', 'Selangor',
                'Terengganu', 'Wilayah Persekutuan Kuala Lumpur', 'Wilayah Persekutuan Labuan',
                'Wilayah Persekutuan Putrajaya',
            ]),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
