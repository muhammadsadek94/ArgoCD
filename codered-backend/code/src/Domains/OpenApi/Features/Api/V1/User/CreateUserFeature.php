<?php

namespace App\Domains\OpenApi\Features\Api\V1\User;

use App\Domains\OpenApi\Http\Requests\Api\V1\User\RegisterUserRequest;
use App\Domains\OpenApi\Http\Resources\Api\V1\User\UserInfoResource;
use App\Domains\User\Jobs\Api\V1\User\Activation\SendOtpActivationCodeJob;
use App\Domains\User\Jobs\Api\V1\User\Auth\RegisterUserJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;

class CreateUserFeature extends Feature
{

    public function handle(RegisterUserRequest $request)
    {

        // register user in database

        $user = $this->run(RegisterUserJob::class, [
            'request'       => $request,
            'generateToken' => false
        ]);

        // send otp
        $this->run(SendOtpActivationCodeJob::class, [
            "user" =>  $user,
            'auto_activate' => true
        ]);
        return $this->run(RespondWithJsonJob::class, [
            'content' => new UserInfoResource($user)
        ]);
    }
}
