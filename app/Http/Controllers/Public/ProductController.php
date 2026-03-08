<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('status', true);

        // Filter by Gender
        $activeGender = $request->get('gender', 'women');
        $query->whereHas('categories', function($q) use ($activeGender) {
            $q->where('gender', $activeGender);
        });

        // Filter by Search
        if ($request->has('q') && !empty($request->q)) {
            $query->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('sku', 'like', '%' . $request->q . '%')
                  ->orWhere('description', 'like', '%' . $request->q . '%');
        }

        // Filter by Category
        if ($request->has('category') && !empty($request->category)) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by Condition
        if ($request->has('condition') && !empty($request->condition)) {
            $query->where('condition', $request->condition);
        }

        // Filter by Price Range
        if ($request->has('min_price') && $request->has('max_price')) {
            $query->whereBetween('price', [$request->min_price, $request->max_price]);
        }

        // Filter by Tags (Material, Carat)
        if ($request->has('tag') && !empty($request->tag)) {
            $tag = $request->tag;
            $query->where(function($q) use ($tag) {
                $q->where('material', $tag)
                  ->orWhere('carat', $tag);
            });
        }

        // Sort by
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        
        // Data for filters
        $categories = Category::where('status', true)
            ->where('gender', $activeGender)
            ->whereNull('parent_id')
            ->with('children')
            ->get();
        
        $conditions = Product::where('status', true)->whereNotNull('condition')->distinct()->pluck('condition');
        
        // Tags (Material & Carat)
        $materials = Product::where('status', true)->whereNotNull('material')->distinct()->pluck('material');
        $carats = Product::where('status', true)->whereNotNull('carat')->distinct()->pluck('carat');
        $tags = $materials->merge($carats)->unique()->values();
        
        // Min/Max Price for range slider
        $minPrice = Product::where('status', true)->min('price') ?? 0;
        $maxPrice = Product::where('status', true)->max('price') ?? 10000;

        return view('public.home.shop_list', compact('products', 'categories', 'conditions', 'tags', 'minPrice', 'maxPrice', 'sort', 'activeGender'));
    }

    public function show($gender, $categorySlug, $productSlug)
    {
        // Resolve category
        $category = Category::where('slug', $categorySlug)->firstOrFail();

        // Optional: Validate gender matches category gender or is consistent
        // if ($category->gender !== 'unisex' && $category->gender !== $gender) {
        //    abort(404);
        // }

        // Updated query to use relationship
        $product = Product::where('slug', $productSlug)
            ->whereHas('categories', function($q) use ($category) {
                $q->where('categories.id', $category->id);
            })
            ->where('status', true)
            ->firstOrFail();

        // Get related products (share at least one category with the current product, ideally the current context category)
        $relatedProducts = Product::whereHas('categories', function($q) use ($category) {
                $q->where('categories.id', $category->id);
            })
            ->where('id', '!=', $product->id)
            ->where('status', true)
            ->take(12)
            ->get();

        return view('public.home.product', compact('product', 'category', 'relatedProducts', 'gender'));
    }
}
