<?php

namespace App\Domains\Geography\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
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

        //
    }
}
