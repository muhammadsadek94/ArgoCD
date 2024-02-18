<?php

namespace App\Domains\Enterprise\Features\Api\V1\User;

use App\Domains\Enterprise\Jobs\Api\V1\User\GetUserByidJob;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Enterprise\Http\Resources\Api\V1\User\EnterpriseUserDetailsResource;

class GetUsersByIdFeature extends Feature
{
    public function handle( Request $request)
    {
        $user = $this->run(GetUserByidJob::class, [
            "request" => $request
        ]);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'user' => new EnterpriseUserDetailsResource($user)
            ]
        ]);
    }
}
