<?php

namespace App\Domains\User\Listeners\User\ActiveCampaign;

use App\Domains\User\Enum\UserType;
use App\Domains\User\Events\User\UserCreated;
use App\Domains\User\Models\ActiveCampaignAccount;
use App\Domains\User\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;

class CreateActiveCampaignAccount implements ShouldQueue
{
    use InteractsWithQueue;

    /** @var \App\Domains\User\Services\ActiveCampaign\ActiveCampaignService */
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
     * @param UserCreated $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        /** @var User $user */
        $user = $event->user;

        if($user->type != UserType::USER) return;


        $first_name = $user->first_name;
        $last_name = $user->last_name;
        $email = $user->email;
        $phone = $user->phone;
        $country_id = $user->country_id;
        $tags = $user->usertags->implode('name', ',');
        $source = $user->source ?? 'EC-Council';
        /** @var ActiveCampaignAccount $response */
        $response = $this->ac->createContact($first_name, $last_name, $email, $phone, $source, $tags,$country_id, json_decode($user->utm_data));
        try {
            $user->update([
                'active_campaign_id' => $response->account_id
            ]);
        } catch (\Exception $e) {
            Log::error("update user with active_campaign_id ". $e->getMessage());
        }

    }
}
