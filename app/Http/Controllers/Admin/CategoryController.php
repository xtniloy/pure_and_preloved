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

        $slug = $this->generateUniqueSlug($request->name, $request->parent_id);

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

        if ($request->name != $category->name || $request->parent_id != $category->parent_id) {
            $category->slug = $this->generateUniqueSlug($request->name, $request->parent_id, $category->id);
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

    private function generateUniqueSlug($name, $parentId = null, $excludeId = null, $step = 1, $counter = 1)
    {
        $baseSlug = Str::slug($name);

        if ($step === 1) {
            if ($this->isSlugUnique($baseSlug, $excludeId)) {
                return $baseSlug;
            }
            return $this->generateUniqueSlug($name, $parentId, $excludeId, 2);
        }

        if ($step === 2) {
            if ($parentId) {
                $parent = Category::find($parentId);
                if ($parent) {
                    $prefixedSlug = Str::slug($parent->slug . '-' . $name);
                    if ($this->isSlugUnique($prefixedSlug, $excludeId)) {
                        return $prefixedSlug;
                    }
                    return $this->generateUniqueSlug($name, $parentId, $excludeId, 3, 1);
                }
            }
            return $this->generateUniqueSlug($name, $parentId, $excludeId, 3, 1);
        }

        // Step 3: Numbering
        $base = $baseSlug;
        if ($parentId) {
            $parent = Category::find($parentId);
            if ($parent) {
                $base = Str::slug($parent->slug . '-' . $name);
            }
        }

        $slugWithNumber = $base . '-' . $counter;
        if ($this->isSlugUnique($slugWithNumber, $excludeId)) {
            return $slugWithNumber;
        }

        return $this->generateUniqueSlug($name, $parentId, $excludeId, 3, $counter + 1);
    }

    private function isSlugUnique($slug, $excludeId = null)
    {
        $query = Category::where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        return $query->count() === 0;
    }
}
