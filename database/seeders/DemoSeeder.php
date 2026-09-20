<?php

namespace Database\Seeders;

use App\Enums\JobApplicationStatus;
use App\Enums\LeaveApplicationStatus;
use App\Models\Child;
use App\Models\ChildGuardian;
use App\Models\JobApplication;
use App\Models\LeaveApplication;
use App\Models\LeaveBalance;
use App\Models\Payslip;
use App\Models\RefDepartment;
use App\Models\StaffProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Sample KMS working data — never run in production (see `DatabaseSeeder`).
 * Assumes `ReferenceDataSeeder` and `RoleSeeder` have already run.
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = $this->staffUser('admin@example.com', 'Admin', 'admin');
        $principal = $this->staffUser('principal@example.com', 'Guru Besar', 'principal');
        $teacher1 = $this->staffUser('teacher1@example.com', 'Cikgu Aisyah', 'teacher');
        $teacher2 = $this->staffUser('teacher2@example.com', 'Cikgu Hafiz', 'teacher');

        $babyschool = RefDepartment::query()->where('name', 'Babyschool')->first();
        $playschool = RefDepartment::query()->where('name', 'Playschool')->first();

        $approvedChild = Child::factory()->approved()->create([
            'department_id' => $playschool?->id,
            'responsible_teacher_id' => $teacher1->id,
            'created_by' => $admin->id,
            'reviewed_by' => $principal->id,
        ]);
        ChildGuardian::factory()->for($approvedChild)->create(['type' => 'father']);
        ChildGuardian::factory()->for($approvedChild)->create(['type' => 'mother']);

        $submittedChild = Child::factory()->submitted()->create([
            'department_id' => $babyschool?->id,
            'created_by' => $principal->id,
        ]);
        ChildGuardian::factory()->for($submittedChild)->create(['type' => 'mother']);

        Child::factory()->create(['created_by' => $admin->id]);

        JobApplication::factory()->create();
        JobApplication::factory()->create([
            'status' => JobApplicationStatus::Approved,
            'reviewed_by' => $principal->id,
            'reviewed_at' => now()->subWeek(),
        ]);

        LeaveApplication::factory()->submitted()->create([
            'staff_id' => $teacher1->id,
        ]);
        LeaveApplication::factory()->create([
            'staff_id' => $teacher2->id,
            'status' => LeaveApplicationStatus::Approved,
            'submitted_at' => now()->subMonth(),
            'reviewed_by' => $principal->id,
            'reviewed_at' => now()->subMonth(),
        ]);

        Payslip::factory()->create([
            'staff_id' => $teacher1->id,
            'created_by' => $admin->id,
        ]);
    }

    private function staffUser(string $email, string $shortName, string $role): User
    {
        $user = User::withTrashed()->where('email', $email)->first()
            ?? User::factory()->create([
                'name' => $shortName,
                'short_name' => $shortName,
                'email' => $email,
            ]);

        $user->assignRole($role);

        $staffProfile = StaffProfile::query()->firstOrCreate(
            ['user_id' => $user->id],
            StaffProfile::factory()->make(['user_id' => $user->id, 'full_name' => mb_strtoupper($shortName)])->toArray(),
        );

        LeaveBalance::query()->firstOrCreate(['staff_id' => $user->id], ['balance_days' => 8]);

        return $user;
    }
}
