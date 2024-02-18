<?php

namespace App\Domains\OpenApi\Features\Api\V2\User;

use App\Domains\OpenApi\Http\Requests\Api\V2\User\UpdateUserRequest;
use App\Domains\OpenApi\Http\Resources\Api\V2\User\UserInfoResource;
use App\Domains\User\Jobs\Api\V2\User\Profile\UpdateProfileJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;

class UpdateUserProfileFeature extends Feature
{

    public function handle(UpdateUserRequest $request)
    {
        
        // register user in database
        $user = $this->run(UpdateProfileJob::class, [
            'request' => $request,
            'user'    => $request->user('api')
        ]);

        return $this->run(RespondWithJsonJob::class, [
            'content' => new UserInfoResource($user)
        ]);
    }
}
