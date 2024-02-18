<?php

namespace App\Domains\User\Features\Api\V1\Instructor\Profile;

use App\Domains\User\Http\Requests\Api\V1\Instructor\UpdateProfileRequest;
use App\Domains\User\Http\Resources\Api\V1\Instructor\InstructorDetailsResource;
use App\Domains\User\Jobs\Api\V1\Instructor\Profile\UpdateProfileJob;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Traits\Authenticated;
use App\Domains\Uploads\Jobs\MarkFileAsUsedJob;
use App\Domains\Uploads\Jobs\RevokeFileJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\User\Http\Resources\Api\V1\User\UserDetailsResource;

class UpdateProfileFeature extends Feature
{
    use Authenticated;

    public function handle(UpdateProfileRequest $request)
    {
        $user = $this->auth('api')->load('instructor_profile');

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
            "content" => new InstructorDetailsResource($user)
        ]);
    }
}
