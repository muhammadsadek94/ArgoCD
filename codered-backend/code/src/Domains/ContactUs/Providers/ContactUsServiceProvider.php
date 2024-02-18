<?php

namespace App\Domains\ContactUs\Providers;

use View;
use Lang;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Support\ServiceProvider;
use App\Domains\ContactUs\Providers\RouteServiceProvider;
use Illuminate\Translation\TranslationServiceProvider;

class ContactUsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap migrations and factories for:
     * - `php artisan migrate` command.
     * - factory() helper.
     *
     * Previous usage:
     * php artisan migrate --path=src/Services/ContactUs/database/migrations
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
    * Register the ContactUs service provider.
    *
    * @return void
    */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);

        $this->app->register(AuthServiceProvider::class);

        $this->app->register(EventServiceProvider::class);

        $this->registerResources();
    }

    /**
     * Register the ContactUs service resource namespaces.
     *
     * @return void
     */
    protected function registerResources()
    {
        // Translation must be registered ahead of adding lang namespaces
        $this->app->register(TranslationServiceProvider::class);

        Lang::addNamespace('contact_us', realpath(__DIR__.'/../resources/lang'));

        // View::addNamespace('contact_us', base_path('resources/views/vendor/contact_us'));
        View::addNamespace('contact_us', realpath(__DIR__.'/../resources/views'));
    }
}
