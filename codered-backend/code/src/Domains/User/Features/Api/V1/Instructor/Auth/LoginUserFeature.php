<?php

namespace App\Domains\User\Features\Api\V1\Instructor\Auth;

use App\Domains\User\Enum\UserType;
use App\Domains\User\Jobs\Api\V1\Device\UpdateDeviceInfoJob;
use App\Domains\User\Http\Requests\Api\V1\Instructor\LoginUserRequest;
use App\Domains\User\Http\Resources\Api\V1\Instructor\InstructorDetailsResource;
use App\Domains\User\Jobs\Api\V1\Instructor\Auth\LoginUserJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use INTCore\OneARTFoundation\Feature;
use Constants;


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
            if($user->type != UserType::PROVIDER) {
                return $this->run(RespondWithJsonErrorJob::class, [
                    'errors' => [
                        "name"    => "password",
                        'message' => trans('enterprise::lang.account_not_allowed')
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
                'content' => new InstructorDetailsResource($user)
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
