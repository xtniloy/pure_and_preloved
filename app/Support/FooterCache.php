<?php

namespace App\Support;

use App\Models\BlogPost;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

/**
 * Cached footer payload (footer content setting + latest blog posts),
 * rendered on every public page. Admin footer/blog changes must call
 * clear(). Note: a scheduled post crossing its publish time appears when
 * the TTL expires or any blog admin action busts the cache.
 */
class FooterCache
{
    public const KEY = 'footer:data';

    public static function data(): array
    {
        return Cache::remember(self::KEY, now()->addHours(6), function () {
            return [
                'content' => Setting::getJson('footer_content', []),
                'blog_posts' => BlogPost::published()
                    ->with(['featuredImage', 'author'])
                    ->orderByDesc('published_at')
                    ->take(4)
                    ->get(),
            ];
        });
    }

    public static function clear(): void
    {
        Cache::forget(self::KEY);
    }
}
