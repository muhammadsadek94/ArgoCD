<?php

namespace App\Domains\User\Listeners\User\ActiveCampaign;

use App\Domains\User\Enum\ExperienceLevels;
use App\Domains\User\Events\User\UserUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;

class UpdateActiveCampaignAccount implements ShouldQueue
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
     * @param UserUpdated $event
     * @return void
     */
    public function handle(UserUpdated $event)
    {
        $user = $event->user;
        $first_name = $user->first_name;
        $last_name = $user->last_name;
        $email = $user->email;
        $phone = $user->phone;

        $user_tags = $user->usertags->implode('name', ',');
        $interested_categories = $user->categories->implode('name', ',');
        $interested_tags = $user->tags->implode('name', ',');
        $goals = $user->goals->implode('name', ',');
        $level_experience = $this->getLevels($user->level_experience);
        $source = $user->source ?? 'codered';

        $response = $this->ac->editContact($user->active_campaign_id, $first_name, $last_name, $email, $phone,
            $user_tags, $interested_categories, $interested_tags, $level_experience, $goals, $source);
    }

    private function getLevels($level_experience)
    {
        $data = [
            ExperienceLevels::BEGINNER     => 'beginner',
            ExperienceLevels::INTERMEDIATE => 'intermediate',
            ExperienceLevels::ADVANCED     => 'advanced',
        ];
        return @$data[$level_experience];
    }
}
