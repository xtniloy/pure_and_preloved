<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.sections.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all(); // Or filter as needed, e.g., only leaf categories
        return view('admin.sections.products.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'price' => 'required|numeric',
            'sale_price' => 'nullable|numeric|lt:price',
            'category_id' => 'required|exists:categories,id',
            'images' => 'nullable|array', // Assuming array of asset IDs
            'images.*' => 'exists:assets,id',
        ]);

        $slug = Str::slug($request->name);
        $count = Product::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        $data = $request->all();
        $data['slug'] = $slug;
        $data['status'] = $request->has('status');

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.sections.products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'price' => 'required|numeric',
            'sale_price' => 'nullable|numeric|lt:price',
            'category_id' => 'required|exists:categories,id',
            'images' => 'nullable|array',
            'images.*' => 'exists:assets,id',
        ]);

        $slug = Str::slug($request->name);
        if ($slug != $product->slug) {
             $count = Product::where('slug', $slug)->where('id', '!=', $product->id)->count();
             if ($count > 0) {
                 $slug = $slug . '-' . ($count + 1);
             }
        }

        $data = $request->all();
        $data['slug'] = $slug;
        $data['status'] = $request->has('status');

        // Handle images if not present in request (clearing them)
        if (!$request->has('images')) {
            $data['images'] = null;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
