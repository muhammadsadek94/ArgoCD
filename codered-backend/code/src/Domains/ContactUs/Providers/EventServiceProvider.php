<?php

namespace App\Domains\ContactUs\Providers;

use App\Domains\ContactUs\Events\NewInquiry;
use App\Domains\ContactUs\Listeners\ActiveCampaign\SendInquiry;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [

        NewInquiry::class => [
            SendInquiry::class,
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
