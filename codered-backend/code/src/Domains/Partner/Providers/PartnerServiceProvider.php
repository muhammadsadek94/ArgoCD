<?php

namespace App\Domains\Partner\Providers;

use View;
use Lang;
/** Using Legacy Eloquent Factory -  more info : laravel/legacy-factories **/
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Support\ServiceProvider;
use App\Domains\Partner\Providers\RouteServiceProvider;
use Illuminate\Translation\TranslationServiceProvider;

class PartnerServiceProvider extends ServiceProvider
{

    private $configPath = __DIR__ . '/../config/partner.php';

    /**
     * Bootstrap migrations and factories for:
     * - `php artisan migrate` command.
     * - factory() helper.
     *
     * Previous usage:
     * php artisan migrate --path=src/Domains/Partner/database/migrations
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
    * Register the Partner service provider.
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
     * Register the Partner service resource namespaces.
     *
     * @return void
     */
    protected function registerResources()
    {
        # Merge application and packages configurations
        $this->mergeConfigFrom(
            $this->configPath,
            'Partner'
        );

        # Add config file to service provider publish command
        $this->publishes([
            $this->configPath => config_path('partner.php'),
        ], 'config');

        # Translation must be registered ahead of adding lang namespaces
        $this->app->register(TranslationServiceProvider::class);

        Lang::addNamespace('partner', realpath(__DIR__.'/../resources/lang'));

        // View::addNamespace('partner', base_path('resources/views/vendor/partner'));
        View::addNamespace('partner', realpath(__DIR__.'/../resources/views'));
    }
}
