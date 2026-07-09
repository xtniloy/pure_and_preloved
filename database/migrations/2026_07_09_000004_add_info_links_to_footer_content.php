<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The footer "Information" column used to list all active pages
     * automatically. Make it editable: seed footer_content.info_links
     * from the pages that are currently shown so nothing changes visually.
     */
    public function up(): void
    {
        $row = DB::table('settings')->where('key', 'footer_content')->first();

        if (!$row) {
            return;
        }

        $footer = json_decode($row->value ?? '', true) ?: [];

        if (array_key_exists('info_links', $footer)) {
            return;
        }

        $footer['info_links'] = DB::table('pages')
            ->where('status', true)
            ->orderBy('title')
            ->get(['title', 'slug'])
            ->map(fn ($page) => ['label' => $page->title, 'url' => '/' . $page->slug])
            ->values()
            ->all();

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
        unset($footer['info_links']);

        DB::table('settings')
            ->where('key', 'footer_content')
            ->update([
                'value' => json_encode($footer, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'updated_at' => now(),
            ]);
    }
};
