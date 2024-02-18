<?php

namespace App\Domains\User\Features\Api\V2\User\Auth;

use App\Domains\User\Jobs\Api\V2\Device\UpdateDeviceInfoJob;
use App\Domains\User\Http\Resources\Api\V2\User\UserDetailsResource;
use App\Domains\User\Http\Requests\Api\V2\User\SocialMediaLoginRequest;
use App\Domains\User\Jobs\Api\V2\User\Auth\SocialMediaLoginUserJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;
use Constants;

class SocialMediaLoginFeature extends Feature
{
    public function handle(SocialMediaLoginRequest $request)
    {
        $user = $this->run(SocialMediaLoginUserJob::class, [
            "request" => $request
        ]);

        if(!is_null($user)) {
            //update device token & language
            $this->run(UpdateDeviceInfoJob::class, [
                'token'     => $user->access_token->token,
                'device_id' => $request->device_id ?? null,
                'language'  => $request->get('language') ?? $request->header('Accept-Language') ?? Constants::DEFAULT_LANGUAGE
            ]);

            return $this->run(RespondWithJsonJob::class, [
                'content' => new UserDetailsResource($user)
            ]);
        }


        return $this->run(RespondWithJsonErrorJob::class, [
            'errors' => [
                'name'    => 'message',
                "message" => trans("user::lang.Invalid credentials")
            ]
        ]);
    }
}
