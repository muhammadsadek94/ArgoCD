<?php

namespace App\Domains\User\Jobs\Api\V1\User\Profile;

use Illuminate\Http\Request;
use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Job;

class UserDetailsJob extends Job
{
    protected $request;

    /**
     * Create a new job instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() :User
    {
        $user = $this->request->user();
        return $user;
    }
}
