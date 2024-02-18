<?php

namespace App\Domains\User\Jobs\Api\V1\Instructor\Profile\UpdatePhone;

use INTCore\OneARTFoundation\Job;

class ValidateTempCodeJob extends Job
{
    private $temp_code;
    private $user;

    /**
     * Create a new job instance.
     *
     * @param $user
     * @param $temp_code
     */
    public function __construct($user, $temp_code)
    {
        $this->user = $user;
        $this->temp_code = $temp_code;
    }

    /**
     * Execute the job.
     *
     * @return bool
     */
    public function handle()
    {
        return $this->user->temp_phone_code == $this->temp_code;
    }
}
