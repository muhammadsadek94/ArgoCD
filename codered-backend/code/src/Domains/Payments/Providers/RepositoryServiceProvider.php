<?php

namespace App\Domains\Payments\Providers;

use App\Domains\Payments\Repositories\LearnPathInfoRepository;
use App\Domains\Payments\Repositories\LearnPathInfoRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Domains\Payments\Repositories\PackageSubscriptionRepository;
use App\Domains\Payments\Repositories\PackageSubscriptionRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PackageSubscriptionRepositoryInterface::class, PackageSubscriptionRepository::class);
        $this->app->bind(LearnPathInfoRepositoryInterface::class, LearnPathInfoRepository::class);
    }
}
