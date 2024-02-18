<?php

namespace App\Domains\OpenApi\Providers;

use View;
use Lang;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Support\ServiceProvider;
use App\Domains\OpenApi\Providers\RouteServiceProvider;
use Illuminate\Translation\TranslationServiceProvider;
use Laravel\Passport\Passport;

class OpenApiServiceProvider extends ServiceProvider
{

    private $configPath = __DIR__ . '/../config/openapi.php';

    /**
     * Bootstrap migrations and factories for:
     * - `php artisan migrate` command.
     * - factory() helper.
     *
     * Previous usage:
     * php artisan migrate --path=src/Domains/OpenApi/database/migrations
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

        Passport::tokensExpireIn(now()->addHour());
        Passport::refreshTokensExpireIn(now()->addHours(2));
    }

    /**
     * Register the OpenApi service provider.
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
     * Register the OpenApi service resource namespaces.
     *
     * @return void
     */
    protected function registerResources()
    {
        # Merge application and packages configurations
        $this->mergeConfigFrom(
            $this->configPath,
            'OpenApi'
        );

        # Add config file to service provider publish command
        $this->publishes([
            $this->configPath => config_path('OpenApi.php'),
        ], 'config');

        # Translation must be registered ahead of adding lang namespaces
        $this->app->register(TranslationServiceProvider::class);

        Lang::addNamespace('open_api', realpath(__DIR__ . '/../resources/lang'));

        // View::addNamespace('open_api', base_path('resources/views/vendor/open_api'));
        View::addNamespace('open_api', realpath(__DIR__ . '/../resources/views'));
    }
}
