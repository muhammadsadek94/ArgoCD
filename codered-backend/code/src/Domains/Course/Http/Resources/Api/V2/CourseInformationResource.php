<?php

namespace App\Domains\Course\Http\Resources\Api\V2;

use App\Domains\Bundles\Http\Resources\BundleForCourseResource;
use App\Domains\Bundles\Models\CourseBundle;
use App\Domains\Course\Enum\CoursePackageType;
use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Models\Course;
use App\Domains\User\Http\Resources\Api\V2\User\ReviewUserResource;
use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Uploads\Http\Resources\FileResource;
use App\Domains\User\Http\Resources\Api\V2\User\InstructorBasicInfoResource;
use App\Domains\Course\Http\Resources\Api\V2\Chapter\CourseSyllabusResource;

class CourseInformationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $user = $request->user('api');
        $package = $this->packages->where('type', CoursePackageType::ONE_TIME)->first();
        $completed_course_id = $this->getCompletedCourseId($user);
        $user_course_enrollments = $user ? $user?->course_enrollments->where('id', $this->id)->count() > 0 : false;
        return [
            'id'                        => $this->id,
            'name'                      => $this->name,
            'activation'                => $this->activation,
            'image'                     => new FileResource($this->image),
            'cover'                     => new FileResource($this->cover),
            'category'                  => new CourseCategoryResource($this->sub ?? $this->category),
            'brief'                     => $this->brief,
            'description'               => $this->description,
            'level'                     => $this->level,
            'timing'                    => $this->timing,
            'learn'                     => $this->learn,
            'enrollment'                => $this->agg_count_course_enrollment,
            'job_role'                  => JobRoleResource::collection($this->jobRoles),
            'specialty_area'            => SpecialtyAreaResource::collection($this->specialtyAreas),
            'prerequisites'             => $this->prerequisites,
            'subtitles'                 => $this->subtitles,
            'tags'                      => $this->tags->map(function ($row) {
                return $row->name;
            }),
            'videos_count'              => @$this->agg_lessons['total_videos'],
            'rate'                      => ceil($this->agg_avg_reviews),
            'reviews_count'             => $this->agg_count_reviews,
            'top_reviews'               => ReviewUserResource::collection($this->getTopReviewers()),
            'rate_percentages'          => $this->agg_reviews ?? [],
            'assessments_count'         => @$this->agg_lessons['total_quizzes'],
            'interactive_labs_count'    => @$this->agg_lessons['total_labs'],
            'course_syllabus'           => CourseSyllabusResource::collection($this->chapters),
            'instructors'               => InstructorBasicInfoResource::collection($this->instructors),
            'instructor'                => new InstructorBasicInfoResource($this->user),
            'enrolled'                  => $user_course_enrollments,
            'intro_video'               => $this->intro_video,
            'completed_course_id'       => $completed_course_id,
            'is_free'                   => $this->is_free,
            'slug_url'                  => $this->slug_url,
            'amount'                    => $package ? $package->amount : 0,
            'price'                     => $this->price,
            'discount_price'            => $this->discount_price,
            'packages'                  => CoursePackageResource::collection($this->packages),
            'state'                     => $this->getCourseState($user, $completed_course_id, $user_course_enrollments),
            'has_access'                => $user ? has_access_course_eager($this->resource, $user) : false,
            'metadata'                  => $this->metadata ? $this->metadata : []

        ];
    }


    private function getRatePercentage()
    {
        $rates = [];

        $reviews = $this->reviews;
        $count = $reviews->count();
        if ($count == 0) return null;
        for ($i = 5; $i > 0; $i--) {
            $reviews = $this->reviews;
            array_push($rates, [
                'value' => $i,
                'percentage' => round((($reviews->where('rate', $i)->count() / $count) * 100), 0) . '%',
            ]);
        }
        return $rates;
    }



    private function getCompletedCourseId($user)
    {

        if (!$user) return null;

        return $this->completedCourses()->where(['user_id' => $user->id])->first()->id ?? null;
    }

    private function getTopReviewers()
    {

        return $this->reviews()->where('rate', '>=', 4)->whereNotNull('user_goals')->latest()->active()->take(6)->get();
    }



    private function getCourseState($user, $completed_course_id, $user_course_enrollments)
    {
//        $course = Course::select('id', 'course_type', 'is_free', 'activation', 'course_category_id', 'course_sub_category_id')->find($this->id);
        $course = $this->resource;
        $is_course_free = $this->is_free;
        // dd($enrolled, $completed, has_access_course($course), $is_course_free, count($this->packages));
        if ($user && $user_course_enrollments && $completed_course_id)
            return 1; // start over
        else if ($user && $user_course_enrollments && !$completed_course_id)
            return 2; // resume
        else if ($user && !$user_course_enrollments && has_access_course_eager($course, $user))
            return 3; // start course
        else if ( !$user_course_enrollments && !has_access_course_eager($course, $user) && count($this->packages) > 0 && !$is_course_free)
            return 4; // buy course
        else if (!$user && $is_course_free != 1)
            return 5; // Start Your 7-day trial for just $1
        else if (!$user && $is_course_free == 1)
            return 6; // Get Your Free Access Now
        else if ($user && !$user_course_enrollments && !has_access_course_eager($course, $user) && count($this->packages) == 0 && !$is_course_free)
            return 7;
    }
}
