<?php

namespace Database\Seeders;

use App\Models\RefBank;
use App\Models\RefCountry;
use App\Models\RefDepartment;
use App\Models\RefEducationLevel;
use App\Models\RefGender;
use App\Models\RefHealthIssue;
use App\Models\RefIllness;
use App\Models\RefLeaveType;
use App\Models\RefMaritalStatus;
use App\Models\RefPublicHoliday;
use App\Models\RefRace;
use App\Models\RefReligion;
use App\Models\RefState;
use Illuminate\Database\Seeder;

/**
 * Seeds every `ref_*` lookup table. Safe to run in production — every write
 * is an idempotent `updateOrCreate` keyed on the table's natural key, and
 * none of this is demo/test data (see `DemoSeeder` for that).
 */
class ReferenceDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedCountriesAndStates();
        $this->seedGenders();
        $this->seedReligions();
        $this->seedRaces();
        $this->seedMaritalStatuses();
        $this->seedDepartments();
        $this->seedLeaveTypes();
        $this->seedIllnesses();
        $this->seedHealthIssues();
        $this->seedBanks();
        $this->seedEducationLevels();
        $this->seedPublicHolidays();
    }

    private function seedCountriesAndStates(): void
    {
        $countries = [
            'Malaysia', 'Singapura', 'Indonesia', 'Thailand', 'Filipina', 'Brunei',
            'India', 'China', 'Bangladesh', 'Myanmar', 'Vietnam', 'Pakistan',
            'Nepal', 'Sri Lanka', 'Lain-lain',
        ];

        $malaysia = null;

        foreach ($countries as $index => $name) {
            $country = RefCountry::query()->updateOrCreate(
                ['name' => $name],
                ['is_active' => true, 'sort_order' => $index],
            );

            if ($name === 'Malaysia') {
                $malaysia = $country;
            }
        }

        // Only Malaysia's states/federal territories are modelled — addresses
        // for other nationalities leave `state_id` null (it's nullable).
        $states = [
            'Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan', 'Pahang',
            'Perak', 'Perlis', 'Pulau Pinang', 'Sabah', 'Sarawak', 'Selangor',
            'Terengganu', 'Wilayah Persekutuan Kuala Lumpur',
            'Wilayah Persekutuan Labuan', 'Wilayah Persekutuan Putrajaya',
        ];

        foreach ($states as $index => $name) {
            RefState::query()->updateOrCreate(
                ['country_id' => $malaysia->id, 'name' => $name],
                ['is_active' => true, 'sort_order' => $index],
            );
        }
    }

    private function seedGenders(): void
    {
        foreach (['Lelaki', 'Perempuan'] as $index => $name) {
            RefGender::query()->updateOrCreate(
                ['name' => $name],
                ['is_active' => true, 'sort_order' => $index],
            );
        }
    }

    private function seedReligions(): void
    {
        $religions = ['Islam', 'Buddha', 'Kristian', 'Hindu', 'Sikh', 'Lain-lain'];

        foreach ($religions as $index => $name) {
            RefReligion::query()->updateOrCreate(
                ['name' => $name],
                ['is_active' => true, 'sort_order' => $index],
            );
        }
    }

    private function seedRaces(): void
    {
        $races = ['Melayu', 'Cina', 'India', 'Bumiputera Sabah', 'Bumiputera Sarawak', 'Lain-lain'];

        foreach ($races as $index => $name) {
            RefRace::query()->updateOrCreate(
                ['name' => $name],
                ['is_active' => true, 'sort_order' => $index],
            );
        }
    }

    private function seedMaritalStatuses(): void
    {
        $statuses = ['Bujang', 'Berkahwin', 'Bercerai', 'Duda/Balu'];

        foreach ($statuses as $index => $name) {
            RefMaritalStatus::query()->updateOrCreate(
                ['name' => $name],
                ['is_active' => true, 'sort_order' => $index],
            );
        }
    }

    private function seedDepartments(): void
    {
        $departments = ['Babyschool', 'Playschool', 'Kindergarten'];

        foreach ($departments as $index => $name) {
            RefDepartment::query()->updateOrCreate(
                ['name' => $name],
                ['is_active' => true, 'sort_order' => $index],
            );
        }
    }

    /**
     * Fixed set from the Leave Application checkboxes (`05-backend-schema.md`).
     * Slugs are read literally by leave-duration/balance logic elsewhere
     * (`cuti-tahunan`, `cuti-sakit`) — do not rename without updating that code.
     */
    private function seedLeaveTypes(): void
    {
        $types = [
            'cuti-tahunan' => 'Cuti Tahunan',
            'cuti-kecemasan' => 'Cuti Kecemasan',
            'cuti-sakit' => 'Cuti Sakit',
            'cuti-tanpa-gaji' => 'Cuti Tanpa Gaji',
            'cuti-ganti' => 'Cuti Ganti',
            'cuti-lain-lain' => 'Cuti Lain-lain',
        ];

        foreach ($types as $slug => $name) {
            RefLeaveType::query()->updateOrCreate(['slug' => $slug], ['name' => $name]);
        }
    }

    private function seedIllnesses(): void
    {
        $illnesses = ['Lelah', 'Batuk Kering', 'Sakit Jantung', 'Gastrik', 'Barah', 'Sawan', 'Lain-lain'];

        foreach ($illnesses as $index => $name) {
            RefIllness::query()->updateOrCreate(
                ['name' => $name],
                ['is_active' => true, 'sort_order' => $index],
            );
        }
    }

    private function seedHealthIssues(): void
    {
        $issues = [
            'Cepat Penat', 'Sakit Dada', 'Selalu Pitam/Pening Kepala',
            'Kurang Penglihatan', 'Kurang Pendengaran', 'Alahan', 'Lain-lain',
        ];

        foreach ($issues as $index => $name) {
            RefHealthIssue::query()->updateOrCreate(
                ['name' => $name],
                ['is_active' => true, 'sort_order' => $index],
            );
        }
    }

    private function seedBanks(): void
    {
        $banks = [
            'Maybank', 'CIMB Bank', 'Public Bank', 'RHB Bank', 'Hong Leong Bank',
            'AmBank', 'Bank Islam', 'Bank Rakyat', 'Bank Simpanan Nasional', 'Affin Bank',
        ];

        foreach ($banks as $index => $name) {
            RefBank::query()->updateOrCreate(
                ['name' => $name],
                ['is_active' => true, 'sort_order' => $index],
            );
        }
    }

    private function seedEducationLevels(): void
    {
        $levels = [
            'UPSR', 'PT3/PMR', 'SPM', 'STPM/STAM', 'Sijil', 'Diploma',
            'Ijazah Sarjana Muda', 'Ijazah Sarjana', 'Kedoktoran (PhD)', 'Lain-lain',
        ];

        foreach ($levels as $index => $name) {
            RefEducationLevel::query()->updateOrCreate(
                ['name' => $name],
                ['is_active' => true, 'sort_order' => $index],
            );
        }
    }

    /**
     * Only the fixed-date national holidays are seeded — their calendar date
     * never moves. Malaysia's movable/lunar holidays (Chinese New Year, Hari
     * Raya Aidilfitri/Haji, Wesak Day, Deepavali, Thaipusam, Awal Muharram,
     * Maulidur Rasul, Nuzul Al-Quran, state-level holidays) shift every year
     * and are **admin-entered**, per `05-backend-schema.md` — seeding
     * guessed dates here would silently corrupt leave-duration calculations,
     * so they're deliberately left out.
     */
    private function seedPublicHolidays(): void
    {
        $year = (int) date('Y');

        $holidays = [
            "{$year}-01-01" => 'Tahun Baharu',
            "{$year}-05-01" => 'Hari Pekerja',
            "{$year}-08-31" => 'Hari Kebangsaan',
            "{$year}-09-16" => 'Hari Malaysia',
            "{$year}-12-25" => 'Hari Krismas',
        ];

        $index = 0;

        foreach ($holidays as $date => $name) {
            RefPublicHoliday::query()->updateOrCreate(
                ['holiday_date' => $date],
                ['name' => $name, 'is_active' => true, 'sort_order' => $index++],
            );
        }
    }
}
