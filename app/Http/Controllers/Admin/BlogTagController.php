<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogTagController extends Controller
{
    public function index()
    {
        $tags = BlogTag::withCount('posts')->latest()->paginate(15);

        return view('admin.sections.blog.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.sections.blog.tags.form');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        BlogTag::create($data);

        return redirect()->route('admin.blog-tags.index')->with('success', 'Blog tag created successfully.');
    }

    public function edit(BlogTag $blogTag)
    {
        return view('admin.sections.blog.tags.form', ['tag' => $blogTag]);
    }

    public function update(Request $request, BlogTag $blogTag)
    {
        $data = $this->validated($request, $blogTag);

        $blogTag->update($data);

        return redirect()->route('admin.blog-tags.index')->with('success', 'Blog tag updated successfully.');
    }

    public function destroy(BlogTag $blogTag)
    {
        $blogTag->delete();

        return redirect()->route('admin.blog-tags.index')->with('success', 'Blog tag deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?BlogTag $tag = null): array
    {
        $request->merge([
            'slug' => Str::slug($request->filled('slug') ? $request->slug : $request->name),
        ]);

        return $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_tags,slug' . ($tag ? ',' . $tag->id : ''),
        ]);
    }
}
