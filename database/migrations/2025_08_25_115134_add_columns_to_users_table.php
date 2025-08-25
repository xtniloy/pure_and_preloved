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
        Schema::table('users', function (Blueprint $table) {
            $table->string('uuid', 50)->after('id')->unique();
            $table->string('new_email', 20)->nullable()->after('email');
            $table->string('phone', 20)->nullable()->after('new_email');
            $table->tinyInteger('status')->after('phone')->default(1);
            $table->foreignId('verified_by')->nullable()->after('status')->constrained('admins')->onDelete('set null');
            $table->timestamp('last_login')->nullable()->after('verified_by');
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('uuid');
            $table->dropColumn('new_email');
            $table->dropColumn('phone');
            $table->dropColumn('status');
            $table->dropColumn('verified_by');
            $table->dropColumn('last_login');
            $table->string('password')->nullable(false)->change();
        });
    }
};
