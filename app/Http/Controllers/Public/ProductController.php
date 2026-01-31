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

        $product = Product::where('slug', $productSlug)
            ->where('category_id', $category->id)
            ->where('status', true)
            ->firstOrFail();

        // Get related products (same category)
        $relatedProducts = Product::where('category_id', $category->id)
            ->where('id', '!=', $product->id)
            ->where('status', true)
            ->take(12)
            ->get();

        return view('public.home.product', compact('product', 'category', 'relatedProducts', 'gender'));
    }
}
