<?php

namespace App\Domains\Enterprise\Features\Api\V1\User\Auth;

use App\Domains\User\Enum\UserType;
use Constants;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Enterprise\Jobs\Api\V1\User\Auth\LoginUserJob;
use App\Domains\User\Http\Resources\Api\V1\User\UserDetailsResource;
use App\Domains\Enterprise\Http\Requests\Api\V1\User\LoginUserRequest;
use App\Domains\Enterprise\Jobs\Api\V1\User\Device\UpdateDeviceInfoJob;


class LoginUserFeature extends Feature
{
    public function handle(LoginUserRequest $request)
    {
        $user = $this->run(new LoginUserJob($request));

        if(!is_null($user)) {

            if($user->activation == 0) {
                return $this->run(RespondWithJsonErrorJob::class, [
                    'errors' => [
                        "name"    => "password",
                        'message' => trans('user::lang.account_suspended')
                    ]
                ]);
            }

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
                "name"    => "password",
                'message' => trans('user::lang.invalid_credentials')
            ]
        ]);
    }
}
