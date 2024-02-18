<?php

namespace App\Domains\User\Features\Api\V1\User\Activation;

use App\Domains\User\Jobs\Api\V1\User\Activation\SendOtpActivationCodeJob;
use App\Domains\User\Jobs\Api\V1\User\UserDetailsJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;

class ResendActivationCodeFeature extends Feature
{
    public function handle(Request $request)
    {
       $user = $request->user();

        $this->run(SendOtpActivationCodeJob::class, [
            "user" =>  $user
        ]);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                "message" => trans("user::lang.otp_sent_email")
            ]
        ]);
    }
}
