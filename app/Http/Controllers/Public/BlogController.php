<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Notifications\BlogCommentReceived;
use App\Services\AdminNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $query = BlogPost::published()->with(['featuredImage', 'author']);

        $activeCategory = null;
        if ($request->filled('category')) {
            $activeCategory = BlogCategory::where('slug', $request->input('category'))
                ->where('status', true)
                ->firstOrFail();
            $query->whereHas('categories', fn ($q) => $q->where('blog_categories.id', $activeCategory->id));
        }

        $activeTag = null;
        if ($request->filled('tag')) {
            $activeTag = BlogTag::where('slug', $request->input('tag'))->firstOrFail();
            $query->whereHas('tags', fn ($q) => $q->where('blog_tags.id', $activeTag->id));
        }

        if ($search = trim((string) $request->input('q'))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('excerpt', 'like', '%' . $search . '%')
                    ->orWhere('body', 'like', '%' . $search . '%');
            });
        }

        $posts = $query->orderByDesc('published_at')->paginate(6)->withQueryString();

        return view('public.blog.index', array_merge(
            compact('posts', 'activeCategory', 'activeTag', 'search'),
            $this->sidebarData()
        ));
    }

    public function show(string $slug): View
    {
        $post = BlogPost::published()
            ->where('slug', $slug)
            ->with(['featuredImage', 'metaImage', 'categories', 'tags', 'author'])
            ->firstOrFail();

        $relatedPosts = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->when(
                $post->categories->isNotEmpty(),
                fn ($q) => $q->whereHas('categories', fn ($qq) => $qq->whereIn('blog_categories.id', $post->categories->pluck('id')))
            )
            ->with(['featuredImage', 'author'])
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        $comments = $post->comments()
            ->visible()
            ->whereNull('parent_id')
            ->with(['user', 'replies' => fn ($q) => $q->visible()->with('user')->orderBy('created_at')])
            ->orderBy('created_at')
            ->get();

        $commentCount = $post->comments()->visible()->count();

        return view('public.blog.show', array_merge(
            compact('post', 'relatedPosts', 'comments', 'commentCount'),
            $this->sidebarData()
        ));
    }

    public function storeComment(Request $request, string $slug, AdminNotificationService $notifier): RedirectResponse
    {
        $post = BlogPost::published()->where('slug', $slug)->firstOrFail();

        if (!$post->allow_comments) {
            return redirect()->route('blog.show', $post->slug)
                ->with('comment_error', 'Comments are closed for this post.');
        }

        $data = $request->validate([
            'body' => 'required|string|min:2|max:2000',
            'parent_id' => 'nullable|integer',
        ]);

        // Replies stay one level deep: replying to a reply attaches to its
        // top-level parent.
        $parentId = null;
        if (!empty($data['parent_id'])) {
            $target = BlogComment::visible()
                ->where('blog_post_id', $post->id)
                ->find($data['parent_id']);

            if (!$target) {
                return redirect()->to(route('blog.show', $post->slug) . '#comments')
                    ->with('comment_error', 'The comment you are replying to no longer exists.');
            }

            $parentId = $target->parent_id ?: $target->id;
        }

        $comment = $post->comments()->create([
            'user_id' => $request->user()->id,
            'parent_id' => $parentId,
            'body' => $data['body'],
            'status' => true,
        ]);

        // Notify admins (email + web) per their preferences. A mail/queue hiccup
        // must not break the visitor's submission, so failures are logged.
        try {
            $notifier->notifyAdmins(new BlogCommentReceived($comment));
        } catch (\Throwable $e) {
            Log::error('Blog comment notification failed: ' . $e->getMessage());
        }

        return redirect()->to(route('blog.show', $post->slug) . '#comments')
            ->with('comment_success', 'Your comment has been posted.');
    }

    /**
     * Data for the blog sidebar (search / categories / recent posts / tags),
     * shared by the list and detail pages.
     *
     * @return array<string, mixed>
     */
    private function sidebarData(): array
    {
        $sidebarCategories = BlogCategory::where('status', true)
            ->withCount(['posts' => fn ($q) => $q->published()])
            ->orderBy('name')
            ->get()
            ->filter(fn ($category) => $category->posts_count > 0)
            ->values();

        $recentPosts = BlogPost::published()
            ->with('featuredImage')
            ->orderByDesc('published_at')
            ->take(4)
            ->get();

        $sidebarTags = BlogTag::withCount(['posts' => fn ($q) => $q->published()])
            ->orderBy('name')
            ->get()
            ->filter(fn ($tag) => $tag->posts_count > 0)
            ->values();

        return compact('sidebarCategories', 'recentPosts', 'sidebarTags');
    }
}
