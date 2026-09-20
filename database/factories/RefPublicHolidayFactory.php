<?php

namespace Database\Factories;

use App\Models\RefPublicHoliday;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RefPublicHoliday>
 */
class RefPublicHolidayFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->sentence(3),
            'holiday_date' => fake()->unique()->dateTimeBetween('-1 year', '+1 year')->format('Y-m-d'),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
