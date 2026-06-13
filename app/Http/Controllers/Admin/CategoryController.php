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
        // Drill into the children of a specific category.
        if ($request->filled('parent_id')) {
            $parent = Category::with('parent')->findOrFail($request->parent_id);

            $categories = Category::with(['parent', 'asset'])
                ->withCount('children')
                ->where('parent_id', $parent->id)
                ->orderBy('sort_order', 'asc')
                ->orderBy('id', 'asc')
                ->paginate(30);

            return view('admin.sections.categories.index', [
                'categories' => $categories,
                'parent' => $parent,
                'gender' => $parent->gender,
            ]);
        }

        // Show the root categories for a chosen gender.
        if ($request->filled('gender')) {
            $gender = $request->gender;
            abort_unless(in_array($gender, ['man', 'women', 'unisex'], true), 404);

            $categories = Category::with(['parent', 'asset'])
                ->withCount('children')
                ->whereNull('parent_id')
                ->where('gender', $gender)
                ->orderBy('sort_order', 'asc')
                ->orderBy('id', 'asc')
                ->paginate(30);

            return view('admin.sections.categories.index', [
                'categories' => $categories,
                'parent' => null,
                'gender' => $gender,
            ]);
        }

        // Landing: choose a gender to manage.
        $genders = ['man', 'women'];
        if (Category::where('gender', 'unisex')->exists()) {
            $genders[] = 'unisex';
        }

        $counts = Category::selectRaw('gender, count(*) as total')
            ->groupBy('gender')
            ->pluck('total', 'gender');

        return view('admin.sections.categories.landing', compact('genders', 'counts'));
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

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Order updated successfully.']);
        }

        return back()->with('success', 'Order updated successfully.');
    }

    public function create(Request $request)
    {
        $parents = Category::all();
        $parent_id = $request->query('parent_id');
        $gender = $request->query('gender');

        // When adding under a parent, the gender is dictated by that parent.
        if ($parent_id && $parent = Category::find($parent_id)) {
            $gender = $parent->gender;
        }

        return view('admin.sections.categories.form', compact('parents', 'parent_id', 'gender'));
    }

    /**
     * URL of the list a category belongs to: its parent's children list when
     * nested, otherwise the gender root list.
     */
    private function listUrlFor($parentId, $gender)
    {
        return $parentId
            ? route('admin.categories.index', ['parent_id' => $parentId])
            : route('admin.categories.index', ['gender' => $gender]);
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

        // Append new categories at the end of their list (parent/gender group).
        $sortOrder = (Category::where('parent_id', $request->parent_id)
            ->where('gender', $request->gender)
            ->max('sort_order') ?? 0) + 1;

        Category::create([
            'name' => $request->name,
            'slug' => $slug,
            'parent_id' => $request->parent_id,
            'gender' => $request->gender,
            'asset_id' => $request->asset_id,
            'status' => $request->has('status') ? 1 : 0,
            'sort_order' => $sortOrder,
        ]);

        return redirect($this->listUrlFor($request->parent_id, $request->gender))
            ->with('success', 'Category created successfully.');
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

        return redirect($this->listUrlFor($category->parent_id, $category->gender))
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $redirect = $this->listUrlFor($category->parent_id, $category->gender);
        $category->delete();
        return redirect($redirect)->with('success', 'Category deleted successfully.');
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
