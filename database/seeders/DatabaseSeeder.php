<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * `ReferenceDataSeeder` (the KMS `ref_*` lookup tables) is the only
     * seeder safe to run in production — everything else below it creates
     * demo accounts/records (including a `password`-password superadmin) and
     * is skipped outside local/testing.
     */
    public function run(): void
    {
        $this->call(ReferenceDataSeeder::class);
        $this->call(RoleSeeder::class);

        if (app()->isProduction()) {
            return;
        }

        $this->call(UserSeeder::class);
        $this->call(RolePermissionSeeder::class);
        // After RolePermissionSeeder — it grants every permission that exists.
        $this->call(SuperAdminSeeder::class);
        // After ReferenceDataSeeder — demo children/staff/etc. reference ref_* rows.
        $this->call(DemoSeeder::class);
    }
}
