<?php

namespace App\Domains\Enterprise\Features\Api\V1\User;

use App\Domains\Enterprise\Http\Requests\Api\V1\User\UpdateEnterpriseProfileRequest;
use App\Domains\Enterprise\Http\Requests\Api\V1\User\UpdateProfileRequest;
use App\Domains\Enterprise\Http\Resources\Api\V1\User\EnterpriseUserDetailsResource;
use App\Domains\Uploads\Jobs\MarkFileAsUsedJob;
use App\Domains\Uploads\Jobs\RevokeFileJob;
use App\Domains\User\Jobs\Api\V1\User\Profile\UpdateProfileJob;
use App\Foundation\Traits\Authenticated;
use Illuminate\Http\Resources\Json\JsonResource;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;


class UpdateProfileFeature extends Feature
{

    use Authenticated;

    public function handle(UpdateEnterpriseProfileRequest $request)
    {
        $user = $this->auth('api');

        if($request->has('image_id')) {
            if($user->image_id) {
                $this->run(RevokeFileJob::class, [
                    'file_id' => $user->image_id
                ]);
            }

            $this->run(MarkFileAsUsedJob::class, [
                'file_id' => $request->image_id
            ]);
        }

        $user = $this->run(UpdateProfileJob::class, [
            'request' => $request,
            'user'    => $user
        ]);


        $user = $user->fresh();
        return $this->run(RespondWithJsonJob::class, [
            "content" => new EnterpriseUserDetailsResource($user)
        ]);
    }
}
