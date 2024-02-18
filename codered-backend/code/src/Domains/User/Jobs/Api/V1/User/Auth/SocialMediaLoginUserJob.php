<?php

namespace App\Domains\User\Jobs\Api\V1\User\Auth;

use App\Domains\User\Models\User;
use App\Foundation\Repositories\Repository;
use Illuminate\Support\Facades\Auth;
use INTCore\OneARTFoundation\Job;

class SocialMediaLoginUserJob extends Job
{
    protected $request;

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
     * @return User|null
     */
    public function handle() : ?User
    {
        $auth = User::where("social_type", $this->request->social_type)
                    ->where("social_id", $this->request->social_id)
                    ->first();
        if($auth){
            $device_name = $this->request->get('device_name', 'Login via email & password');

            $auth->access_token =  $auth->createToken($device_name);
            return $auth;
        }
        return null;
    }
}
