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
        Schema::create('child_health_issues', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('child_id')->constrained();
            $table->foreignId('ref_health_issue_id')->constrained('ref_health_issues');
            $table->string('detail')->nullable();
            $table->timestamps();

            $table->unique(['child_id', 'ref_health_issue_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('child_health_issues');
    }
};
