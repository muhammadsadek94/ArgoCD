<?php

namespace App\Domains\Favourite\Providers;

use App\Domains\Favourite\Enum\FavouriteType;
use View;
use Lang;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Support\ServiceProvider;
use App\Domains\Favourite\Providers\RouteServiceProvider;
use Illuminate\Translation\TranslationServiceProvider;

class FavouriteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap migrations and factories for:
     * - `php artisan migrate` command.
     * - factory() helper.
     *
     * Previous usage:
     * php artisan migrate --path=src/Services/Favourite/database/migrations
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


        FavouriteType::buildMorph();
    }

    /**
    * Register the Favourite service provider.
    *
    * @return void
    */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);

        $this->app->register(AuthServiceProvider::class);

        $this->registerResources();
    }

    /**
     * Register the Favourite service resource namespaces.
     *
     * @return void
     */
    protected function registerResources()
    {
        // Translation must be registered ahead of adding lang namespaces
        $this->app->register(TranslationServiceProvider::class);

        Lang::addNamespace('favourite', realpath(__DIR__.'/../resources/lang'));

        // View::addNamespace('favourite', base_path('resources/views/vendor/favourite'));
        View::addNamespace('favourite', realpath(__DIR__.'/../resources/views'));
    }
}
