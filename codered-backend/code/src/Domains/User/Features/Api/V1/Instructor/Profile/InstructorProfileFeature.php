<?php

namespace App\Domains\User\Features\Api\V1\Instructor\Profile;

use App\Domains\User\Http\Resources\Api\V1\Instructor\InstructorDetailsResource;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;

class InstructorProfileFeature extends Feature
{
    public function handle(Request $request)
    {
        $user = $request->user('api');

        return $this->run(RespondWithJsonJob::class, [
            'content' => new InstructorDetailsResource($user)
        ]);
    }
}
