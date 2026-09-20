<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\RefState;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Address>
 */
class AddressFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'address_line_1' => fake()->streetAddress(),
            'address_line_2' => fake()->optional()->buildingNumber(),
            'address_line_3' => null,
            'state_id' => RefState::factory(),
            'district' => fake()->city(),
            'city' => fake()->city(),
            'postcode' => fake()->numerify('#####'),
        ];
    }
}
