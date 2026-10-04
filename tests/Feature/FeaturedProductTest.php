<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Category;
use App\Models\HomeSection;
use App\Models\Product;
use App\Support\AdminAccess;
use App\Support\HomeCache;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class FeaturedProductTest extends TestCase
{
    // Matches the rest of the suite: shared local DB, rolled back per test.
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        // Start from a clean featured list regardless of local data (rolled back).
        Product::query()->update(['is_featured' => false, 'featured_order' => null]);
        HomeCache::clear();
    }

    private function admin(array $permissions = ['manage featured products']): Admin
    {
        $admin = Admin::create([
            'name' => 'Featured Admin',
            'email' => 'featured-'.uniqid().'@example.com',
            'password' => 'secret-password',
            'status' => 1,
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, AdminAccess::GUARD);
        }
        $role = Role::create(['name' => 'role-'.uniqid(), 'guard_name' => AdminAccess::GUARD]);
        $role->syncPermissions($permissions);
        $admin->assignRole($role);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return $admin;
    }

    private function category(array $overrides = []): Category
    {
        return Category::create(array_merge([
            'name' => 'Rings',
            'slug' => 'rings-'.uniqid(),
            'gender' => 'women',
            'status' => true,
        ], $overrides));
    }

    private function product(array $overrides = [], ?Category $category = null): Product
    {
        $product = Product::create(array_merge([
            'name' => 'Ring '.uniqid(),
            'slug' => 'ring-'.uniqid(),
            'sku' => 'SKU-'.strtoupper(uniqid()),
            'price' => 100,
            'stock' => 1,
            'status' => true,
        ], $overrides));

        if ($category) {
            $product->categories()->attach($category->id);
        }

        return $product;
    }

    private function featuredIds(): array
    {
        return Product::featured()->pluck('id')->all();
    }

    // ── Access ─────────────────────────────────────────────────────────

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $this->get(route('admin.featured-products.index'))->assertRedirect(route('admin.login'));
    }

    public function test_admin_without_permission_is_forbidden(): void
    {
        $admin = $this->admin(['manage products']);
        $product = $this->product();

        $this->actingAs($admin, 'admin')->get(route('admin.featured-products.index'))->assertForbidden();
        $this->actingAs($admin, 'admin')
            ->post(route('admin.featured-products.store'), ['product_ids' => [$product->id]])
            ->assertForbidden();

        $this->assertFalse($product->fresh()->is_featured);
    }

    // ── Listing & picker ───────────────────────────────────────────────

    public function test_index_shows_featured_list_in_order(): void
    {
        $a = $this->product(['name' => 'Alpha Ring', 'is_featured' => true, 'featured_order' => 2]);
        $b = $this->product(['name' => 'Bravo Ring', 'is_featured' => true, 'featured_order' => 1]);

        $this->actingAs($this->admin(), 'admin')
            ->get(route('admin.featured-products.index'))
            ->assertOk()
            ->assertSeeInOrder(['Bravo Ring', 'Alpha Ring']);
    }

    public function test_picker_hides_already_featured_products_by_default(): void
    {
        $this->product(['name' => 'Featured Zed', 'is_featured' => true, 'featured_order' => 1]);
        $this->product(['name' => 'Plain Zed']);

        $response = $this->actingAs($this->admin(), 'admin')
            ->get(route('admin.featured-products.index', ['tab' => 'add', 'q' => 'Zed']));

        $products = $response->viewData('products');
        $this->assertSame(['Plain Zed'], $products->pluck('name')->all());

        $all = $this->actingAs($this->admin(), 'admin')
            ->get(route('admin.featured-products.index', ['tab' => 'add', 'q' => 'Zed', 'show' => 'all']))
            ->viewData('products');
        $this->assertCount(2, $all);
    }

    public function test_picker_searches_by_sku(): void
    {
        $match = $this->product(['sku' => 'FIND-ME-'.uniqid()]);
        $this->product();

        $products = $this->actingAs($this->admin(), 'admin')
            ->get(route('admin.featured-products.index', ['q' => 'FIND-ME-']))
            ->viewData('products');

        $this->assertSame([$match->id], $products->pluck('id')->all());
    }

    public function test_picker_filters_by_parent_category_including_children(): void
    {
        $parent = $this->category(['name' => 'Necklaces']);
        $child = $this->category(['name' => 'Chokers', 'parent_id' => $parent->id]);
        $other = $this->category(['name' => 'Earrings']);

        $inParent = $this->product([], $parent);
        $inChild = $this->product([], $child);
        $this->product([], $other);

        $ids = $this->actingAs($this->admin(), 'admin')
            ->get(route('admin.featured-products.index', ['category' => $parent->id, 'per_page' => 100]))
            ->viewData('products')->pluck('id')->sort()->values()->all();

        $this->assertSame(collect([$inParent->id, $inChild->id])->sort()->values()->all(), $ids);
    }

    public function test_picker_filters_by_status_and_stock(): void
    {
        $tag = 'Filt'.uniqid();
        $activeIn = $this->product(['name' => "$tag A", 'status' => true, 'stock' => 3]);
        $inactive = $this->product(['name' => "$tag B", 'status' => false, 'stock' => 3]);
        $outOfStock = $this->product(['name' => "$tag C", 'status' => true, 'stock' => 0]);

        $get = fn (array $params) => $this->actingAs($this->admin(), 'admin')
            ->get(route('admin.featured-products.index', $params + ['q' => $tag]))
            ->viewData('products')->pluck('id')->sort()->values()->all();

        $this->assertSame([$inactive->id], $get(['status' => 'inactive']));
        $this->assertSame([$outOfStock->id], $get(['stock' => 'out']));
        $this->assertSame([$activeIn->id], $get(['status' => 'active', 'stock' => 'in']));
    }

    public function test_picker_rejects_invalid_filter_values(): void
    {
        $this->actingAs($this->admin(), 'admin')
            ->get(route('admin.featured-products.index', ['per_page' => 9999]))
            ->assertSessionHasErrors('per_page');
    }

    public function test_warns_when_no_featured_section_is_active_on_homepage(): void
    {
        HomeSection::where('type', 'featured_products')->update(['is_active' => false]);

        $this->actingAs($this->admin(), 'admin')
            ->get(route('admin.featured-products.index'))
            ->assertSee('section is active on the homepage', false);
    }

    // ── Bulk add ───────────────────────────────────────────────────────

    public function test_bulk_add_appends_products_to_end_of_list(): void
    {
        $existing = $this->product(['is_featured' => true, 'featured_order' => 1]);
        $a = $this->product();
        $b = $this->product();

        $this->actingAs($this->admin(), 'admin')
            ->from(route('admin.featured-products.index', ['tab' => 'add', 'q' => 'x']))
            ->post(route('admin.featured-products.store'), ['product_ids' => [$a->id, $b->id]])
            ->assertRedirect(route('admin.featured-products.index', ['tab' => 'add', 'q' => 'x']))
            ->assertSessionHas('success');

        $ids = $this->featuredIds();
        $this->assertSame($existing->id, $ids[0]);
        $this->assertEqualsCanonicalizing([$a->id, $b->id], array_slice($ids, 1));
        $this->assertSame([1, 2, 3], Product::featured()->pluck('featured_order')->all());
    }

    public function test_bulk_add_skips_already_featured_products(): void
    {
        $existing = $this->product(['is_featured' => true, 'featured_order' => 1]);
        $new = $this->product();

        $this->actingAs($this->admin(), 'admin')
            ->post(route('admin.featured-products.store'), ['product_ids' => [$existing->id, $new->id]])
            ->assertSessionHas('success', '1 product added to featured. 1 already featured.');

        $this->assertSame(1, $existing->fresh()->featured_order);
        $this->assertSame(2, $new->fresh()->featured_order);
    }

    public function test_bulk_add_validates_input(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin, 'admin')
            ->post(route('admin.featured-products.store'), [])
            ->assertSessionHasErrors('product_ids');

        $this->actingAs($admin, 'admin')
            ->post(route('admin.featured-products.store'), ['product_ids' => [999999999]])
            ->assertSessionHasErrors('product_ids.0');
    }

    public function test_bulk_add_clears_homepage_cache(): void
    {
        Cache::put(HomeCache::FEATURED_KEY, 'stale');

        $this->actingAs($this->admin(), 'admin')
            ->post(route('admin.featured-products.store'), ['product_ids' => [$this->product()->id]]);

        $this->assertFalse(Cache::has(HomeCache::FEATURED_KEY));
    }

    // ── Bulk remove ────────────────────────────────────────────────────

    public function test_bulk_remove_unfeatures_and_closes_gaps(): void
    {
        $a = $this->product(['is_featured' => true, 'featured_order' => 1]);
        $b = $this->product(['is_featured' => true, 'featured_order' => 2]);
        $c = $this->product(['is_featured' => true, 'featured_order' => 3]);
        $d = $this->product(['is_featured' => true, 'featured_order' => 4]);

        $this->actingAs($this->admin(), 'admin')
            ->delete(route('admin.featured-products.destroy'), ['product_ids' => [$a->id, $c->id]])
            ->assertSessionHas('success', '2 products removed from featured.');

        $this->assertFalse($a->fresh()->is_featured);
        $this->assertNull($a->fresh()->featured_order);
        $this->assertSame([$b->id, $d->id], $this->featuredIds());
        $this->assertSame([1, 2], Product::featured()->pluck('featured_order')->all());
    }

    public function test_bulk_remove_requires_selection(): void
    {
        $this->actingAs($this->admin(), 'admin')
            ->delete(route('admin.featured-products.destroy'), [])
            ->assertSessionHasErrors('product_ids');
    }

    // ── Reorder ────────────────────────────────────────────────────────

    public function test_reorder_saves_new_positions(): void
    {
        $a = $this->product(['is_featured' => true, 'featured_order' => 1]);
        $b = $this->product(['is_featured' => true, 'featured_order' => 2]);
        $c = $this->product(['is_featured' => true, 'featured_order' => 3]);

        $this->actingAs($this->admin(), 'admin')
            ->postJson(route('admin.featured-products.reorder'), ['ids' => [$c->id, $a->id, $b->id]])
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertSame([$c->id, $a->id, $b->id], $this->featuredIds());
    }

    public function test_reorder_rejects_stale_or_foreign_ids(): void
    {
        $a = $this->product(['is_featured' => true, 'featured_order' => 1]);
        $b = $this->product(['is_featured' => true, 'featured_order' => 2]);
        $notFeatured = $this->product();
        $admin = $this->admin();

        // Missing one featured product (list changed in another tab).
        $this->actingAs($admin, 'admin')
            ->postJson(route('admin.featured-products.reorder'), ['ids' => [$b->id]])
            ->assertStatus(409);

        // Includes a product that is not featured.
        $this->actingAs($admin, 'admin')
            ->postJson(route('admin.featured-products.reorder'), ['ids' => [$b->id, $notFeatured->id]])
            ->assertStatus(409);

        $this->assertSame([$a->id, $b->id], $this->featuredIds());
        $this->assertFalse($notFeatured->fresh()->is_featured);
    }

    public function test_reorder_validates_payload(): void
    {
        $this->actingAs($this->admin(), 'admin')
            ->postJson(route('admin.featured-products.reorder'), ['ids' => 'nope'])
            ->assertStatus(422);
    }

    // ── Storefront ─────────────────────────────────────────────────────

    public function test_homepage_shows_active_featured_products_in_admin_order_up_to_section_limit(): void
    {
        HomeSection::where('type', 'featured_products')->update(['is_active' => false]);
        HomeSection::create([
            'type' => 'featured_products',
            'title' => 'Featured',
            'data' => ['heading' => 'Our picks', 'limit' => 2],
            'position' => 999,
            'is_active' => true,
        ]);

        $this->product(['name' => 'Third Pick', 'is_featured' => true, 'featured_order' => 3]);
        $this->product(['name' => 'First Pick', 'is_featured' => true, 'featured_order' => 1]);
        $this->product(['name' => 'Hidden Pick', 'is_featured' => true, 'featured_order' => 2, 'status' => false]);
        $this->product(['name' => 'Second Pick', 'is_featured' => true, 'featured_order' => 4]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSeeInOrder(['First Pick', 'Third Pick'])
            ->assertDontSee('Hidden Pick')
            ->assertDontSee('Second Pick'); // beyond the limit of 2
    }

    public function test_featured_limit_is_clamped(): void
    {
        $this->assertSame(10, Product::featuredLimit([]));
        $this->assertSame(1, Product::featuredLimit(['limit' => 0]));
        $this->assertSame(Product::FEATURED_MAX_SHOWN, Product::featuredLimit(['limit' => 500]));
    }
}
