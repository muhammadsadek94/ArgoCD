<?php

namespace Framework\Providers;

use Laravel\Telescope\EntryType;
use App\Domains\Admin\Models\Admin;
use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;


class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Telescope::night(); // enable dark mode

        $this->hideSensitiveRequestDetails();


        Telescope::filter(function(IncomingEntry $entry) {

            if($this->app->environment('local')) {
                return true;
            }

            return $entry->isReportableException() ||
                $entry->isFailedRequest() ||
                $entry->isFailedJob() ||
                $entry->isScheduledTask() ||
                $entry->hasMonitoredTag() ||
                $this->allowedMentoring($entry);
        });

        Telescope::avatar(function($id) {
            return Admin::find($id)->image->full_url ?? '';
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->authorization();
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     *
     * @return void
     */
    protected function hideSensitiveRequestDetails()
    {
        if($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Configure the Telescope authorization services.
     *
     * @return void
     */
    protected function authorization()
    {
        $this->gate();


        Telescope::auth(function($request) {

            return app()->environment('local') ||
                $request->user('admin')->can('viewTelescope');
        });

    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        //TODO: Admin in permission system
        Gate::define('viewTelescope', function($user) {
            // Add admin emails
            return in_array($user->email, [
                'admin@admin.com',
                'developer@intcore.com',
                'developer@admin.com'
            ]);
        });

    }

    private function allowedMentoring(IncomingEntry $entry)
    {
        $allowed_logs = [
            EntryType::COMMAND,
            EntryType::EXCEPTION,
            EntryType::MAIL,
            EntryType::NOTIFICATION,
            EntryType::DUMP,
            EntryType::SCHEDULED_TASK,
            EntryType::LOG,
            EntryType::CACHE,
            EntryType::REDIS,

        ];
        return in_array($entry->type, $allowed_logs);
    }
}
