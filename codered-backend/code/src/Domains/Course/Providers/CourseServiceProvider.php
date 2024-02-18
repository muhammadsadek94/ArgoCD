<?php

namespace App\Domains\Course\Providers;

use View;
use Lang;
use Brightcove\API\DI;
use Brightcove\API\CMS;
use Brightcove\API\Client;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Support\ServiceProvider;
use App\Domains\Course\Providers\RouteServiceProvider;
use Illuminate\Translation\TranslationServiceProvider;

class CourseServiceProvider extends ServiceProvider
{

    private $configPath = __DIR__ . '/../config/course.php';

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
                realpath(__DIR__ . '/../database/migrations')
            ]);

        $this->app->make(EloquentFactory::class)
            ->load(realpath(__DIR__ . '/../database/factories'));
    }

    /**
    * Register the Course service provider.
    *
    * @return void
    */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);

        $this->app->register(AuthServiceProvider::class);

        $this->app->register(RepositoryServiceProvider::class);

        $this->app->register(EventServiceProvider::class);

        $this->registerResources();

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
            'Course'
        );

        # Add config file to service provider publish command
        $this->publishes([
            $this->configPath => config_path('course.php'),
        ], 'config');

        # Translation must be registered ahead of adding lang namespaces
        $this->app->register(TranslationServiceProvider::class);

        Lang::addNamespace('course', realpath(__DIR__.'/../resources/lang'));

        // View::addNamespace('course', base_path('resources/views/vendor/course'));
        View::addNamespace('course', realpath(__DIR__.'/../resources/views'));
    }
}
