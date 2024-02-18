<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\User\ForgetPassword;

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
    public function handle(): bool
    {
        $user = $this->getUser();

        if (!is_null($user)) {

            if ($user->password_reset_code !== $this->request->code) return false;

            $new_password = $this->request->password;
            $user->update(['password' => $new_password, "password_reset_code" => null]);

            foreach ($user->tokens as $token) {
                $token->revoke();
            }
            return true;

        }
        return false;


    }

    private function getUser(): ?User
    {
        $user = User::where([
            "email" => $this->request->email,
        ])
        ->whereIn('type', [UserType::PRO_ENTERPRISE_ADMIN, UserType::PRO_ENTERPRISE_SUBACCOUNT])
        ->first();

        return $user;
    }
}
