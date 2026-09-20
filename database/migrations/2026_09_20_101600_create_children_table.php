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
        Schema::create('children', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->string('full_name');
            $table->string('ic_number')->nullable()->unique();
            $table->string('birth_certificate_number')->unique();
            $table->date('date_of_birth');
            $table->foreignId('gender_id')->constrained('ref_genders');
            $table->foreignId('religion_id')->constrained('ref_religions');
            $table->foreignId('race_id')->constrained('ref_races');
            $table->foreignId('nationality_id')->constrained('ref_countries');
            $table->foreignId('department_id')->nullable()->constrained('ref_departments');
            $table->foreignId('responsible_teacher_id')->nullable()->constrained('users');
            $table->boolean('has_disability')->default(false);
            $table->text('disability_details')->nullable();
            $table->string('status')->default('draft');
            $table->text('return_reason')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('parent_confirmed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index(['department_id', 'status']);
            $table->index(['responsible_teacher_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children');
    }
};
