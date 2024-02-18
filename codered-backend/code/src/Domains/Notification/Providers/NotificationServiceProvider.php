<?php

namespace App\Domains\Notification\Providers;

use App\Domains\Notification\Enum\NotificationType;
use View;
use Lang;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Support\ServiceProvider;
use Illuminate\Translation\TranslationServiceProvider;
use App\Domains\Notification\Http\View\Composers\NotificationsComposer;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap migrations and factories for:
     * - `php artisan migrate` command.
     * - factory() helper.
     *
     * Previous usage:
     * php artisan migrate --path=src/Services/Notification/database/migrations
     * Now:
     * php artisan migrate
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot()
    {
        $this->loadMigrationsFrom([
            realpath(__DIR__ . '/../database/migrations')
        ]);

        $this->app->make(EloquentFactory::class)
            ->load(realpath(__DIR__ . '/../database/factories'));

        $this->bootServices();

    }

    public function bootServices()
    {
        NotificationType::buildMorph();

        View::composer(
            'provider.layouts.topbar', NotificationsComposer::class
        );

    }

    /**
     * Register the Notification service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);

        $this->app->register(AuthServiceProvider::class);

        $this->app->register(NotificationServiceProviderRegistery::class);

        $this->registerResources();
    }

    /**
     * Register the Notification service resource namespaces.
     *
     * @return void
     */
    protected function registerResources()
    {
        // Translation must be registered ahead of adding lang namespaces
        $this->app->register(TranslationServiceProvider::class);

        Lang::addNamespace('notification', realpath(__DIR__ . '/../resources/lang'));

        // View::addNamespace('notification', base_path('resources/views/vendor/notification'));
        View::addNamespace('notification', realpath(__DIR__ . '/../resources/views'));
    }
}
