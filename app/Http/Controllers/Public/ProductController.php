<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
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
