<?php

namespace App\Domains\User\Providers;

use View;
use Lang;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Support\ServiceProvider;
use App\Domains\User\Providers\RouteServiceProvider;
use Illuminate\Translation\TranslationServiceProvider;
use Laravel\Passport\Passport;

class UserServiceProvider extends ServiceProvider
{
    private $configPath = __DIR__ . '/../config/user.php';

    /**
     * Bootstrap migrations and factories for:
     * - `php artisan migrate` command.
     * - factory() helper.
     *
     * Previous usage:
     * php artisan migrate --path=src/Services/User/database/migrations
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

        $this->app->bind('ac', function($app) {
            return new \App\Domains\User\Services\ActiveCampaign\ActiveCampaignService($app['config']['activecampaign']);
        });
    }

    /**
    * Register the User service provider.
    *
    * @return void
    */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);

        $this->app->register(AuthServiceProvider::class);

        $this->app->register(EventServiceProvider::class);

        $this->registerResources();

        Passport::ignoreMigrations();
    }

    /**
     * Register the User service resource namespaces.
     *
     * @return void
     */
    protected function registerResources()
    {
        $this->mergeConfigFrom(
            $this->configPath,
            'User'
        );

        # Add config file to service provider publish command
        $this->publishes([
            $this->configPath => config_path('user.php'),
        ], 'config');


        // Translation must be registered ahead of adding lang namespaces
        $this->app->register(TranslationServiceProvider::class);

        Lang::addNamespace('user', realpath(__DIR__.'/../resources/lang'));

        // View::addNamespace('user', base_path('resources/views/vendor/user'));
        View::addNamespace('user', realpath(__DIR__.'/../resources/views'));
    }
}
