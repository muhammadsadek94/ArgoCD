<?php

namespace App\Domains\User\Jobs\Api\V1\User\Profile\UpdatePhone;

use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Job;

class UpdatePhoneJob extends Job
{
    private $user;
    private $temp_code;
    private $phone;

    /**
     * Create a new job instance.
     *
     * @param $user
     * @param $temp_code
     * @param $phone
     */
    public function __construct(User $user, $temp_code, $phone)
    {
        $this->user = $user;
        $this->temp_code = $temp_code;
        $this->phone = $phone;
    }

    /**
     * Execute the job.
     *
     * @return User
     */
    public function handle()
    {
        $this->user->update([
            'temp_phone_code' => null,
            'phone' => $this->phone
        ]);

        return $this->user;
    }
}
