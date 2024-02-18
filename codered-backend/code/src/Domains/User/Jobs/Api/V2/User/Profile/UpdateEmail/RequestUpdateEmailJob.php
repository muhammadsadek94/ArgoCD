<?php

namespace App\Domains\User\Jobs\Api\V2\User\Profile\UpdateEmail;

use App\Domains\User\Notifications\UpdateEmailOTPNotifications;
use INTCore\OneARTFoundation\Job;

class RequestUpdateEmailJob extends Job
{
    private $auth;
    private $email;

    /**
     * Create a new job instance.
     *
     * @param $auth
     * @param $email
     */
    public function __construct($auth, $email)
    {
        $this->auth = $auth;
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $temp_code = env('APP_DEBUG') ? 123456 : rand(100000,900000);

        $this->auth->update(['temp_email_code' => $temp_code]);

        $this->sendNotification($temp_code);
    }

    /**
     * Send notification with OTP
     * @param $code
     */
    private function sendNotification($code)
    {
        $this->auth->notify(new UpdateEmailOTPNotifications($code, $this->email));
    }
}
