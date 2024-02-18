<?php

namespace App\Domains\Faq\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domains\Faq\Repositories\FaqRepository;
use App\Domains\Faq\Repositories\FaqRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(FaqRepositoryInterface::class, FaqRepository::class);
    }
}
