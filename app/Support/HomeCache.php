<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

/**
 * Cache keys for the public homepage payload (sections, featured products,
 * SEO settings). Any admin-side change that affects what the homepage
 * renders must call clear().
 */
class HomeCache
{
    public const SECTIONS_KEY = 'home:sections';
    public const FEATURED_KEY = 'home:featured_products';
    public const SEO_KEY = 'home:seo';

    public static function clear(): void
    {
        Cache::forget(self::SECTIONS_KEY);
        Cache::forget(self::FEATURED_KEY);
        Cache::forget(self::SEO_KEY);
    }
}
