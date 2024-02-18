<?php

namespace App\Domains\User\Features\Api\V2\User\Profile;

use INTCore\OneARTFoundation\Feature;
use App\Foundation\Traits\Authenticated;
use App\Domains\Uploads\Jobs\MarkFileAsUsedJob;
use App\Domains\Uploads\Jobs\RevokeFileJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\User\Jobs\Api\V2\User\Profile\UpdateProfileJob;
use App\Domains\User\Http\Requests\Api\V2\User\UpdateProfileRequest;
use App\Domains\User\Http\Resources\Api\V2\User\UserDetailsResource;
use App\Domains\User\Jobs\Api\V1\User\Profile\CheckPasswordJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;

class UpdateProfileFeature extends Feature
{
    use Authenticated;

    public function handle(UpdateProfileRequest $request)
    {
        $user = $this->auth('api');

        if ($request->has('image_id')) {
            if ($user->image_id) {
                $this->run(RevokeFileJob::class, [
                    'file_id' => $user->image_id
                ]);
            }

            $this->run(MarkFileAsUsedJob::class, [
                'file_id' => $request->image_id
            ]);

            if ($user->image_id != $request->image_id)
                $request->merge(['is_profile_picture_updated' => 1]);
        }

        $isValid = $this->run(CheckPasswordJob::class, [
            'request' => $request,
            'user' => $user
        ]);

        if (!$isValid) {
            return $this->run(RespondWithJsonErrorJob::class, [
                "errors" => [
                    "name" => "password",
                    "message" => 'incorrect password'
                ]
            ]);
        }

        $user = $this->run(UpdateProfileJob::class, [
            'request' => $request,
            'user'    => $user
        ]);


        $user = $user->fresh();
        return $this->run(RespondWithJsonJob::class, [
            "content" => new UserDetailsResource($user)
        ]);
    }
}
