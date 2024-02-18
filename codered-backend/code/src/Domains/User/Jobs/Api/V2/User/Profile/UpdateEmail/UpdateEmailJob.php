<?php

namespace App\Domains\User\Jobs\Api\V2\User\Profile\UpdateEmail;

use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Job;

class UpdateEmailJob extends Job
{
    private $user;
    private $email;

    /**
     * Create a new job instance.
     *
     * @param $user
     * @param $email
     */
    public function __construct(User $user, $email)
    {
        $this->user = $user;
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return User
     */
    public function handle()
    {
        $this->user->update([
            'temp_email_code' => null,
            'email' => $this->email
        ]);

        return $this->user;
    }
}
