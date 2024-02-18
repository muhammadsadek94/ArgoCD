<?php

namespace App\Domains\User\Providers;

use App\Domains\User\Events\User\PasswordUpdated;
use App\Domains\User\Events\User\UserCreated;
use App\Domains\User\Events\User\UserDeleted;
use App\Domains\User\Events\User\UserUpdated;
use App\Domains\User\Listeners\User\ActiveCampaign\CreateActiveCampaignAccount;
use App\Domains\User\Listeners\User\ActiveCampaign\DeleteActiveCampaignAccount;
use App\Domains\User\Listeners\User\ActiveCampaign\UpdateActiveCampaignAccount;
use App\Domains\User\Listeners\User\CyberQ\RegisterUserCyberQ;
use App\Domains\User\Listeners\User\Portal\UpdatePasswordIntegrationListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [

        UserCreated::class => [
            CreateActiveCampaignAccount::class,
            // RegisterUserCyberQ::class
        ],
        UserUpdated::class => [
            UpdateActiveCampaignAccount::class,
        ],
        PasswordUpdated::class => [
            UpdatePasswordIntegrationListener::class
        ],
        UserDeleted::class => [
//            DeleteActiveCampaignAccount::class, // keep users in activecapiagn
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
