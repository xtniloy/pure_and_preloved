<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Display a published CMS page by its slug.
     */
    public function show(string $slug): View
    {
        $page = Page::where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        return view('public.home.page', compact('page'));
    }
}
