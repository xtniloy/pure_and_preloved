<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Featured products get an explicit, admin-controlled position instead of
     * being implicitly ordered by created_at.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('featured_order')->nullable()->after('is_featured');
            $table->index(['is_featured', 'featured_order']);
        });

        // Backfill: keep the order the homepage already showed (newest first).
        $ids = DB::table('products')
            ->where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->pluck('id');

        foreach ($ids as $index => $id) {
            DB::table('products')->where('id', $id)->update(['featured_order' => $index + 1]);
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['is_featured', 'featured_order']);
            $table->dropColumn('featured_order');
        });
    }
};
