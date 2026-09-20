<?php

namespace Database\Factories;

use App\Enums\GuardianType;
use App\Models\Child;
use App\Models\ChildGuardian;
use App\Models\RefCountry;
use App\Models\RefMaritalStatus;
use App\Models\RefRace;
use App\Models\RefReligion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ChildGuardian>
 */
class ChildGuardianFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'child_id' => Child::factory(),
            'type' => fake()->randomElement(GuardianType::cases()),
            'full_name' => mb_strtoupper(fake()->name()),
            'ic_number' => fake()->unique()->numerify('######-##-####'),
            'date_of_birth' => fake()->dateTimeBetween('-55 years', '-20 years')->format('Y-m-d'),
            'race_id' => RefRace::factory(),
            'religion_id' => RefReligion::factory(),
            'nationality_id' => RefCountry::factory(),
            'marital_status_id' => RefMaritalStatus::factory(),
            'home_phone' => fake()->optional()->numerify('0#-########'),
            'mobile_number' => fake()->numerify('01#-#######'),
            'office_number' => fake()->optional()->numerify('0#-########'),
            'email' => fake()->optional()->safeEmail(),
            'employer_position_address' => fake()->address(),
        ];
    }
}
