<?php

namespace App\Domains\Course\Http\Resources\Api\V1;

use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Models\Course;
use App\Domains\Enterprise\Models\CourseWeight;
use App\Domains\Uploads\Http\Resources\FileResource;
use App\Domains\User\Models\UserSubscription;
use Auth;
use Carbon\Carbon;
use INTCore\OneARTFoundation\Http\JsonResource;

class LearnPathWithCoursesResource extends JsonResource
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
            'id'   =>$this->id,
            'name'                      => $this->name,
            'progress'                  => $this->progress($this->id),
            'duration'                  =>  $this->getDuration() ?? 0,
            'courses'                   => CourseInternalResource::collection($this->courses),
        ];

    }


    private function getDuration() {
        $user = Auth::user();
        if(!$user) return;
        $subsription = UserSubscription::where('package_id', $this->id)
            ->where('user_id', $user->id)->orderBy('expired_at', 'DESC')->first();
        if($subsription)
            return Carbon::parse($subsription->expired_at)->format('d M Y');
    }

    private function progress($package_id)
    {
        $user = Auth::user();
        if(!$user) return;
        return CourseWeight::select(\DB::raw(' AVG((course_weights.weight/100) * completed_course_percentages.completed_percentage) as avg_progress'))
            ->join("completed_course_percentages", function ($join) {
                $join->on("course_weights.course_id", "=", "completed_course_percentages.course_id");
            })
            ->join("users", function ($join) {
                $join->on("completed_course_percentages.user_id", "=", "users.id");
            })
            ->join("package_subscriptions", function ($join) {
                $join->on("course_weights.package_subscription_id", "=", "package_subscriptions.id");
            })
            ->where("package_subscriptions.id", "=", $package_id)
            ->where("users.id", "=", $user->id)
            ->first()->avg_progress;
    }

}
