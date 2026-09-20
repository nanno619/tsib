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
        Schema::create('child_guardians', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('child_id')->constrained();
            $table->string('type');
            $table->string('full_name');
            $table->string('ic_number')->index();
            $table->date('date_of_birth');
            $table->foreignId('race_id')->constrained('ref_races');
            $table->foreignId('religion_id')->constrained('ref_religions');
            $table->foreignId('nationality_id')->constrained('ref_countries');
            $table->foreignId('marital_status_id')->constrained('ref_marital_statuses');
            $table->string('home_phone')->nullable();
            $table->string('mobile_number');
            $table->string('office_number')->nullable();
            $table->string('email')->nullable();
            $table->text('employer_position_address');
            $table->timestamps();

            $table->unique(['child_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('child_guardians');
    }
};
