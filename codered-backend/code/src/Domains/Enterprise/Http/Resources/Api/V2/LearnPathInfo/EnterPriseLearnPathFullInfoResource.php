<?php

namespace App\Domains\Enterprise\Http\Resources\Api\V2\LearnPathInfo;

use App\Domains\Cms\Http\Resources\Api\V1\BrandResource;
use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Http\Resources\Api\V2\CourseBasicInfoResource;
use App\Domains\Course\Http\Resources\Api\V2\CourseForLearnPathExternalResource;
use App\Domains\Course\Http\Resources\Api\V2\CourseWithChaptersResource;
use App\Domains\Course\Http\Resources\Api\V2\JobRoleResource;
use App\Domains\Course\Models\Chapter;
use App\Domains\Course\Models\CompletedCourses;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\Lesson;
use App\Domains\Payments\Enum\LearnPathType;
use App\Domains\Payments\Models\LearnPathInfo;
use App\Domains\Uploads\Http\Resources\FileResource;
use App\Domains\User\Http\Resources\Api\V2\Instructor\InstructorBasicInfoResource;
use App\Domains\User\Http\Resources\Api\V2\Instructor\InstructorResource;
use App\Domains\User\Models\User;
use Auth;
use Carbon\Carbon;
use DB;
use INTCore\OneARTFoundation\Http\JsonResource;

class EnterPriseLearnPathFullInfoResource extends JsonResource
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



        return [
            'id'                        => $this->id,
            'has_access'                => $this->hasAccess($request),
            'name'                      => $this->package->name,
            'slug_url'                  => $this->id,
            'description'               => null,
            'price'                     => $this->price,
            'price_description'         => $this->price_description,
            'average_salary'            => $this->avg_salary,
            'type'                      => 99, // TODO:: THIS REFER TO FRONTEND TO USE LEARN PATH as TITLE INSTEAD OF SKILL PATH... ETC,
            'type_name'                 => "Learning Path",
            'for_who'                   => $this->for_who,
            'payment_url'               => $this->payment_url,
            'overview'                  => null,
            'skills_description'        => $this->skills_description,
            'jobs_description'          => $this->jobs_description,
            'duration'                  => $this->package ? $this->getDuration() : 0,
            'courses_count'             => $this->package->courses->count() ? $this->package->courses->count() : 0,
            'labs_count'                => $this->getLabsCount(),
            'videos_count'              => $this->getVideosCount(),
            'assessment_questions'      => $this->getAssessmentQuestions(),
            'timing'                    => $this->getVideosDuration(),
            'completed_courses_count'   => $this->package->courses->count() ? $this->completedCoursesCount($request) : 0,
            'completion_percentage'     => $this->package->courses->count() ? $this->completionPercentage($request) : 0,
            'image'                     => new FileResource($this->image),
            'cover'                     => new FileResource($this->cover),
            'features'                  => [],
            'prerequisites'             => [],
            'tools'                     => [],
            'certificate'               => NULL,
            'faqs'                      => [],
            'metadata'                  =>  [],
            'learns'                    => [],
            'skills'                    => [],
            'subtitles'                 =>'',
            'jobs'                      => [],
            'courses'                   => $this->package->courses ? CourseForLearnPathExternalResource::collection($this->getCourses()->get()) : [],
            'instructors'               => $this->package->courses ? InstructorBasicInfoResource::collection($this->getInstructors()) : [],
            'related_learn_paths'       =>[],

        ];
    }

    public function getCourses()
    {

        $courses = $this->package->courses()
                    ->orderBy('sort','Asc');

        return $courses;
    }

    private function completedCoursesCount($request) {

        if(!$request->user('api')) return 0;

        $user = $request->user('api');

        $completed_courses = $this->getCourses()->whereHas('completedCourses', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();

        return $completed_courses;
    }

    private function completionPercentage($request) {
        if(!$request->user('api')) return 0;
        if(!$this->package->courses()->count()) return 0;

        return round($this->completedCoursesCount($request) / $this->package->courses()->count() * 100);
    }

    private function getDuration() {
        if($this->package->duration)
            return Carbon::now()->addDays($this->package->duration)->format('d M Y');
        return Carbon::now()->addDays(365)->format('d M Y');
    }

    private function getInstructors() {

        $instuctors_ids = $this->getCourses()->pluck('user_id')->toArray();

        $instuctors = User::whereIn('id', $instuctors_ids)->distinct('id')->get();

        return $instuctors;
    }

    private function getLabsCount()
    {
        return $this->package->labs->count();
    }

    private function getVideosCount()
    {
        return $this->package->videos->count();
    }

    private function getAssessmentQuestions()
    {
        $courses_ids = $this->getCourses()->pluck('courses.id');
        $chapters = Chapter::active()->whereIn('course_id', $courses_ids)->withCount(['lessons' => function($query){
            $query->where('type', LessonType::QUIZ)->active();
        }])->get()->sum('lessons_count');
        return $chapters;
    }

    private function getVideosDuration()
    {
        $timing = $this->getCourses()->sum('timing');
        $duration = ceil($timing / 60);
        return $duration;
    }

    private function hasAccess($request)
    {
        if(!$request->user('api')) return false;
        $user = $request->user('api');
        $user_package = $user->purchased_subscription()->where('expired_at', '>=' , Carbon::now()->format('Y-m-d'))
        ->whereHas('package', function($query){
            $query->where('id', $this->package_id);
        })->count();

        return !!$user_package;
    }

}
