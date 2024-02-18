<?php

namespace App\Domains\User\Features\Api\V1\Instructor\ForgetPassword;

use App\Domains\User\Http\Requests\Api\V1\User\ResetPasswordRequest;
use App\Domains\User\Jobs\Api\V1\Instructor\ForgetPassword\ResetPasswordJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;

class ResetPasswordFeature extends Feature
{
    public function handle(ResetPasswordRequest $request)
    {

        $isResetted = $this->run(ResetPasswordJob::class, [
            "request" => $request
        ]);

        if (!$isResetted) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'name' => 'message',
                    "message"=>trans("user::lang.incorrect_otp")
                ]
            ]);
        }

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                "message"=> trans("user::lang.password_reset_success")
            ]
        ]);
    }
}


