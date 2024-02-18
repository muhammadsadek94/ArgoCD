<?php

namespace App\Domains\Enterprise\Features\Api\V1\User;

use App\Domains\OpenApi\Http\Requests\Api\V1\User\UpdateUserRequest;
use App\Domains\OpenApi\Http\Resources\Api\V1\User\UserInfoResource;
use App\Domains\User\Jobs\Api\V1\User\Profile\DeleteSubscriptionJob;
use App\Domains\User\Jobs\Api\V1\User\Profile\UpdateProfileJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use Illuminate\Http\Client\Request;
use INTCore\OneARTFoundation\Feature;

class DeleteUserSubscribtion extends Feature
{

    public function handle(Request $request)
    {

        // register user in database
        $user = $this->run(DeleteSubscriptionJob::class, [
            'request' => $request,
        ]);

        return $this->run(RespondWithJsonJob::class, [
            'content' => new UserInfoResource($user)
        ]);
    }
}
