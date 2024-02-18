<?php

namespace App\Domains\Enterprise\Features\Api\V1\SubAccount;

use App\Domains\Enterprise\Http\Resources\Api\V1\SubAccount\SubAccountDetailsResource;
use App\Domains\Enterprise\Jobs\Api\V1\SubAccount\GetSubAccountByIdJob;
use App\Domains\Enterprise\Jobs\Api\V1\User\GetUserByidJob;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonJob;


class GetSubAccountByIdFeature extends Feature
{
    public function handle( Request $request)
    {
        $user = $this->run(GetSubAccountByIdJob::class, [
            "request" => $request
        ]);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'user' => new SubAccountDetailsResource($user)
            ]
        ]);
    }
}
