<?php

namespace App\Domains\User\Jobs\Api\V1\Instructor\ForgetPassword;

use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Job;
use PhpParser\Node\Expr\Array_;
use App\Domains\User\Enum\UserType;

class ResetPasswordJob extends Job
{
    protected $request;

    /**
     * Create a new job instance.
     *
     * @param $request
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
    public function handle() : Bool
    {
        $user = $this->getUser();

        if (!is_null($user)) {

            if ($user->password_reset_code !== $this->request->code) return false;

            $new_password = $this->request->password;
            $user->update(['password' => $new_password, "password_reset_code" => null]);
            return true;

        }
        return false;



    }

    private function getUser() : ?User
    {
        $user = null;
        if (!empty($this->request->email)) {
            $user = User::where(["email" => $this->request->email, 'type' => UserType::PROVIDER])->first();
        }

        if (!empty($this->request->phone)) {
            $user = User::where(["phone" => $this->request->phone, 'type' => UserType::PROVIDER])->first();
        }

        return $user;
    }
}
