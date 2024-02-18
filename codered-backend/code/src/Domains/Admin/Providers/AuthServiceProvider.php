<?php

namespace App\Domains\Admin\Providers;

use App\Foundation\Traits\HasAuthorization;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;

class AuthServiceProvider extends ServiceProvider
{
    use HasAuthorization;

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // ArticleModel::class => ArticleAccessPolicy::class,
    ];

    /**
     * Register any authentication / authorization provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function($admin, $ability) {
            if(env('APP_ENV') == 'local' || env('APP_ENV') == 'development') {
                if(is_null($admin->role_id)) return true;
            };

            if($admin->is_super_admin == 1) return true;

            if($admin->abilities()->contains($ability)) {
                return true;
            }
        });

        Blade::if('permitted', function($ability) {
            return is_permitted($ability);
        });

    }
}
