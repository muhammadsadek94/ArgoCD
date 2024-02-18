<?php

namespace App\Domains\User\Features\Api\V2\User\Auth;

use App\Domains\User\Jobs\Api\V2\Device\UpdateDeviceInfoJob;
use App\Domains\User\Http\Resources\Api\V2\User\UserDetailsResource;
use App\Domains\User\Http\Requests\Api\V2\User\SocialMediaRegisterRequest ;
use App\Domains\User\Jobs\Api\V2\User\Activation\SendOtpActivationCodeJob;
use App\Domains\User\Jobs\Api\V2\User\Auth\RegisterUserJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;
use Constants;

class SocialMediaRegisterFeature extends Feature
{
    public function handle(SocialMediaRegisterRequest $request)
    {
        $user = $this->run(RegisterUserJob::class, [
            'request' => $request
        ]);

        $user = $this->run(SendOtpActivationCodeJob::class, [
            "auto_activate" => true,
            "user" =>  $user
        ]);

        $this->run(UpdateDeviceInfoJob::class, [
            'token'     => $user->access_token->token,
            'device_id' => $request->device_id ?? null,
            'language'  => $request->get('language') ?? $request->header('Accept-Language') ?? Constants::DEFAULT_LANGUAGE
        ]);

        return $this->run(RespondWithJsonJob::class, [
            'content' => new UserDetailsResource($user)
        ]);
    }
}
