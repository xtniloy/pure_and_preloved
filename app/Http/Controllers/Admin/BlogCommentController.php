<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogComment;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogCommentController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogComment::with(['user', 'post']);

        if ($postId = $request->input('post')) {
            $query->where('blog_post_id', $postId);
        }

        if ($request->input('status') === 'visible') {
            $query->where('status', true);
        } elseif ($request->input('status') === 'hidden') {
            $query->where('status', false);
        }

        $comments = $query->latest()->paginate(15)->withQueryString();
        $posts = BlogPost::orderBy('title')->get(['id', 'title']);

        return view('admin.sections.blog.comments.index', compact('comments', 'posts'));
    }

    public function toggle(BlogComment $blogComment)
    {
        $blogComment->update(['status' => !$blogComment->status]);

        return back()->with('success', $blogComment->status ? 'Comment is now visible.' : 'Comment hidden from the site.');
    }

    public function destroy(BlogComment $blogComment)
    {
        $blogComment->delete();

        return back()->with('success', 'Comment deleted successfully.');
    }
}
