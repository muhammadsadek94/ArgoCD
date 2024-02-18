<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\User\Activation;

use App\Domains\User\Models\User;
use Illuminate\Support\Facades\Auth;
use INTCore\OneARTFoundation\Job;
use App\Domains\User\Enum\UserActivation;

class ActivateUserJob extends Job
{
    protected $request;
    /**
     * Create a new job instance.
     *
     * @return void
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
    public function handle() : ?User
    {
        $code = $this->request->code;
        $user = $this->request->user();

        if ($user->activation != UserActivation::ACTIVE && $user->activation == $code){
            $user->update(['activation'=>UserActivation::ACTIVE]);
            return $user;
        }
        return null;
    }
}
