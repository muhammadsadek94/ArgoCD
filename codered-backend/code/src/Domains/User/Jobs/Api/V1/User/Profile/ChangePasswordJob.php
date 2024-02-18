<?php

namespace App\Domains\User\Jobs\Api\V1\User\Profile;

use App\Domains\User\Events\User\PasswordUpdated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use INTCore\OneARTFoundation\Job;

class ChangePasswordJob extends Job
{
    public $request;
    public $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request, $user)
    {
        $this->request = $request;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->user;

        if ($user->has_password){
            if (!Hash::check($this->request->old_password, $user->password)) return false;
            if (Hash::check($this->request->password, $user->password)) return false;

        }
        $device_name = $this->request->get('device_name', 'Login via email & password');
        PasswordUpdated::dispatch($user);

        $user->update(["password" => $this->request->password]);
        $token = $user->createToken($device_name)->accessToken;
        return $token;

    }

}
