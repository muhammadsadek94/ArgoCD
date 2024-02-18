<?php

namespace App\Domains\Bundles\Http\Resources\V2;

use App\Domains\Bundles\Http\Resources\BundlesBasicInformationResource;
use App\Domains\Cms\Http\Resources\Api\V2\BrandResource;
use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Http\Resources\Api\V2\CourseForLearnPathExternalResource;
use App\Domains\Course\Models\Chapter;
use App\Domains\Course\Models\CompletedCourses;
use App\Domains\Course\Models\Course;
use App\Domains\Payments\Models\LearnPathInfo;
use App\Domains\Uploads\Http\Resources\FileResource;
use App\Domains\User\Http\Resources\Api\V2\Instructor\InstructorBasicInfoResource;
use App\Domains\User\Models\User;
use Auth;
use Carbon\Carbon;
use INTCore\OneARTFoundation\Http\JsonResource;

class CategoryBundlesResource extends JsonResource
{
     /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id'                        => $this->id,
            'has_access'                => $this->hasAccess($request),
            'name'                      => $this->name,
            'slug_url'                  => $this->slug_url,
            'description'               => $this->description,
            'price'                     => $this->price,
            'price_description'         => $this->price_description,
            'average_salary'            => $this->avg_salary,
            'type_name'                 => 'Custom Bundle',
            'for_who'                   => $this->for_who,
            'payment_url'               => $this->payment_url,
            'overview'                  => $this->overview,
            'skills_description'        => $this->skills_description,
            'jobs_description'          => $this->jobs_description,
            'duration'                  => $this->package ? $this->getDuration() : 0,
            'courses_count'             => $this->categoryCourses()->count() ? $this->categoryCourses()->count() : 0,
            'labs_count'                => $this->getLabsCount(),
            'videos_count'              => $this->getVideosCount(),
            'assessment_questions'      => $this->getAssessmentQuestions(),
            'timing'                    => $this->getVideosDuration(),
            'completed_courses_count'   => $this->categoryCourses()->count() ? $this->completedCoursesCount($request) : 0,
            'completion_percentage'     => $this->categoryCourses()->count() ? $this->completionPercentage($request) : 0,
            'image'                     => new FileResource($this->image),
            'cover'                     => new FileResource($this->cover),
            'features'                  => $this->features,
            'prerequisites'             => $this->prerequisite,
            'tools'                     => BrandResource::collection($this->tools()->limit(5)->get()),
            'faqs'                      => $this->faq,
            'learns'                    => $this->learn,
            'skills'                    => $this->skills,
            'features'                  => $this->features,
            'subtitles'                 => $this->subtitles ? implode(', ', $this->subtitles) : '',
            'jobs'                      => $this->jobs,
            'courses'                   => $this->categoryCourses() ? CourseForLearnPathExternalResource::collection($this->categoryCourses()) : [],
            'instructors'               => $this->categoryCourses() ? InstructorBasicInfoResource::collection($this->getInstructors()) : [],
            'related_bundles'           => BundleBasicInfoResource::collection(LearnPathInfo::where('type', $this->type)->where('id', '!=', $this->id)->active()->get()) ?? [],
        ];
    }

    private function categoryCourses() {
        $courses = Course::where('course_category_id', $this->category_id)
            ->active()
            ->limit(5)
            ->get();

        return $courses;
    }

    private function completedCoursesCount($request) {
        if(!$request->user('api')) return 0;
        $user = $request->user('api');
        $courses_ids = $this->categoryCourses()->pluck('id');
        $completed_courses = CompletedCourses::where('user_id', $user->id)->whereIn('course_id', $courses_ids)->count();
        return $completed_courses;
    }

    private function completionPercentage($request) {
        if(!$request->user('api')) return 0;
        $user = $request->user('api');
        $courses_ids = $this->categoryCourses()->pluck('id');
        $completed_courses = CompletedCourses::where('user_id', $user->id)->whereIn('course_id', $courses_ids)->count();
        return ($completed_courses / $this->categoryCourses()->count()) * 100;
    }

    private function getDuration() {
        if($this->package->duration)
            return Carbon::now()->addDays($this->package->duration)->format('d M Y');
        return Carbon::now()->addDays(365)->format('d M Y');
    }

    private function getInstructors() {
        $instuctors_ids = [];
        foreach($this->categoryCourses() as $course){
            array_push($instuctors_ids, $course->user_id);
        }
        $instuctors = User::whereIn('id', $instuctors_ids)->distinct('id')->get();
        
        return $instuctors;
    }

    private function getLabsCount()
    {
        $courses_ids = $this->categoryCourses()->pluck('id');
        $chapters = Chapter::whereIn('course_id', $courses_ids)->active()->withCount(['lessons' => function($query){
            $query->where('type', LessonType::LAB);
        }])->get()->sum('lessons_count');
        return $chapters;
    }

    private function getVideosCount()
    {
        $courses_ids = Course::where('course_category_id', $this->category_id)->where('is_free', 0)->active()->pluck('id');
        $chapters = Chapter::whereIn('course_id', $courses_ids)->active()->withCount(['lessons' => function($query){
            $query->where('type', LessonType::VIDEO);
        }])->get()->sum('lessons_count');
        return $chapters;
    }

    private function getAssessmentQuestions()
    {
        $courses_ids = $this->categoryCourses()->pluck('id');
        $chapters = Chapter::active()->whereIn('course_id', $courses_ids)->withCount(['lessons' => function($query){
            $query->where('type', LessonType::QUIZ)->active();
        }])->get()->sum('lessons_count');
        return $chapters;
    }

    private function getVideosDuration()
    {
        $timing = $this->categoryCourses()->sum('timing');
        $duration = ceil($timing / 60);
        return $duration;
    }

    private function hasAccess($request)
    {
        if(!$request->user('api')) return false;
        $user = $request->user('api');
        $user_package = $user->purchased_subscription()->where('expired_at', '>=' , Carbon::now()->format('Y-m-d'))->where('package_id', $this->package_id)->first();
        return !!$user_package;
    }
}
