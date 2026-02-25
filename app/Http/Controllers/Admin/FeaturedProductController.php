<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class FeaturedProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('categories')->orderBy('created_at', 'desc');

        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', '%' . $q . '%')
                    ->orWhere('sku', 'like', '%' . $q . '%');
            });
        }

        $products = $query->paginate(20)->appends([
            'q' => $request->q,
        ]);

        return view('admin.sections.featured_products.index', compact('products'));
    }

    public function toggle(Request $request, Product $product)
    {
        $request->validate([
            'is_featured' => 'required|boolean',
        ]);

        $product->update([
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()->route('admin.featured-products.index')->with('success', 'Featured status updated.');
    }
}

