<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Rules\AvailableSlug;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::latest()->paginate(10);
        return view('admin.sections.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.sections.pages.form');
    }

    public function store(Request $request)
    {
        // Derive the effective slug from the slug field (or title) so we can
        // validate the final URL for conflicts.
        $request->merge([
            'slug' => Str::slug($request->filled('slug') ? $request->slug : $request->title),
        ]);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', new AvailableSlug()],
            'body' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'nullable|boolean',
        ]);

        $data['status'] = $request->has('status') ? 1 : 0;

        Page::create($data);

        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully.');
    }

    public function edit(Page $page)
    {
        return view('admin.sections.pages.form', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $request->merge([
            'slug' => Str::slug($request->filled('slug') ? $request->slug : $request->title),
        ]);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', new AvailableSlug($page->id)],
            'body' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'nullable|boolean',
        ]);

        $data['status'] = $request->has('status') ? 1 : 0;

        $page->update($data);

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Page deleted successfully.');
    }
}
