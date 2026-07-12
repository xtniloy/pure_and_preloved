<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Support\FooterCache;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::with(['categories', 'author', 'featuredImage'])->withCount('comments');

        if ($search = trim((string) $request->input('q'))) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        if ($status = $request->input('status')) {
            if (array_key_exists($status, BlogPost::statuses())) {
                $query->where('status', $status);
            }
        }

        if ($categoryId = $request->input('category')) {
            $query->whereHas('categories', fn ($q) => $q->where('blog_categories.id', $categoryId));
        }

        $posts = $query->latest()->paginate(10)->withQueryString();
        $categories = BlogCategory::orderBy('name')->get();

        return view('admin.sections.blog.posts.index', compact('posts', 'categories'));
    }

    public function create()
    {
        $categories = BlogCategory::orderBy('name')->get();
        $tags = BlogTag::orderBy('name')->get();

        return view('admin.sections.blog.posts.form', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $data['admin_id'] = auth('admin')->id();

        $post = BlogPost::create($data);
        $post->categories()->sync($request->input('categories', []));
        $post->tags()->sync($this->resolveTagIds($request));

        FooterCache::clear();

        return redirect()->route('admin.blog-posts.index')->with('success', 'Blog post created successfully.');
    }

    public function edit(BlogPost $blogPost)
    {
        $blogPost->load(['categories', 'tags']);
        $categories = BlogCategory::orderBy('name')->get();
        $tags = BlogTag::orderBy('name')->get();

        return view('admin.sections.blog.posts.form', ['post' => $blogPost, 'categories' => $categories, 'tags' => $tags]);
    }

    public function update(Request $request, BlogPost $blogPost)
    {
        $data = $this->validated($request, $blogPost);

        // Image pickers submit nothing when cleared.
        if (!$request->has('featured_image_id')) {
            $data['featured_image_id'] = null;
        }
        if (!$request->has('meta_image_id')) {
            $data['meta_image_id'] = null;
        }

        $blogPost->update($data);
        $blogPost->categories()->sync($request->input('categories', []));
        $blogPost->tags()->sync($this->resolveTagIds($request));

        FooterCache::clear();

        return redirect()->route('admin.blog-posts.index')->with('success', 'Blog post updated successfully.');
    }

    public function destroy(BlogPost $blogPost)
    {
        $blogPost->delete();

        FooterCache::clear();

        return redirect()->route('admin.blog-posts.index')->with('success', 'Blog post deleted successfully.');
    }

    /**
     * Shared store/update validation. Returns only the blog_posts columns
     * (categories/tags are synced separately by the caller).
     *
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?BlogPost $post = null): array
    {
        // Derive the effective slug from the slug field (or title) so the
        // final URL is what gets validated for uniqueness.
        $request->merge([
            'slug' => Str::slug($request->filled('slug') ? $request->slug : $request->title),
        ]);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_posts,slug' . ($post ? ',' . $post->id : ''),
            'excerpt' => 'nullable|string|max:500',
            'body' => 'nullable|string',
            'status' => 'required|in:' . implode(',', array_keys(BlogPost::statuses())),
            'published_at' => 'nullable|date',
            'featured_image_id' => 'nullable|exists:assets,id',
            'meta_image_id' => 'nullable|exists:assets,id',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:blog_categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:blog_tags,id',
            'new_tags' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        unset($data['categories'], $data['tags'], $data['new_tags']);

        $data['allow_comments'] = $request->has('allow_comments');

        // Publishing without an explicit date stamps it now (an existing date
        // is kept so re-saving a published post doesn't bump it).
        if ($data['status'] === BlogPost::STATUS_PUBLISHED && empty($data['published_at'])) {
            $data['published_at'] = $post?->published_at ?? now();
        }

        return $data;
    }

    /**
     * Selected tag ids plus any comma-separated "new tags" (created on save).
     *
     * @return array<int, int>
     */
    private function resolveTagIds(Request $request): array
    {
        $ids = collect($request->input('tags', []))->map(fn ($id) => (int) $id);

        foreach (explode(',', (string) $request->input('new_tags')) as $name) {
            $name = trim($name);
            $slug = Str::slug($name);

            if ($name === '' || $slug === '') {
                continue;
            }

            $tag = BlogTag::firstOrCreate(['slug' => $slug], ['name' => $name]);
            $ids->push($tag->id);
        }

        return $ids->unique()->values()->all();
    }
}
