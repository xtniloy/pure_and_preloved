<?php

namespace App\Providers;

use App\Models\Admin;
use App\Support\AdminAccess;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // A Super Admin bypasses every permission check.
        Gate::before(function ($user, $ability) {
            if ($user instanceof Admin && $user->hasRole(AdminAccess::SUPER_ADMIN)) {
                return true;
            }

            return null; // fall through to normal checks
        });
    }
}
