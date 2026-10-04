<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HomeSection;
use App\Models\Product;
use App\Support\HomeCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Curates the ordered list of featured products shown by the homepage
 * "Featured Products Slider" section(s).
 *
 * The page has two tabs: the current featured list (drag to reorder, bulk
 * remove) and a filterable, paginated product picker (bulk add), so a
 * catalogue of thousands of products stays manageable.
 */
class FeaturedProductController extends Controller
{
    private const PER_PAGE_OPTIONS = [25, 50, 100];

    public function index(Request $request)
    {
        $featured = Product::featured()->with(['categories', 'thumbnailImage'])->get();
        Product::preloadAssets($featured);

        $filters = $request->validate([
            'q' => 'nullable|string|max:255',
            'category' => 'nullable|integer',
            'status' => 'nullable|in:active,inactive',
            'stock' => 'nullable|in:in,out',
            'show' => 'nullable|in:not_featured,all',
            'per_page' => 'nullable|integer|in:'.implode(',', self::PER_PAGE_OPTIONS),
        ]);

        $products = $this->pickerQuery($filters)
            ->with(['categories', 'thumbnailImage'])
            ->paginate($filters['per_page'] ?? self::PER_PAGE_OPTIONS[0])
            ->withQueryString();
        Product::preloadAssets($products->getCollection());

        $categories = Category::with('parent')->orderBy('gender')->orderBy('sort_order')->orderBy('name')->get();

        // How many featured products the homepage actually renders (largest
        // limit among active slider sections; null when none is active).
        $homepageLimit = HomeSection::where('type', 'featured_products')
            ->where('is_active', true)
            ->get()
            ->map(fn ($section) => Product::featuredLimit($section->data))
            ->max();

        return view('admin.sections.featured_products.index', [
            'featured' => $featured,
            'products' => $products,
            'categories' => $categories,
            'filters' => $filters,
            'perPageOptions' => self::PER_PAGE_OPTIONS,
            'homepageLimit' => $homepageLimit,
            'activeTab' => $request->query('tab') === 'add' ? 'add' : 'featured',
        ]);
    }

    /** Bulk add: appends the selected products to the end of the list. */
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_ids' => 'required|array|min:1|max:200',
            'product_ids.*' => 'integer|distinct|exists:products,id',
        ], [
            'product_ids.required' => 'Select at least one product to feature.',
        ]);

        $added = DB::transaction(function () use ($data) {
            $next = (int) Product::where('is_featured', true)->lockForUpdate()->max('featured_order');

            $toAdd = Product::whereIn('id', $data['product_ids'])
                ->where('is_featured', false)
                ->orderBy('id', 'desc')
                ->pluck('id');

            foreach ($toAdd as $id) {
                Product::whereKey($id)->update(['is_featured' => true, 'featured_order' => ++$next]);
            }

            return $toAdd->count();
        });

        HomeCache::clear();

        $skipped = count($data['product_ids']) - $added;
        $message = $added.' '.str('product')->plural($added).' added to featured.';
        if ($skipped > 0) {
            $message .= " {$skipped} already featured.";
        }

        return back()->with('success', $message);
    }

    /** Bulk remove, then close the gaps in the ordering. */
    public function destroy(Request $request)
    {
        $data = $request->validate([
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'integer|distinct',
        ], [
            'product_ids.required' => 'Select at least one product to remove.',
        ]);

        $removed = DB::transaction(function () use ($data) {
            $removed = Product::whereIn('id', $data['product_ids'])
                ->where('is_featured', true)
                ->update(['is_featured' => false, 'featured_order' => null]);

            $this->resequence(Product::featured()->pluck('id')->all());

            return $removed;
        });

        HomeCache::clear();

        return back()->with('success', $removed.' '.str('product')->plural($removed).' removed from featured.');
    }

    /** Saves the full drag-and-drop order (AJAX). */
    public function reorder(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|distinct',
        ]);

        $featuredIds = Product::where('is_featured', true)->pluck('id')->all();
        $ids = array_map('intval', $data['ids']);

        // The client must send exactly the current featured set; anything else
        // means the page is stale (e.g. edited in another tab).
        if (count($ids) !== count($featuredIds) || array_diff($ids, $featuredIds)) {
            return response()->json([
                'success' => false,
                'message' => 'The featured list changed elsewhere. Reload the page and try again.',
            ], 409);
        }

        DB::transaction(fn () => $this->resequence($ids));

        HomeCache::clear();

        return response()->json(['success' => true, 'message' => 'Order saved.']);
    }

    /** Products matching the picker filters. */
    private function pickerQuery(array $filters)
    {
        $query = Product::query();

        if (! empty($filters['q'])) {
            $q = trim($filters['q']);
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', '%'.$q.'%')
                    ->orWhere('sku', 'like', '%'.$q.'%');
            });
        }

        if (! empty($filters['category'])) {
            // Include direct children so picking a parent category finds its products.
            $categoryIds = Category::where('id', $filters['category'])
                ->orWhere('parent_id', $filters['category'])
                ->pluck('id');
            $query->whereHas('categories', fn ($sub) => $sub->whereIn('categories.id', $categoryIds));
        }

        if (($filters['status'] ?? null) === 'active') {
            $query->where('status', true);
        } elseif (($filters['status'] ?? null) === 'inactive') {
            $query->where('status', false);
        }

        if (($filters['stock'] ?? null) === 'in') {
            $query->where('stock', '>', 0);
        } elseif (($filters['stock'] ?? null) === 'out') {
            $query->where(fn ($sub) => $sub->whereNull('stock')->orWhere('stock', '<=', 0));
        }

        if (($filters['show'] ?? 'not_featured') === 'not_featured') {
            $query->where('is_featured', false);
        }

        return $query->latest('id');
    }

    /** Writes positions 1..n in the given id order. */
    private function resequence(array $ids): void
    {
        foreach (array_values($ids) as $index => $id) {
            Product::whereKey($id)->update(['featured_order' => $index + 1]);
        }
    }
}
