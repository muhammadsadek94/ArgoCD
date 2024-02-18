<?php

namespace App\Domains\Notification\Providers;

use App\Domains\Notification\Broadcasting\FcmChannel;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProviderRegistery extends ServiceProvider
{

    /**
     * Register the Notification service provider.
     *
     * @return void
     */
    public function register()
    {
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('fcm', function () {
                return new FcmChannel(app(Client::class), config('services.fcm.key'));
            });
        });

    }


}
