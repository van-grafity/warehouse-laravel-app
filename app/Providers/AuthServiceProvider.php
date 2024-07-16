<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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
        Gate::before(function (User $user, $ability) {
           if ($user->hasRole('developer')) {
               return true;
           }
        });

        Gate::define('viewLogViewer', function (?User $user) {
            if ($user && $user->hasRole('developer')) {
               return true;
            }
        });
        
        Gate::define('developer-menu', function (User $user) {
            if ($user->hasRole('developer')) {
                return true;
            }
        });

        Gate::define('admin-menu', function (User $user) {
            $permitted_roles = [
                'admin',
            ];
            if ($user->hasRole($permitted_roles)) { return true; }
        });

        // !!  next nya cara ini mungkin akan di gantikan
        // Gate::define('user-menu', function (User $user) {
        //     $permitted_roles = [
        //         'admin',
        //         'user',
        //         'warehouse-supervisor',
        //         'warehouse-clerk',
        //         'fg-warehouse',
        //         'fg-warehouse-clerk',
        //     ];
        //     if ($user->hasRole($permitted_roles)) { return true; }
        // });
    }
}
