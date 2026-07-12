<?php

namespace App\Providers;

use App\Support\MenuCache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;

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
        Schema::defaultStringLength(191);

        // Both nav views share one cached category payload (MenuCache) instead of
        // querying the tree twice per request; only $activeGender stays per-request.
        View::composer(
            ['public.layouts.nav.header', 'public.layouts.nav.mobile_off_canvas'],
            function ($view) {
                $categories = MenuCache::categories();

                $view->with([
                    'manCategories' => $categories['man'],
                    'womenCategories' => $categories['women'],
                    'activeGender' => request('gender', 'women'),
                ]);
            }
        );

        Paginator::useBootstrap();
    }
}
