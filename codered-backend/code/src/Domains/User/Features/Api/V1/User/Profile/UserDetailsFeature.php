<?php

namespace App\Domains\User\Features\Api\V1\User\Profile;

use App\Domains\User\Jobs\Api\V1\User\Profile\UserDetailsJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Domains\User\Http\Resources\Api\V1\User\UserDetailsResource;

class UserDetailsFeature extends Feature
{
    public function handle(Request $request)
    {
        $user = $this->run(new UserDetailsJob($request));

        return $this->run(new RespondWithJsonJob(new UserDetailsResource($user)));
    }
}
