<?php

namespace Database\Factories;

use App\Models\RefLeaveType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<RefLeaveType>
 */
class RefLeaveTypeFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->randomElement([
            'Cuti Tahunan', 'Cuti Kecemasan', 'Cuti Sakit',
            'Cuti Tanpa Gaji', 'Cuti Ganti', 'Cuti Lain-lain',
        ]);

        return [
            'name' => $name,
            // `slug` is unique in the database; a fixed six-name domain would collide
            // under Faker's own unique() tracker, so uniqueness comes from the suffix.
            'slug' => Str::slug($name).'-'.Str::lower(Str::random(6)),
        ];
    }
}
