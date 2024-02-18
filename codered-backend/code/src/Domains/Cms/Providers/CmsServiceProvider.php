<?php

namespace App\Domains\Cms\Providers;

use View;
use Lang;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Support\ServiceProvider;
use App\Domains\Cms\Providers\RouteServiceProvider;
use Illuminate\Translation\TranslationServiceProvider;

class CmsServiceProvider extends ServiceProvider
{

    private $configPath = __DIR__ . '/../config/cms.php';

    /**
     * Bootstrap migrations and factories for:
     * - `php artisan migrate` command.
     * - factory() helper.
     *
     * Previous usage:
     * php artisan migrate --path=src/Domains/Cms/database/migrations
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
    * Register the Cms service provider.
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
     * Register the Cms service resource namespaces.
     *
     * @return void
     */
    protected function registerResources()
    {
        # Merge application and packages configurations
        $this->mergeConfigFrom(
            $this->configPath,
            'Cms'
        );

        # Add config file to service provider publish command
        $this->publishes([
            $this->configPath => config_path('cms.php'),
        ], 'config');

        # Translation must be registered ahead of adding lang namespaces
        $this->app->register(TranslationServiceProvider::class);

        Lang::addNamespace('cms', realpath(__DIR__.'/../resources/lang'));

        // View::addNamespace('cms', base_path('resources/views/vendor/cms'));
        View::addNamespace('cms', realpath(__DIR__.'/../resources/views'));
    }
}
