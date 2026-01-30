<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('public.layouts.nav.header', function ($view) {
            $manCategories = Category::where('gender', 'man')
                ->whereNull('parent_id')
                ->where('status', true)
                ->orderBy('sort_order', 'asc')
                ->with(['children' => function($query) {
                    $query->orderBy('sort_order', 'asc');
                }, 'children.asset', 'asset'])
                ->get();

            $womenCategories = Category::where('gender', 'women')
                ->whereNull('parent_id')
                ->where('status', true)
                ->orderBy('sort_order', 'asc')
                ->with(['children' => function($query) {
                    $query->orderBy('sort_order', 'asc');
                }, 'children.asset', 'asset'])
                ->get();

            $activeGender = request('gender', 'women');

            $view->with(compact('manCategories', 'womenCategories', 'activeGender'));
        });

        View::composer('public.layouts.nav.mobile_off_canvas', function ($view) {
            $manCategories = Category::where('gender', 'man')
                ->whereNull('parent_id')
                ->where('status', true)
                ->orderBy('sort_order', 'asc')
                ->with(['children' => function($query) {
                    $query->orderBy('sort_order', 'asc');
                }])
                ->get();

            $womenCategories = Category::where('gender', 'women')
                ->whereNull('parent_id')
                ->where('status', true)
                ->orderBy('sort_order', 'asc')
                ->with(['children' => function($query) {
                    $query->orderBy('sort_order', 'asc');
                }])
                ->get();

            $activeGender = request('gender', 'women');

            $view->with(compact('manCategories', 'womenCategories', 'activeGender'));
        });

        Paginator::useBootstrap();
    }
}
