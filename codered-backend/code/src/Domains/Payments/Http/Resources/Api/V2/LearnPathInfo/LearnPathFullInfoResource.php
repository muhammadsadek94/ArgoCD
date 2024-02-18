<?php

namespace App\Domains\Payments\Http\Resources\Api\V2\LearnPathInfo;

use App\Domains\Cms\Http\Resources\Api\V1\BrandResource;
use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Http\Resources\Api\V2\CourseForLearnPathExternalResource;
use App\Domains\Course\Http\Resources\Api\V2\JobRoleResource;
use App\Domains\Course\Models\Chapter;
use App\Domains\Payments\Enum\LearnPathType;
use App\Domains\Payments\Models\LearnPathInfo;
use App\Domains\Uploads\Http\Resources\FileResource;
use App\Domains\User\Http\Resources\Api\V2\Instructor\InstructorBasicInfoResource;
use App\Domains\User\Models\User;
use Auth;
use Carbon\Carbon;
use INTCore\OneARTFoundation\Http\JsonResource;

class LearnPathFullInfoResource extends JsonResource
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
        $certificate = null;
        if ($user) {
            $cert_check = $this->allPathCertificates->first();
            if ($cert_check) {
                $certificate = $cert_check->id;
            }
        }

        $overview = preg_replace('/\n/', '<br />', $this->overview);

                $related_paths = LearnPathInfo::select('id', 'name', 'slug_url', 'description', 'features', 'type', 'agg_courses')
                    ->with('allPathCertificates', function ($query) use ($user) {
                        $query->select('id', 'learnpath_id', 'user_id')
                            ->when(!empty($user), function ($query) use ($user) {
                                return $query->where('user_id', $user->id);
                            });
                    })
                    ->where('category_id', $this->category_id)
                    ->where('id', '!=', $this->id)
                    ->paths()
                    ->take(8)
                    ->get();

        $allCourses = $this->allCourses;
        $countCourses = @$this->agg_courses['total_courses'] ?? 0;

        $userCompletedCoursesCount = $this->whenLoaded('completedCourses') ? $this->completedCourses->count() : 0;

        return [
            'id'                      => $this->id,
            'has_access'              => $this->hasAccess($user),
            'name'                    => $this->name,
            'slug_url'                => $this->slug_url,
            'description'             => $this->description,
            'price'                   => $this->price,
            'price_description'       => $this->price_description,
            'average_salary'          => $this->avg_salary,
            'type'                    => (int) $this->type,
            'type_name'               => LearnPathType::getTypeName((int) $this->type),
            'for_who'                 => $this->for_who,
            'payment_url'             => $this->payment_url,
            'overview'                => $overview,
            'skills_description'      => $this->skills_description,
            'jobs_description'        => $this->jobs_description,
            'duration'                => $this->package ? $this->getDuration() : 0,
            'courses_count'           => $countCourses,
            'labs_count'              => @$this->agg_courses['total_labs'],
            'videos_count'            => @$this->agg_courses['total_videos'],
            'assessment_questions'    => @$this->agg_courses['total_quizzes'],
            'timing'                  => @$this->agg_courses['duration'],
            'completed_courses_count' => $userCompletedCoursesCount,
            'completion_percentage'   => $countCourses ? $this->completionPercentage($user, $countCourses, $userCompletedCoursesCount) : 0,
            'image'                   => new FileResource($this->image),
            'cover'                   => new FileResource($this->cover),
            'features'                => $this->features,
            'prerequisites'           => $this->prerequisite,
            'tools'                   => BrandResource::collection($this->tools()->limit(5)->get()),
            'certificate'             => $certificate,
            'faqs'                    => $this->faq,
            'metadata'                => $this->metadata ? $this->metadata : [],
            'learns'                  => $this->learn,
            'skills'                  => $this->skills,
            'subtitles'               => $this->subtitles ? implode(', ', $this->subtitles) : '',
            'jobs'                    => JobRoleResource::collection($this->jobRoles),
            'courses'                 => CourseForLearnPathExternalResource::collection($allCourses),
            'instructors'             => InstructorBasicInfoResource::collection($this->getInstructors($allCourses)),
            'related_learn_paths'     => LearnPathBasicInfoResource::collection($related_paths) ?? [],

        ];
    }

    private function completedCoursesCount($user)
    {
        if (!$user) return 0;
        return $this->completedCourses->count();
    }

    private function completionPercentage($user, $countCourses, $userCompletedCoursesCount)
    {
        if (!$user) return 0;
        return round($userCompletedCoursesCount / $countCourses * 100);
    }

    private function getDuration()
    {
        if ($this->package->duration)
            return Carbon::now()->addDays($this->package->duration)->format('d M Y');
        return Carbon::now()->addDays(365)->format('d M Y');
    }

    private function getInstructors($allCourses)
    {

        $instuctors_ids = $allCourses->pluck('user_id')->toArray();

        $instuctors = User::whereIn('id', $instuctors_ids)
            ->select('users.id', 'first_name', 'last_name', 'image_id', 'activation')
            ->with('instructor_profile:id,user_id,profile_summary,facebook_url,twitter_url,instagram_url,current_employer,job', 'image:id,path,full_url,mime_type')
            ->distinct('id')
            ->get();

        return $instuctors;
    }

    private function hasAccess($user)
    {

        if (!$user) return false;
        $user_package = $user->purchased_subscription()->where('expired_at', '>=', Carbon::now()->format('Y-m-d'))
            ->whereHas('package', function ($query) {
                $query->where('learn_path_id', $this->id);
            })->count();

        return !!$user_package;
    }
}
