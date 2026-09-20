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
        Schema::create('payslips', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('staff_id')->constrained('users');
            $table->string('salary_month', 7);
            $table->date('salary_date');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('basic_salary', 10, 2);
            $table->decimal('overtime', 10, 2)->nullable();
            $table->decimal('allowances', 10, 2)->nullable();
            $table->decimal('advance', 10, 2)->nullable();
            $table->decimal('epf_staff', 10, 2);
            $table->decimal('epf_employer', 10, 2);
            $table->decimal('socso_staff', 10, 2);
            $table->decimal('socso_employer', 10, 2);
            $table->decimal('eis_staff', 10, 2);
            $table->decimal('eis_employer', 10, 2);
            $table->string('status')->default('draft');
            $table->text('return_reason')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();

            $table->unique(['staff_id', 'salary_month']);
            $table->index('salary_month');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payslips');
    }
};
