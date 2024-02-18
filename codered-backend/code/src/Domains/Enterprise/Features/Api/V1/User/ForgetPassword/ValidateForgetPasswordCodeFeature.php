<?php

namespace App\Domains\Enterprise\Features\Api\V1\User\ForgetPassword;

use App\Domains\Enterprise\Http\Requests\Api\V1\User\ValidateForgetPasswordCodeRequest;
use App\Domains\Enterprise\Jobs\Api\V1\User\ForgetPassword\ValidateCodeJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;

class ValidateForgetPasswordCodeFeature extends Feature
{
    public function handle(ValidateForgetPasswordCodeRequest $request)
    {

        $status = $this->run(ValidateCodeJob::class, [
            "request" => $request
        ]);

        if(!$status) {
            return $this->run(RespondWithJsonErrorJob::class, [
                "errors" => [
                    "name"    => "code",
                    "message" => trans("user::lang.incorrect_otp")
                ]
            ]);
        }

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                "message" => trans("user::lang.correct_otp")
            ]
        ]);
    }
}
