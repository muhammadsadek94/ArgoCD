<?php

namespace App\Domains\Workshop\Providers;

use App\Domains\Workshop\Repositories\Interfaces\WorkshopRepositoryInterface;
use App\Domains\Workshop\Repositories\WorkshopRepository;
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
        $this->app->bind(WorkshopRepositoryInterface::class, WorkshopRepository::class);
    }
}
