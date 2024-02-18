<?php

namespace App\Domains\Blog\Providers;

use App\Domains\Blog\Repositories\ArticleRepository;
use App\Domains\Blog\Repositories\Interfaces\ArticleRepositoryInterface;
use App\Domains\Blog\Repositories\QuotesRepository;
use App\Domains\Blog\Repositories\Interfaces\QuotesRepositoryInterface;
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
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
        
        $this->app->bind(QuotesRepositoryInterface::class, QuotesRepository::class);


    }
}
