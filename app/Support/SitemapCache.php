<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

/**
 * Cache key for the generated XML sitemap (see SitemapController). Any admin
 * change to products, categories, CMS pages or blog posts must call clear()
 * so /sitemap.xml reflects it before the cache TTL expires.
 */
class SitemapCache
{
    public const KEY = 'sitemap.xml';

    public static function clear(): void
    {
        Cache::forget(self::KEY);
    }
}
