<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * `ReferenceDataSeeder` (the KMS `ref_*` lookup tables), `RoleSeeder`
     * and the Setting row below are the only things safe to run in
     * production — everything else below that is skipped outside
     * local/testing since it creates demo accounts/records (including a
     * `password`-password superadmin).
     */
    public function run(): void
    {
        $this->call(ReferenceDataSeeder::class);
        $this->call(RoleSeeder::class);
        // Ensures the singleton settings row exists deterministically,
        // rather than relying on CheckMaintenanceMode's lazy fallback.
        Setting::current();

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
