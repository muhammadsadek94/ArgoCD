<?php

namespace App\Domains\Bundles\Providers;

use App\Domains\Bundles\Repositories\BundlesRepository;
use App\Domains\Bundles\Repositories\Interfaces\BundlesRepositoryInterface;
use App\Domains\Bundles\Repositories\PromoCodeRepository;
use App\Domains\Bundles\Repositories\Interfaces\PromoCodeRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        
        $this->app->bind(BundlesRepositoryInterface::class, BundlesRepository::class);

        $this->app->bind(PromoCodeRepositoryInterface::class, PromoCodeRepository::class);
    }
}
