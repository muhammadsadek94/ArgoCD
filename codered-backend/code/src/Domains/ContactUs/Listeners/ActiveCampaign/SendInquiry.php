<?php

namespace App\Domains\ContactUs\Listeners\ActiveCampaign;

use App\Domains\ContactUs\Events\NewInquiry;
use App\Domains\ContactUs\Models\ContactUs;
use App\Domains\User\Models\ActiveCampaignAccount;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendInquiry implements ShouldQueue
{
    use InteractsWithQueue;

    /** @var \App\Domains\User\Services\ActiveCampaign\ActiveCampaignService  */
    private $ac;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ac = app('ac');
    }

    /**
     * Handle the event.
     *
     * @param NewInquiry $event
     * @return void
     */
    public function handle(NewInquiry $event)
    {
        $contact_us = $event->contact_us;

        /** @var ActiveCampaignAccount $response */
        $response = $this->ac->submitContactUs($contact_us);


    }
}
