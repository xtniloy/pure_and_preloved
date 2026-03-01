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
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('charge', 10, 2)->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // Seed initial methods
        \Illuminate\Support\Facades\DB::table('shipping_methods')->insert([
            ['name' => 'Free Delivery', 'charge' => 0, 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Special 24 Delivery', 'charge' => 30, 'status' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_methods');
    }
};
