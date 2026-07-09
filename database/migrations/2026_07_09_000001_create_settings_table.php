<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        $now = now();

        DB::table('settings')->insert([
            [
                'key' => 'home_meta_title',
                'value' => 'Pure & Preloved',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'home_meta_description',
                'value' => 'Pure and Preloved — carefully curated pre-owned and vintage jewellery from top UK brands.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'home_meta_keywords',
                'value' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'home_meta_image_id',
                'value' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
