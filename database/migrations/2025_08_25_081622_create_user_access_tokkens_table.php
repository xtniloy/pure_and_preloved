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
        Schema::create('user_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('email')->primary();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('token', 64)->unique();
            $table->unsignedInteger('otp')->unique()->nullable();
            $table->unsignedTinyInteger('use_for')->nullable()->comment('1: email_verification, 2: password_update');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_access_tokens');
    }
};
