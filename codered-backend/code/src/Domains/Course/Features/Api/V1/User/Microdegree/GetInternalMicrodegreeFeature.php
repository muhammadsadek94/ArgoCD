<?php

namespace App\Domains\Course\Features\Api\V1\User\Microdegree;

use App\Domains\Course\Models\Course;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\Course\Http\Resources\Api\V1\CourseInternalResource;
use App\Domains\Course\Http\Resources\Api\V1\CourseBasicInfoResource;
use App\Domains\Course\Http\Resources\Api\V1\CourseInformationResource;


class GetInternalMicrodegreeFeature extends Feature
{

    public function handle(Request $request, CourseRepositoryInterface $course_repository)
    {
        // $course = $course_repository->find($request->course_id);
        $course = Course::where(['id' => $request->course_id])->orWhere(['slug_url' => $request->course_id])->firstOrFail();
        $request->course_id = $course->id;
        if(!has_access_course($course)) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'name'    => 'message',
                    'message' => 'You must have active subscription',
                    'status' => 1001

                ]
            ]);
        }


        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'course' => new CourseInternalResource($course),
            ]
        ]);
    }
}
