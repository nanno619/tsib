<?php

namespace Database\Factories;

use App\Enums\ChildStatus;
use App\Models\Child;
use App\Models\RefCountry;
use App\Models\RefGender;
use App\Models\RefRace;
use App\Models\RefReligion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Child>
 */
class ChildFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => mb_strtoupper(fake()->name()),
            'ic_number' => null,
            'birth_certificate_number' => fake()->unique()->numerify('##############'),
            'date_of_birth' => fake()->dateTimeBetween('-6 years', '-1 year')->format('Y-m-d'),
            'gender_id' => RefGender::factory(),
            'religion_id' => RefReligion::factory(),
            'race_id' => RefRace::factory(),
            'nationality_id' => RefCountry::factory(),
            'department_id' => null,
            'responsible_teacher_id' => null,
            'has_disability' => false,
            'disability_details' => null,
            'status' => ChildStatus::Draft,
            'return_reason' => null,
            'created_by' => User::factory(),
            'reviewed_by' => null,
            'reviewed_at' => null,
            'parent_confirmed_at' => null,
        ];
    }

    public function submitted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ChildStatus::Submitted,
            'parent_confirmed_at' => now(),
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ChildStatus::Approved,
            'parent_confirmed_at' => now(),
            'reviewed_by' => User::factory(),
            'reviewed_at' => now(),
        ]);
    }
}
