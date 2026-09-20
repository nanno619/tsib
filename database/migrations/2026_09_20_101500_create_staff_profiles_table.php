<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('staff_profiles', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('user_id')->unique()->constrained();
            $table->string('staff_number')->unique();
            $table->string('full_name');
            $table->string('ic_number')->index();
            $table->date('date_of_birth');
            $table->foreignId('gender_id')->constrained('ref_genders');
            $table->foreignId('race_id')->constrained('ref_races');
            $table->foreignId('religion_id')->constrained('ref_religions');
            $table->foreignId('marital_status_id')->nullable()->constrained('ref_marital_statuses');
            $table->string('mobile_number');
            $table->unsignedTinyInteger('siblings_count')->nullable();
            $table->foreignId('education_level_id')->nullable()->constrained('ref_education_levels');
            $table->string('education_detail')->nullable();
            $table->string('ambition')->nullable();
            $table->text('field_experience')->nullable();
            $table->text('previous_work_experience')->nullable();
            $table->text('reason_left_previous_job')->nullable();
            $table->boolean('has_mental_illness')->nullable();
            $table->text('illness_details')->nullable();
            $table->string('family_member_name')->nullable();
            $table->string('family_member_ic')->nullable();
            $table->string('family_member_occupation')->nullable();
            $table->text('family_member_employer_address')->nullable();
            $table->string('family_member_phone')->nullable();
            $table->string('epf_number')->nullable();
            $table->foreignId('department_id')->nullable()->constrained('ref_departments');
            $table->foreignId('bank_id')->nullable()->constrained('ref_banks');
            $table->string('bank_account_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_profiles');
    }
};
