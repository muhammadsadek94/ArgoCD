<?php

namespace App\Domains\Brightcove;

use App\Domains\Brightcove\API\DI;
use App\Domains\Brightcove\API\CMS;
use App\Domains\Brightcove\API\Client;
use Illuminate\Support\ServiceProvider;

class BrightcoveServiceProvider extends ServiceProvider
{

    private $configPath = __DIR__ . '/config/brightcove.php';

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

    }

    /**
    * Register the Course service provider.
    *
    * @return void
    */
    public function register()
    {
        # Merge application and packages configurations
        $this->mergeConfigFrom(
            $this->configPath,
            'Brightcove'
        );

        # Add config file to service provider publish command
        $this->publishes([
            $this->configPath => config_path('brightcove.php'),
        ], 'config');

        $this->app->bind(CMS::class, function($app) {
            $secret = config('brightcove.brightcove.secret');
            $account = config('brightcove.brightcove.account_id');
            $client_id = config('brightcove.brightcove.client_id');
            $client = Client::authorize($client_id, $secret);
            return new CMS($client, $account);
        });

        $this->app->bind(DI::class, function($app) {
            $secret = config('brightcove.brightcove.secret');
            $account = config('brightcove.brightcove.account_id');
            $client_id = config('brightcove.brightcove.client_id');
            $client = Client::authorize($client_id, $secret);
            return new DI($client, $account);
        });


    }

}
