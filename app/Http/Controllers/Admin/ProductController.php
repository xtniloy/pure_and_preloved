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
        $products = Product::with('categories')->latest()->paginate(10);
        return view('admin.sections.products.index', compact('products'));
    }

    public function create()
    {
        // Load categories with parents to build hierarchy names if needed
        $categories = Category::with('parent')->get();
        return view('admin.sections.products.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'price' => 'required|numeric',
            'sale_price' => 'nullable|numeric|lt:price',
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => 'exists:categories,id',
            'images' => 'nullable|array', // Assuming array of asset IDs
            'images.*' => 'exists:assets,id',
            'thumbnail_image_id' => 'nullable|exists:assets,id',
            'meta_image_id' => 'nullable|exists:assets,id',
        ], [
            'categories.required' => 'Please select at least one category.',
        ]);

        // Validate gender consistency
        $categories = Category::whereIn('id', $request->categories)->get();
        $genders = $categories->pluck('gender')->unique();
        if ($genders->count() > 1) {
            return back()->withErrors(['categories' => 'All selected categories must belong to the same gender.'])->withInput();
        }

        $slug = Str::slug($request->name);
        $count = Product::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        $data = $request->except(['categories']);
        $data['slug'] = $slug;
        $data['status'] = $request->has('status');

        $product = Product::create($data);
        $product->categories()->sync($request->categories);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::with('parent')->get();
        return view('admin.sections.products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'price' => 'required|numeric',
            'sale_price' => 'nullable|numeric|lt:price',
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => 'exists:categories,id',
            'images' => 'nullable|array',
            'images.*' => 'exists:assets,id',
            'thumbnail_image_id' => 'nullable|exists:assets,id',
            'meta_image_id' => 'nullable|exists:assets,id',
        ], [
            'categories.required' => 'Please select at least one category.',
        ]);

        // Validate gender consistency
        $categories = Category::whereIn('id', $request->categories)->get();
        $genders = $categories->pluck('gender')->unique();
        if ($genders->count() > 1) {
            return back()->withErrors(['categories' => 'All selected categories must belong to the same gender.'])->withInput();
        }

        $slug = Str::slug($request->name);
        if ($slug != $product->slug) {
             $count = Product::where('slug', $slug)->where('id', '!=', $product->id)->count();
             if ($count > 0) {
                 $slug = $slug . '-' . ($count + 1);
             }
        }

        $data = $request->except(['categories']);
        $data['slug'] = $slug;
        $data['status'] = $request->has('status');

        // Handle images if not present in request (clearing them)
        if (!$request->has('images')) {
            $data['images'] = null;
        }
        if (!$request->has('thumbnail_image_id')) {
            $data['thumbnail_image_id'] = null;
        }
        if (!$request->has('meta_image_id')) {
            $data['meta_image_id'] = null;
        }

        $product->update($data);
        $product->categories()->sync($request->categories);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
