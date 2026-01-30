<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::with('parent');

        if ($request->has('parent_id')) {
            $query->where('parent_id', $request->parent_id);
            $parent = Category::find($request->parent_id);
        } else {
            $query->whereNull('parent_id');
            $parent = null;
        }

        $categories = $query->orderBy('sort_order', 'asc')->latest()->paginate(30);
        return view('admin.sections.categories.index', compact('categories', 'parent'));
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:categories,id',
            'categories.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->categories as $categoryData) {
            Category::where('id', $categoryData['id'])->update(['sort_order' => $categoryData['sort_order']]);
        }

        return back()->with('success', 'Order updated successfully.');
    }

    public function create(Request $request)
    {
        $parents = Category::all();
        $parent_id = $request->query('parent_id');
        return view('admin.sections.categories.form', compact('parents', 'parent_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'gender' => 'required|in:man,women,unisex',
            'asset_id' => 'nullable|exists:assets,id',
        ]);

        if ($request->parent_id) {
            $parent = Category::find($request->parent_id);
            if ($parent && $parent->gender !== 'unisex' && $request->gender !== $parent->gender) {
                return back()->withErrors(['gender' => 'Gender must match parent category gender.'])->withInput();
            }
        }

        $slug = Str::slug($request->name);
        $count = Category::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        Category::create([
            'name' => $request->name,
            'slug' => $slug,
            'parent_id' => $request->parent_id,
            'gender' => $request->gender,
            'asset_id' => $request->asset_id,
            'status' => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        $parents = Category::where('id', '!=', $category->id)->get();
        return view('admin.sections.categories.form', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'gender' => 'required|in:man,women,unisex',
            'asset_id' => 'nullable|exists:assets,id',
        ]);

        if ($request->parent_id) {
            $parent = Category::find($request->parent_id);
            if ($parent && $parent->gender !== 'unisex' && $request->gender !== $parent->gender) {
                return back()->withErrors(['gender' => 'Gender must match parent category gender.'])->withInput();
            }
        }

        $slug = Str::slug($request->name);
        if ($slug != $category->slug) {
             $count = Category::where('slug', $slug)->where('id', '!=', $category->id)->count();
             if ($count > 0) {
                 $slug = $slug . '-' . ($count + 1);
             }
             $category->slug = $slug;
        }

        $category->name = $request->name;
        $category->parent_id = $request->parent_id;
        $category->gender = $request->gender;
        $category->asset_id = $request->asset_id;
        $category->status = $request->has('status') ? 1 : 0;
        $category->save();

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
