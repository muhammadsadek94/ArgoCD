<?php

namespace App\Domains\User\Jobs\Api\V1\User\Profile;


use App\Domains\User\Models\User;
use App\Domains\User\Models\UserSubscription;
use INTCore\OneARTFoundation\Job;

class DeleteSubscriptionJob extends Job
{

    public $request;

    /**
     * Create a new job instance.
     *
     * @param $request
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $res = UserSubscription::where('package_id',$this->request->id)->where('user_id',$this->request->user_id)->first()->delete();
        return $res;
    }
}
