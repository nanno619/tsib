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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->string('applicant_name');
            $table->foreignId('gender_id')->constrained('ref_genders');
            $table->date('date_of_birth');
            $table->foreignId('race_id')->constrained('ref_races');
            $table->foreignId('religion_id')->constrained('ref_religions');
            $table->unsignedTinyInteger('siblings_count');
            $table->string('mobile_number');
            $table->foreignId('education_level_id')->constrained('ref_education_levels');
            $table->string('education_detail')->nullable();
            $table->string('ambition');
            $table->foreignId('marital_status_id')->constrained('ref_marital_statuses');
            $table->text('field_experience');
            $table->text('previous_work_experience');
            $table->text('reason_left_previous_job')->nullable();
            $table->boolean('has_mental_illness');
            $table->text('illness_details')->nullable();
            $table->string('family_member_name');
            $table->string('family_member_ic');
            $table->string('family_member_occupation');
            $table->text('family_member_employer_address');
            $table->string('family_member_phone');
            $table->string('status')->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('created_user_id')->nullable()->constrained('users');
            $table->timestamps();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
