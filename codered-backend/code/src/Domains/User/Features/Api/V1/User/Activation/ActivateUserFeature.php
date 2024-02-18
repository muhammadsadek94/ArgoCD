<?php

namespace App\Domains\User\Features\Api\V1\User\Activation;

use App\Domains\User\Http\Requests\Api\V1\User\ActivateUserRequest;
use App\Domains\User\Jobs\Api\V1\User\Activation\ActivateUserJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;
use App\Domains\User\Http\Resources\Api\V1\User\UserDetailsResource;

class ActivateUserFeature extends Feature
{
    public function handle(ActivateUserRequest $request)
    {

        $user = $this->run(ActivateUserJob::class, [
            'request' => $request
        ]);


        if (empty($user)) {
            return $this->run(RespondWithJsonErrorJob::class, [
                "errors" => [
                    'name' => 'code',
                    "message" => trans("user::lang.incorrect_otp")
                ]
            ]);
        }
        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'user' => new UserDetailsResource($user)
            ]
        ]);
    }
}
