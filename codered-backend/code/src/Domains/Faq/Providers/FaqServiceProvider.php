<?php

namespace App\Domains\Faq\Providers;

use View;
use Lang;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Support\ServiceProvider;
use App\Domains\Faq\Providers\RouteServiceProvider;
use Illuminate\Translation\TranslationServiceProvider;

class FaqServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap migrations and factories for:
     * - `php artisan migrate` command.
     * - factory() helper.
     *
     * Previous usage:
     * php artisan migrate --path=src/Services/Faq/database/migrations
     * Now:
     * php artisan migrate
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom([
            realpath(__DIR__ . '/../database/migrations')
        ]);

        $this->app->make(EloquentFactory::class)
            ->load(realpath(__DIR__ . '/../database/factories'));
    }

    /**
    * Register the Faq service provider.
    *
    * @return void
    */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);

        $this->app->register(AuthServiceProvider::class);

        $this->app->register(RepositoryServiceProvider::class);

        $this->registerResources();
    }

    /**
     * Register the Faq service resource namespaces.
     *
     * @return void
     */
    protected function registerResources()
    {
        // Translation must be registered ahead of adding lang namespaces
        $this->app->register(TranslationServiceProvider::class);

        Lang::addNamespace('faq', realpath(__DIR__.'/../resources/lang'));

        // View::addNamespace('faq', base_path('resources/views/vendor/faq'));
        View::addNamespace('faq', realpath(__DIR__.'/../resources/views'));
    }
}
