<?php

namespace Database\Factories;

use App\Enums\JobApplicationStatus;
use App\Models\JobApplication;
use App\Models\RefEducationLevel;
use App\Models\RefGender;
use App\Models\RefMaritalStatus;
use App\Models\RefRace;
use App\Models\RefReligion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobApplication>
 */
class JobApplicationFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'applicant_name' => mb_strtoupper(fake()->name()),
            'gender_id' => RefGender::factory(),
            'date_of_birth' => fake()->dateTimeBetween('-45 years', '-20 years')->format('Y-m-d'),
            'race_id' => RefRace::factory(),
            'religion_id' => RefReligion::factory(),
            'siblings_count' => fake()->numberBetween(0, 10),
            'mobile_number' => fake()->numerify('01#-#######'),
            'education_level_id' => RefEducationLevel::factory(),
            'education_detail' => fake()->words(3, true),
            'ambition' => fake()->jobTitle(),
            'marital_status_id' => RefMaritalStatus::factory(),
            'field_experience' => fake()->paragraph(),
            'previous_work_experience' => fake()->paragraph(),
            'reason_left_previous_job' => fake()->sentence(),
            'has_mental_illness' => false,
            'illness_details' => null,
            'family_member_name' => mb_strtoupper(fake()->name()),
            'family_member_ic' => fake()->numerify('######-##-####'),
            'family_member_occupation' => fake()->jobTitle(),
            'family_member_employer_address' => fake()->address(),
            'family_member_phone' => fake()->numerify('01#-#######'),
            'status' => JobApplicationStatus::Pending,
            'reviewed_by' => null,
            'reviewed_at' => null,
            'created_user_id' => null,
        ];
    }
}
