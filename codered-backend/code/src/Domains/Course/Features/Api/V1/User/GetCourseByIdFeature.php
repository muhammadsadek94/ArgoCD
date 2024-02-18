<?php

namespace App\Domains\Course\Features\Api\V1\User;

use App\Domains\Course\Models\Course;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\Course\Http\Resources\Api\V1\CourseBasicInfoResource;
use App\Domains\Course\Http\Resources\Api\V1\CourseInformationResource;
use App\Domains\Bundles\Repositories\Interfaces\BundlesRepositoryInterface;
use App\Domains\Bundles\Http\Resources\BundlesBasicInformationResource;
use App\Domains\Bundles\Http\Resources\BundlesInformationResource;
use App\Domains\Bundles\Models\CourseBundle;

class GetCourseByIdFeature extends Feature
{

    public function handle(Request $request, CourseRepositoryInterface $course_repository, BundlesRepositoryInterface $bundles_repository)
    {
//        $course = $course_repository->find($request->course_id);
        $course = Course::where(['id' => $request->course_id])->orWhere(['slug_url' => $request->course_id])->firstOrFail();
        $request->course_id = $course->id;

        $related_courses = [];
        if (!empty($course->course_category_id)) {
            $related_courses = $course_repository->getCoursesByCategoryId($course->course_category_id, $course->id);
        }
        //Updating this API to show which bundles this course belongs to//
        $bundles = $bundles_repository->getBundleByCourseId($request->course_id);

        //Updating this API to show list of free courses which is marked as is_free by Admin//
        $free_courses = $course_repository->getFreeCourses();
        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'course' => new CourseInformationResource($course),
                'related_courses' => CourseBasicInfoResource::collection($related_courses),
                'bundles' => BundlesInformationResource::collection($bundles),
                'free_courses' => CourseBasicInfoResource::collection($free_courses),
            ]
        ]);
    }
}
