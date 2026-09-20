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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('web_app_status')->default('online');
            $table->string('domain_name')->nullable();
            $table->string('copyright_by')->nullable();
            $table->string('copyright_year')->nullable();
            $table->string('enquiry_email')->nullable();
            $table->string('outgoing_mail_server')->nullable();
            $table->unsignedSmallInteger('smtp_port')->nullable();
            $table->string('reply_email')->nullable();
            $table->text('reply_email_password')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
