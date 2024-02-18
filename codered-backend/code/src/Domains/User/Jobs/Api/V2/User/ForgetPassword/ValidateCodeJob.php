<?php

namespace App\Domains\User\Jobs\Api\V2\User\ForgetPassword;

use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Job;
use App\Domains\User\Enum\UserType;

class ValidateCodeJob extends Job
{
    protected $request;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request =$request;
    }

    /**
     * Execute the job.
     *
     * @return bool
     */
    public function handle() : Bool
    {
        $user = null;
        if($this->request->email) {
            $user = User::where(["email" => $this->request->email, 'type' => UserType::USER])->where("password_reset_code", $this->request->code)->first();
        }
        if($this->request->phone) {
            $user = User::where(["phone" => $this->request->phone, 'type' => UserType::USER])->where("password_reset_code", $this->request->code)->first();
        }
        return !is_null($user);
    }
}
