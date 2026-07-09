<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Make the footer bottom tag row (above the copyright line) editable.
     * Seed footer_content.tag_links with the previously hard-coded links.
     */
    public function up(): void
    {
        $row = DB::table('settings')->where('key', 'footer_content')->first();

        if (!$row) {
            return;
        }

        $footer = json_decode($row->value ?? '', true) ?: [];

        if (array_key_exists('tag_links', $footer)) {
            return;
        }

        $footer['tag_links'] = [
            ['label' => 'Online Shopping', 'url' => '#'],
            ['label' => 'Promotions', 'url' => '#'],
            ['label' => 'My Orders', 'url' => '#'],
            ['label' => 'Help', 'url' => '#'],
            ['label' => 'Customer Service', 'url' => '#'],
            ['label' => 'Support', 'url' => '#'],
            ['label' => 'Most Populars', 'url' => '#'],
            ['label' => 'New Arrivals', 'url' => '#'],
            ['label' => 'Special Products', 'url' => '#'],
            ['label' => 'Our Stores', 'url' => '#'],
            ['label' => 'Shipping', 'url' => '#'],
            ['label' => 'Payments', 'url' => '#'],
            ['label' => 'Refunds', 'url' => '#'],
            ['label' => 'Checkout', 'url' => '#'],
            ['label' => 'Discount', 'url' => '#'],
            ['label' => 'Terms & Conditions', 'url' => '/terms'],
        ];

        DB::table('settings')
            ->where('key', 'footer_content')
            ->update([
                'value' => json_encode($footer, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        $row = DB::table('settings')->where('key', 'footer_content')->first();

        if (!$row) {
            return;
        }

        $footer = json_decode($row->value ?? '', true) ?: [];
        unset($footer['tag_links']);

        DB::table('settings')
            ->where('key', 'footer_content')
            ->update([
                'value' => json_encode($footer, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'updated_at' => now(),
            ]);
    }
};
