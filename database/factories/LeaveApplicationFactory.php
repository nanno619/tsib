<?php

namespace Database\Factories;

use App\Enums\LeaveApplicationStatus;
use App\Models\LeaveApplication;
use App\Models\RefLeaveType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LeaveApplication>
 */
class LeaveApplicationFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dateFrom = fake()->dateTimeBetween('+1 day', '+2 months');

        return [
            'staff_id' => User::factory(),
            'ref_leave_type_id' => RefLeaveType::factory(),
            'other_type_detail' => null,
            'date_from' => $dateFrom->format('Y-m-d'),
            'date_to' => $dateFrom->format('Y-m-d'),
            'duration_days' => 1,
            'reason' => fake()->sentence(),
            'status' => LeaveApplicationStatus::Draft,
            'return_reason' => null,
            'submitted_at' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
        ];
    }

    public function submitted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => LeaveApplicationStatus::Submitted,
            'submitted_at' => now(),
        ]);
    }
}
