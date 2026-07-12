<?php

namespace App\Support;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

/**
 * Cached category trees for the public navigation (desktop mega menu and
 * mobile off-canvas menu). Admin-side category changes must call clear().
 */
class MenuCache
{
    public const KEY = 'nav:categories';

    /**
     * The root categories per gender with children and mega-menu assets
     * eager-loaded, keyed 'man' / 'women'.
     */
    public static function categories(): array
    {
        return Cache::remember(self::KEY, now()->addHours(6), function () {
            $tree = fn (string $gender) => Category::where('gender', $gender)
                ->whereNull('parent_id')
                ->where('status', true)
                ->orderBy('sort_order', 'asc')
                ->orderBy('id', 'asc')
                ->with(['children' => function ($query) {
                    $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
                }, 'children.asset', 'asset'])
                ->get();

            return [
                'man' => $tree('man'),
                'women' => $tree('women'),
            ];
        });
    }

    public static function clear(): void
    {
        Cache::forget(self::KEY);
    }
}
