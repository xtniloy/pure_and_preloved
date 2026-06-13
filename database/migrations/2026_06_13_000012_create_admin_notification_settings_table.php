<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_notification_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete();
            $table->string('type');               // e.g. contact, order
            $table->boolean('mail')->default(true);
            $table->boolean('web')->default(true);
            $table->timestamps();

            $table->unique(['admin_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_notification_settings');
    }
};
