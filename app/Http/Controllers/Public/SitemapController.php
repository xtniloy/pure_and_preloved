<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Page;
use App\Models\Product;
use App\Support\SitemapCache;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /** How long the generated sitemap XML is cached. */
    private const CACHE_TTL = 3600; // 1 hour

    /**
     * Dynamic XML sitemap of all public URLs. The rendered XML is cached so
     * repeated crawler hits don't re-run the queries.
     */
    public function index()
    {
        $xml = Cache::remember(SitemapCache::KEY, self::CACHE_TTL, fn () => $this->render($this->urls()));

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    /**
     * robots.txt — references the sitemap and honours the SEO_INDEXING switch:
     * when indexing is off the whole site is disallowed, reinforcing the
     * noindex meta tag.
     */
    public function robots()
    {
        $lines = ['User-agent: *'];

        if (config('seo.indexing', false)) {
            // Keep private / transactional areas out of the index.
            foreach (['/cart', '/checkout', '/wishlist', '/account', '/login', '/registration', '/forget-password', '/set-password', '/order-success'] as $path) {
                $lines[] = 'Disallow: ' . $path;
            }
            $lines[] = '';
            $lines[] = 'Sitemap: ' . url('/sitemap.xml');
        } else {
            $lines[] = 'Disallow: /';
        }

        return response(implode("\n", $lines) . "\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    /**
     * Collect every public URL as [loc, lastmod?, changefreq, priority].
     */
    private function urls(): array
    {
        $urls = [
            ['loc' => route('home'),         'changefreq' => 'daily',   'priority' => '1.0'],
            ['loc' => route('shop.index'),   'changefreq' => 'daily',   'priority' => '0.9'],
            ['loc' => route('blog.index'),   'changefreq' => 'weekly',  'priority' => '0.6'],
            ['loc' => route('contact.index'),'changefreq' => 'monthly', 'priority' => '0.4'],
        ];

        // Active products — URL is /product/{gender}/{category}/{slug}
        Product::where('status', 1)->with('categories:id,slug,gender')->get()
            ->each(function (Product $product) use (&$urls) {
                $category = $product->categories->first();
                if (! $category || ! $category->slug || ! $product->slug) {
                    return; // can't build a valid URL without both slugs
                }
                $urls[] = [
                    'loc' => route('product.show', [
                        'gender'   => $category->gender ?: 'women',
                        'category' => $category->slug,
                        'product'  => $product->slug,
                    ]),
                    'lastmod'    => optional($product->updated_at)->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority'   => '0.8',
                ];
            });

        // Published blog posts
        BlogPost::published()->get(['slug', 'updated_at', 'published_at'])
            ->each(function (BlogPost $post) use (&$urls) {
                if (! $post->slug) {
                    return;
                }
                $urls[] = [
                    'loc'        => route('blog.show', $post->slug),
                    'lastmod'    => optional($post->updated_at ?? $post->published_at)->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority'   => '0.5',
                ];
            });

        // Active CMS pages (served at the root, e.g. /terms)
        Page::where('status', true)->get(['slug', 'updated_at'])
            ->each(function (Page $page) use (&$urls) {
                if (! $page->slug) {
                    return;
                }
                $urls[] = [
                    'loc'        => url('/' . $page->slug),
                    'lastmod'    => optional($page->updated_at)->toAtomString(),
                    'changefreq' => 'monthly',
                    'priority'   => '0.5',
                ];
            });

        return $urls;
    }

    /**
     * Build the sitemap XML string from collected URL entries.
     */
    private function render(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $u) {
            $xml .= "    <url>\n";
            $xml .= '        <loc>' . htmlspecialchars($u['loc'], ENT_XML1) . "</loc>\n";
            if (! empty($u['lastmod'])) {
                $xml .= '        <lastmod>' . $u['lastmod'] . "</lastmod>\n";
            }
            $xml .= '        <changefreq>' . $u['changefreq'] . "</changefreq>\n";
            $xml .= '        <priority>' . $u['priority'] . "</priority>\n";
            $xml .= "    </url>\n";
        }

        return $xml . '</urlset>';
    }
}
