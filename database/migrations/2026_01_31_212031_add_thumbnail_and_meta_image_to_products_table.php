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
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('thumbnail_image_id')->nullable()->after('images');
            $table->unsignedBigInteger('meta_image_id')->nullable()->after('thumbnail_image_id');

            $table->foreign('thumbnail_image_id')->references('id')->on('assets')->onDelete('set null');
            $table->foreign('meta_image_id')->references('id')->on('assets')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['thumbnail_image_id']);
            $table->dropForeign(['meta_image_id']);
            $table->dropColumn(['thumbnail_image_id', 'meta_image_id']);
        });
    }
};
