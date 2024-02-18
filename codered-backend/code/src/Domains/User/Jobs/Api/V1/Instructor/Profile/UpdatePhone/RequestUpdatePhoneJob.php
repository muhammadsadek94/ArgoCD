<?php

namespace App\Domains\User\Jobs\Api\V1\Instructor\Profile\UpdatePhone;

use App\Domains\User\Notifications\UpdateEmailOTPNotifications;
use INTCore\OneARTFoundation\Job;

class RequestUpdatePhoneJob extends Job
{
    private $phone;
    private $auth;

    /**
     * Create a new job instance.
     *
     * @param $auth
     * @param $phone
     */
    public function __construct($auth, $phone)
    {
        $this->auth = $auth;
        $this->phone = $phone;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $temp_code = env('APP_DEBUG') ? 123456 : rand(100000,900000);

        $this->auth->update(['temp_phone_code' => $temp_code]);

        $this->sendNotification($temp_code);

    }

    /**
     * Send notification with OTP
     * @param $code
     */
    private function sendNotification($code)
    {
        $this->auth->notify(new UpdateEmailOTPNotifications($code, $this->auth->email));
        // $this->auth->notify(new UpdatePhoneOTPNotifications($code, $this->phone));

    }
}
