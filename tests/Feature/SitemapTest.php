<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Support\SitemapCache;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    // Matches the rest of the suite: shared local DB, rolled back per test.
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        SitemapCache::clear(); // start each test with a cold sitemap cache
    }

    private function makeAdmin(): Admin
    {
        $admin = Admin::create([
            'name' => 'Sitemap Admin',
            'email' => 'sitemap-admin-' . uniqid() . '@example.com',
            'password' => 'secret-password',
            'status' => 1,
        ]);

        // Admin routes are permission-gated; a Super Admin bypasses all checks.
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        $admin->assignRole(
            \Spatie\Permission\Models\Role::findOrCreate(
                \App\Support\AdminAccess::SUPER_ADMIN,
                \App\Support\AdminAccess::GUARD
            )
        );

        return $admin;
    }

    private function makeCategory(): Category
    {
        return Category::create([
            'name' => 'Rings',
            'slug' => 'rings-' . uniqid(),
            'gender' => 'women',
            'status' => true,
        ]);
    }

    private function makeProduct(Category $category, array $overrides = []): Product
    {
        $product = Product::create(array_merge([
            'name' => 'Test Ring ' . uniqid(),
            'slug' => 'test-ring-' . uniqid(),
            'sku' => 'SKU-' . strtoupper(uniqid()),
            'price' => 100,
            'status' => true,
        ], $overrides));
        $product->categories()->attach($category->id);

        return $product;
    }

    public function test_sitemap_returns_valid_xml_with_public_urls(): void
    {
        $category = $this->makeCategory();
        $product = $this->makeProduct($category);
        $page = Page::create(['title' => 'About', 'slug' => 'about-' . uniqid(), 'status' => true]);
        $post = BlogPost::create([
            'title' => 'Live Post',
            'slug' => 'live-post-' . uniqid(),
            'body' => '<p>x</p>',
            'status' => BlogPost::STATUS_PUBLISHED,
            'published_at' => now()->subHour(),
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');

        // Well-formed XML with a urlset root.
        $xml = simplexml_load_string($response->getContent());
        $this->assertNotFalse($xml, 'sitemap should be well-formed XML');

        // Static + dynamic URLs present.
        $response->assertSee(route('home'), false);
        $response->assertSee(route('shop.index'), false);
        $response->assertSee(route('product.show', ['women', $category->slug, $product->slug]), false);
        $response->assertSee(route('blog.show', $post->slug), false);
        $response->assertSee(url('/' . $page->slug), false);
    }

    public function test_sitemap_excludes_unpublished_content(): void
    {
        $category = $this->makeCategory();
        $inactiveProduct = $this->makeProduct($category, ['status' => false]);
        $draftPost = BlogPost::create([
            'title' => 'Draft Post',
            'slug' => 'draft-post-' . uniqid(),
            'body' => '<p>x</p>',
            'status' => BlogPost::STATUS_DRAFT,
            'published_at' => null,
        ]);
        $hiddenPage = Page::create(['title' => 'Hidden', 'slug' => 'hidden-' . uniqid(), 'status' => false]);

        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertDontSee($inactiveProduct->slug, false);
        $response->assertDontSee($draftPost->slug, false);
        $response->assertDontSee($hiddenPage->slug, false);
    }

    public function test_robots_txt_allows_and_links_sitemap_when_indexing_on(): void
    {
        config(['seo.indexing' => true]);

        $response = $this->get('/robots.txt');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $response->assertSee('Sitemap: ' . url('/sitemap.xml'), false);
        $response->assertSee('Disallow: /checkout', false);
        $response->assertDontSee("Disallow: /\n", false); // not a blanket block
    }

    public function test_robots_txt_blocks_everything_when_indexing_off(): void
    {
        config(['seo.indexing' => false]);

        $response = $this->get('/robots.txt');

        $response->assertOk();
        $response->assertSee('Disallow: /', false);
        $response->assertDontSee('Sitemap:', false);
    }

    public function test_admin_page_save_refreshes_the_sitemap_cache(): void
    {
        // Prime the cache with the current sitemap.
        $this->get('/sitemap.xml')->assertOk();

        // Creating a page through the admin should clear the sitemap cache,
        // so the new page appears on the next request.
        $admin = $this->makeAdmin();
        $slug = 'fresh-page-' . uniqid();

        $this->actingAs($admin, 'admin')
            ->post(route('admin.pages.store'), [
                'title' => 'Fresh Page',
                'slug' => $slug,
                'body' => '<p>hi</p>',
                'status' => 1,
            ])
            ->assertRedirect(route('admin.pages.index'));

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee(url('/' . $slug), false);
    }
}
