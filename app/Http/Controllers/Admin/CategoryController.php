<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')->latest()->paginate(10);
        return view('admin.sections.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::all();
        return view('admin.sections.categories.form', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'gender' => 'required|in:man,women,unisex',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $slug = Str::slug($request->name);
        $count = Category::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        Category::create([
            'name' => $request->name,
            'slug' => $slug,
            'parent_id' => $request->parent_id,
            'gender' => $request->gender,
            'image' => $imagePath,
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $slug = Str::slug($request->name);
        if ($slug != $category->slug) {
             $count = Category::where('slug', $slug)->where('id', '!=', $category->id)->count();
             if ($count > 0) {
                 $slug = $slug . '-' . ($count + 1);
             }
             $category->slug = $slug;
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
            $category->image = $imagePath;
        }

        $category->name = $request->name;
        $category->parent_id = $request->parent_id;
        $category->gender = $request->gender;
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
