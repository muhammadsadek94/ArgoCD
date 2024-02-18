<?php

namespace App\Domains\User\Features\Api\V2\User\Profile;

use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Course\Http\Resources\Api\V2\Microdegree\MicrodegreeBasicInfoResource;


class EnrolledInMicroDegreesFeature extends Feature
{

    public function handle(Request $request)
    {
        $user = $request->user('api');
        $micro_degree = $user->microdegree_enrollments;

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'micro_degree' => MicrodegreeBasicInfoResource::collection($micro_degree),
            ]
        ]);
    }
}
