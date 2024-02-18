<?php

namespace App\Domains\UserActivity\Providers;

use App\Domains\UserActivity\Console\UserActivityDelete;
use App\Domains\UserActivity\Console\UserActivityInstall;
use View;
use Lang;
use Illuminate\Support\ServiceProvider;
use Illuminate\Translation\TranslationServiceProvider;

class UserActivityServiceProvider extends ServiceProvider
{

    private $configPath = __DIR__ . '/../config/user-activity.php';
    private $view_path = __DIR__ . '/../views';
    private $asset_path = __DIR__ . '/../assets';
    private $migration_path = __DIR__ . '/../migrations';

    /**
     * Bootstrap migrations and factories for:
     * - `php artisan migrate` command.
     * - factory() helper.
     *
     * Previous usage:
     * php artisan migrate --path=src/Domains/Course/database/migrations
     * Now:
     * php artisan migrate
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom([
            realpath($this->migration_path)
        ]);

    }

    /**
     * Register the Course service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->app->register(EventServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);

        $this->registerResources();

        $this->commands([UserActivityInstall::class]);


    }

    /**
     * Register the Course service resource namespaces.
     *
     * @return void
     */
    protected function registerResources()
    {
        # Merge application and packages configurations
        $this->mergeConfigFrom(
            $this->configPath,
            'UserActivity'
        );

        # Add config file to service provider publish command
        $this->publishes([
            $this->configPath => config_path('user-activity.php'),
        ], 'config');

        View::addNamespace('UserActivity', realpath($this->view_path));
    }
}
