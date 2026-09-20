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
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('staff_id')->constrained('users');
            $table->foreignId('ref_leave_type_id')->constrained('ref_leave_types');
            $table->string('other_type_detail')->nullable();
            $table->date('date_from');
            $table->date('date_to');
            $table->decimal('duration_days', 4, 1);
            $table->text('reason');
            $table->string('status')->default('draft');
            $table->text('return_reason')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['staff_id', 'status']);
            $table->index(['date_from', 'date_to']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_applications');
    }
};
