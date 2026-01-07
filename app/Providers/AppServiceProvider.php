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
                ->with('children')
                ->get();

            $womenCategories = Category::where('gender', 'women')
                ->whereNull('parent_id')
                ->where('status', true)
                ->with('children')
                ->get();

            $activeGender = request('gender', 'women');

            $view->with(compact('manCategories', 'womenCategories', 'activeGender'));
        });

        View::composer('public.layouts.nav.mobile_off_canvas', function ($view) {
            $manCategories = Category::where('gender', 'man')
                ->whereNull('parent_id')
                ->where('status', true)
                ->with('children')
                ->get();

            $womenCategories = Category::where('gender', 'women')
                ->whereNull('parent_id')
                ->where('status', true)
                ->with('children')
                ->get();

            $activeGender = request('gender', 'women');

            $view->with(compact('manCategories', 'womenCategories', 'activeGender'));
        });

        Paginator::useBootstrap();
    }
}
