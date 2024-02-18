<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\User\ForgetPassword;

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
        $this->request = $request;
    }

    /**
     *
     * Execute the job.
     *
     * @return bool
     */
    public function handle(): bool
    {
        $user = User::where([
            "email" => $this->request->email,
        ])->whereIn('type', [UserType::PRO_ENTERPRISE_ADMIN, UserType::PRO_ENTERPRISE_SUBACCOUNT])
            ->where("password_reset_code", $this->request->code)
            ->first();

        return !is_null($user);
    }
}
