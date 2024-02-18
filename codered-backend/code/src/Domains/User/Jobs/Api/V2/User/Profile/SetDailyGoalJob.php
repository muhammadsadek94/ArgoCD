<?php

namespace App\Domains\User\Jobs\Api\V2\User\Profile;

use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Job;

class SetDailyGoalJob extends Job
{
    /**
     * @var User
     */
    private $user;
    private $target;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param $target
     */
    public function __construct(User $user, $target)
    {
        $this->user = $user;
        $this->target = $target;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->user->update([
            'daily_target' => $this->target
        ]);
    }
}
