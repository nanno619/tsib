<?php

namespace Database\Factories;

use App\Enums\PayslipStatus;
use App\Models\Payslip;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payslip>
 */
class PayslipFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $month = fake()->dateTimeBetween('-6 months', 'now');
        $basicSalary = fake()->randomFloat(2, 1800, 6000);

        return [
            'staff_id' => User::factory(),
            'salary_month' => $month->format('Y-m'),
            'salary_date' => $month->format('Y-m-d'),
            'start_date' => $month->modify('first day of this month')->format('Y-m-d'),
            'end_date' => $month->modify('last day of this month')->format('Y-m-d'),
            'basic_salary' => $basicSalary,
            'overtime' => null,
            'allowances' => null,
            'advance' => null,
            'epf_staff' => round($basicSalary * 0.11, 2),
            'epf_employer' => round($basicSalary * 0.13, 2),
            'socso_staff' => round($basicSalary * 0.005, 2),
            'socso_employer' => round($basicSalary * 0.0175, 2),
            'eis_staff' => round($basicSalary * 0.002, 2),
            'eis_employer' => round($basicSalary * 0.002, 2),
            'status' => PayslipStatus::Draft,
            'return_reason' => null,
            'created_by' => User::factory(),
            'submitted_at' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
            'notified_at' => null,
        ];
    }
}
