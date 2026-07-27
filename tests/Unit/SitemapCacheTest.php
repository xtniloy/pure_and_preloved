<?php

namespace Tests\Unit;

use App\Support\SitemapCache;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SitemapCacheTest extends TestCase
{
    public function test_clear_forgets_the_sitemap_cache_key(): void
    {
        Cache::put(SitemapCache::KEY, '<xml/>', 60);
        $this->assertTrue(Cache::has(SitemapCache::KEY));

        SitemapCache::clear();

        $this->assertFalse(Cache::has(SitemapCache::KEY));
    }
}
