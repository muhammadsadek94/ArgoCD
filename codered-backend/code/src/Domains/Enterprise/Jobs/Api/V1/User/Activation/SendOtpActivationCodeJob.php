<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\User\Activation;

use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Job;
use App\Domains\User\Enum\UserActivation;
use App\Domains\Enterprise\Notifications\SendActivationCodeNotifications;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;

class SendOtpActivationCodeJob extends Job
{
    use Queueable;
    protected $user;
    protected $auto_activate;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param bool $auto_activate
     */
    public function __construct(User $user, $auto_activate = true)
    {
        $this->user = $user;
        $this->auto_activate = $auto_activate;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() :User
    {

        $user = $this->getUser();

//       $code = $this->generateActivationCode();
         $code = UserActivation::COMPLETE_PROFILE;
        $user->update(["activation" => $code]);

        if(!$this->auto_activate)
            $this->sendReminder($user, $code);

        return $this->returnUpdatedModel($code);
    }

    /**
     * make copy of user object then remove access_token
     * to avoid update access_token column and this no exsit in users table
     */
    private function getUser() :User
    {
        $user = clone $this->user;
        unset($user->access_token);
        return $user;
    }

    /**
     * Update model with activation code
     * @param $code
     * @return User
     */
    private function returnUpdatedModel($code) :User
    {
        $this->user->activation = $code;
        return $this->user;
    }

    /**
     * Generate token
     */
    private function generateActivationCode() :string
    {
        // if($this->auto_activate)
        //     return UserActivation::ACTIVE;
        return env('APP_DEBUG') ? 123456 : rand(100000, 900000);
    }

    /**
     * Send notification with OTP
     * @param User $user
     * @param string|integer $code
     */
    private function sendReminder($user, $code)
    {
        $user->notify(new SendActivationCodeNotifications($code));
    }
}
