<?php

namespace App\Domains\OpenApi\Features\Api\V2\User;

use App\Domains\Course\Http\Resources\Api\V1\Microdegree\MicrodegreeBasicInfoResource;
use App\Domains\Course\Repositories\MicrodegreeRepositoryInterface;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use Illuminate\Http\Request;

class MyMicrodegreesFeature extends Feature
{

    public function handle(Request $request, MicrodegreeRepositoryInterface $microdegreeRepository)
    {
        $user = $request->user('api');

        $user->load('completed_courses:id,course_id,user_id', 'microdegree_certifications_enrollments:id');
        $user?->load('watched_lessons:id,course_id,chapter_id');

        $not_completed_microdegrees = $microdegreeRepository->getNotCompletedMicrodegrees($user);
        $completed_microdegrees = $microdegreeRepository->getCompletedMicrodegrees($user);
        
        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'microdegrees'                  => MicrodegreeBasicInfoResource::collection($not_completed_microdegrees),
                'completed_microdegrees'        => MicrodegreeBasicInfoResource::collection($completed_microdegrees),
            ]
        ]);
    }
}
