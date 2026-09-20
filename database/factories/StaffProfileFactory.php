<?php

namespace Database\Factories;

use App\Models\RefDepartment;
use App\Models\RefEducationLevel;
use App\Models\RefGender;
use App\Models\RefMaritalStatus;
use App\Models\RefRace;
use App\Models\RefReligion;
use App\Models\StaffProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StaffProfile>
 */
class StaffProfileFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'staff_number' => fake()->unique()->numerify('STF-######'),
            'full_name' => mb_strtoupper(fake()->name()),
            'ic_number' => fake()->unique()->numerify('######-##-####'),
            'date_of_birth' => fake()->dateTimeBetween('-55 years', '-20 years')->format('Y-m-d'),
            'gender_id' => RefGender::factory(),
            'race_id' => RefRace::factory(),
            'religion_id' => RefReligion::factory(),
            'marital_status_id' => RefMaritalStatus::factory(),
            'mobile_number' => fake()->numerify('01#-#######'),
            'siblings_count' => fake()->numberBetween(0, 10),
            'education_level_id' => RefEducationLevel::factory(),
            'education_detail' => fake()->words(3, true),
            'ambition' => fake()->jobTitle(),
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
            'epf_number' => fake()->numerify('KWSP-#######'),
            'department_id' => RefDepartment::factory(),
            'bank_id' => null,
            'bank_account_number' => null,
        ];
    }
}
