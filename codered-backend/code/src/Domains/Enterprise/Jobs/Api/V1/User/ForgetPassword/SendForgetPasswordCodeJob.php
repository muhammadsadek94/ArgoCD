<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\User\ForgetPassword;

use App\Domains\User\Models\User;
use App\Domains\User\Notifications\SendForgetPasswordCodeNotifications;
use INTCore\OneARTFoundation\Job;

class SendForgetPasswordCodeJob extends Job
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
    public function handle(): ?User
    {
        $user = null;
        if ($this->request->has("email") && !empty($this->request->email)) {
            $user = User::where("email", $this->request->email)->first();
        } elseif ($this->request->has("phone") && !empty($this->request->phone)) {
            $user = User::where("phone", $this->request->phone)->first();
        }
        if (!empty($user)) {
            if ($user->activation == 0) return null;

            $reset_password_code = $this->generateForgetPasswordCode();
            $user->update(["password_reset_code" => $reset_password_code]);
            $user->notify(new SendForgetPasswordCodeNotifications($reset_password_code));
            return $user;
        }
        return null;

    }

    protected function generateForgetPasswordCode(): string
    {
        return env('APP_DEBUG') ? 123456 : rand(100000, 900000);
    }
}
