<?php

namespace App\Domains\OpenApi\Features\Api\V2\User;

use App\Domains\OpenApi\Http\Requests\Api\V2\User\RegisterUserRequest;
use App\Domains\OpenApi\Http\Resources\Api\V2\User\UserInfoResource;
use App\Domains\User\Jobs\Api\V2\User\Activation\SendOtpActivationCodeJob;
use App\Domains\User\Jobs\Api\V2\User\Auth\RegisterUserJob;
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
