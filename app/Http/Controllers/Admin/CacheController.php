<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class CacheController extends Controller
{
    /**
     * Wipe the whole application cache: homepage payload, nav menus, footer,
     * social links and file-manager asset lookups. Everything rebuilds
     * lazily on the next page view, so this is always safe to run.
     */
    public function clear()
    {
        Cache::flush();

        return back()->with('success', 'All caches cleared. Pages will rebuild them on the next visit.');
    }
}
