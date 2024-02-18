<?php

namespace App\Domains\User\Jobs\Api\V1\User\Profile;

use App\Domains\User\Events\User\PasswordUpdated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use INTCore\OneARTFoundation\Job;

class CheckPasswordJob extends Job
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

        if(!empty($user->social_id)) return true;

        if ($user->has_password)
            if (!Hash::check($this->request->password, $user->password)) return false;
//        foreach ($user->tokens as $token ){
//            $token->revoke();
//        }

        return true;

    }

}
