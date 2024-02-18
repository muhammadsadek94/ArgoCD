<?php

namespace App\Domains\User\Listeners\User\ActiveCampaign;

use App\Domains\User\Events\User\UserDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DeleteActiveCampaignAccount implements ShouldQueue
{

    use InteractsWithQueue;

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
     * @param UserDeleted $event
     * @return void
     */
    public function handle(UserDeleted $event)
    {
        /**
         * No need to delete account from the activecampaign database
         * we need to keep them there as per business requirements
         */
        return ;
        $user = $event->user;
        $this->ac->deleteContact($user->active_campaign_id);
    }
}
