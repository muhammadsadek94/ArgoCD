<?php

namespace App\Domains\Enterprise\Jobs\Api\V1;

use App\Domains\User\Enum\UserActivation;
use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Job;

class DeactivateUsersJob extends Job
{
    private $request;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request =$request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        User::whereIn('id',$this->request->user_id)->update(["activation" => UserActivation::SUSPEND]);
    }
}
