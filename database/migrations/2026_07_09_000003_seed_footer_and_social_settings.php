<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        // Content that was previously hard-coded in the footer / off-canvas views.
        // Relative link URLs (e.g. /login) are resolved with url() when rendering.
        $footerContent = [
            'about_text' => 'Preloved Jewellery | Gold Diamond Silver | Rings Pendants Bracelets Earrings | Free Nationwide Delivery | Evri & Royal Mail 📮🇬🇧',
            'address' => 'Dixon Street, Lincoln, United Kingdom',
            'email' => 'support@pureandpreloved.co.uk',
            'phone' => '+44 7396 823194',
            'custom_links' => [
                ['label' => 'Legal Notice', 'url' => '#'],
                ['label' => 'Prices Drop', 'url' => '#'],
                ['label' => 'New Products', 'url' => '#'],
                ['label' => 'Best Sales', 'url' => '#'],
                ['label' => 'Login', 'url' => '/login'],
                ['label' => 'My Account', 'url' => '/dashboard'],
            ],
            'copyright' => 'Copyright © <a href="https://pureandpreloved.co.uk/"> Pure and Preloved</a>. All Rights Reserved',
        ];

        $socialLinks = [
            ['platform' => 'facebook', 'url' => 'https://facebook.com/PureAndPreloved'],
            ['platform' => 'twitter', 'url' => 'https://x.com/pureandpreloved'],
            ['platform' => 'google', 'url' => 'https://threads.com/pureandpreloved'],
            ['platform' => 'pinterest', 'url' => 'https://pinterest.com/pureandpreloved'],
            ['platform' => 'instagram', 'url' => 'https://instagram.com/pureandpreloved'],
        ];

        foreach ([
            'footer_content' => json_encode($footerContent, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'social_links' => json_encode($socialLinks, JSON_UNESCAPED_SLASHES),
        ] as $key => $value) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'created_at' => $now, 'updated_at' => $now]
            );
        }
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', ['footer_content', 'social_links'])->delete();
    }
};
