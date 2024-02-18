<?php

namespace App\Domains\User\Features\Api\V1\Instructor\ForgetPassword;

use App\Domains\User\Jobs\Api\V1\Instructor\ForgetPassword\SendForgetPasswordCodeJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;

class SendForgetPasswordCodeFeature extends Feature
{
    public function handle(Request $request)
    {
        $user = $this->run(SendForgetPasswordCodeJob::class, [
            "request" => $request
        ]);

//        if(empty($user)){
//            return $this->run(RespondWithJsonErrorJob::class, [
//                "errors" => [
//                    "name" => "message",
//                    "message"=> trans("user::lang.user_not_found")
//                ]
//            ]);
//        }
        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                "message" => trans("user::lang.otp_sent")
            ]
        ]);
    }
}
