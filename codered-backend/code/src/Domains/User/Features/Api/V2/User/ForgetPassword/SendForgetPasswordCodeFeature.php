<?php

namespace App\Domains\User\Features\Api\V2\User\ForgetPassword;

use App\Domains\User\Jobs\Api\V2\User\ForgetPassword\SendForgetPasswordCodeJob;
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

//         if(empty($user)){
//             return $this->run(RespondWithJsonErrorJob::class, [
//                 "errors" => [
//                     "name" => "message",
//                     "message"=> trans("user::lang.user_not_found")
//                 ]
//             ]);
//         }
        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                "message" => 'If your email is registered with us, you will receive a one-time OTP on your email shortly to reset your password.'
            ]
        ]);
    }
}
