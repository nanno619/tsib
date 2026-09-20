<?php

namespace Database\Factories;

use App\Models\LeaveBalance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LeaveBalance>
 */
class LeaveBalanceFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'staff_id' => User::factory(),
            'balance_days' => 8,
        ];
    }
}
