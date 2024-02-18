<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\User\Auth;

use Illuminate\Support\Facades\Auth;
use INTCore\OneARTFoundation\Job;

class LogoutUserJob extends Job
{
    protected $token;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return $this->token->revoke();
    }
}
