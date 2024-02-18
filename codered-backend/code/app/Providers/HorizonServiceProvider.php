<?php

namespace Framework\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\Horizon;
use Laravel\Horizon\HorizonApplicationServiceProvider;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // Horizon::routeSmsNotificationsTo('15556667777');
        // Horizon::routeMailNotificationsTo('example@example.com');
        // Horizon::routeSlackNotificationsTo('slack-webhook-url', '#channel');

        // Horizon::night();
    }

    /**
     * Configure the Horizon authorization services.
     *
     * @return void
     */
    protected function authorization()
    {
        $this->gate();

        Horizon::auth(function ($request) {

            return app()->environment('local') ||
                Gate::forUser($request->user('admin'))
                    ->allows('viewHorizon', [$request->user('admin')]);

        });
    }

    /**
     * Register the Horizon gate.
     *
     * This gate determines who can access Horizon in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        //TODO: Admin in permission system
        Gate::define('viewHorizon', function ($user) {
            // Add admin emails
            return in_array($user->email, [
                'admin@admin.com',
                'developer@intcore.com',
                'developer@admin.com'
            ]);
        });
    }
}
