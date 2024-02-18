<?php

namespace App\Domains\User\Features\Api\V1\Instructor\Profile;

use App\Domains\User\Http\Resources\Api\V1\Instructor\InstructorDetailsResource;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Traits\Authenticated;
use App\Domains\User\Http\Requests\Api\V1\Instructor\UpdateBankInfoRequest;
use App\Domains\User\Jobs\Api\V1\Instructor\Profile\UpdateBankInfoJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;

class UpdateBankInfoFeature extends Feature
{
    use Authenticated;

    public function handle(UpdateBankInfoRequest $request)
    {
        $user = $this->auth('api')->load('instructor_profile');

        $user = $this->run(UpdateBankInfoJob::class, [
            'request' => $request,
            'user'    => $user
        ]);


        $user = $user->fresh();
        return $this->run(RespondWithJsonJob::class, [
            "content" => new InstructorDetailsResource($user)
        ]);
    }
}
