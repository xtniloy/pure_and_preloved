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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('original_name',255);
            $table->string('stored_name',255);
            $table->string('directory',255)->nullable();
            $table->string('path',511)->nullable();
//            $table->morphs('fileable');

            $table->string('metadata',255)->nullable();
            $table->string('mime_type',100)->nullable();
            $table->smallInteger('order')->nullable();
            $table->string('group',100)->nullable();
            $table->unsignedTinyInteger('status');
            $table->unsignedBigInteger('size')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
