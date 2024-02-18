<?php

namespace App\Domains\Enterprise\Http\Resources\Api\V1\User;

use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\Course\Http\Resources\Api\V1\CourseCategoryResource;
use App\Domains\Course\Models\Course;
use App\Domains\Uploads\Http\Resources\FileResource;
use App\Domains\User\Http\Resources\Api\V1\User\InstructorBasicInfoResource;
use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Http\JsonResource;
use DB;
class EnterpriseLearnPathCoursesWithProgressResource extends JsonResource
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
            'id'                  => $this->id,
            'name'                => $this->name,
            'activation'          => $this->activation,
            'image'               => new \Illuminate\Http\Resources\Json\JsonResource($this->image),
            'cover'               => new FileResource($this->cover),
            'category'            => $this->sub ? new CourseCategoryResource($this->sub) : new CourseCategoryResource($this->category),
            'brief'               => $this->brief,
            'description'         => $this->description,
            'level'               => $this->level,
            'timing'              => $this->timing,
            'learn'               => $this->learn,
            'weight'              => $this->pivot->weight,
            'instructors'         => InstructorBasicInfoResource::collection($this->instructors),
            'instructor'          => new InstructorBasicInfoResource($this->user),
            'intro_video'         => $this->intro_video,
            'is_free'             => $this->is_free,
            'slug_url'            => $this->slug_url,
            'tags'                => $this->tags()->active()->get()->map(function ($row){return $row->name;}),
            'users'               => EnterpriseUserDetailsResource::collection( $this->users()),
            'avg_progress'        => number_format($this->avg_progress(), 2)

        ];


    }
    private function users(){
        $admin = request()->user('api');

        $users  = User::select(DB::raw('users.*  , completed_course_percentages.completed_percentage as progress'))

            ->join("completed_course_percentages", function ($join) {
                $join->on("users.id", "=", "completed_course_percentages.user_id");
            })
            ->join("courses", function ($join) {
                $join->on("completed_course_percentages.course_id", "=", "courses.id");
            })
            ->join("course_weights", function ($join) {
                $join->on("courses.id", "=", "course_weights.course_id");
            })
            ->join("package_subscriptions", function ($join) {
                $join->on("package_subscriptions.id", "course_weights.package_subscription_id", "=");
            })
            ->where("courses.id", "=", $this->id)
            ->where(function ($query) use ($admin) {
                $query->where('users.enterprise_id', $admin->id)
                    ->orWhere('users.subaccount_id', $admin->id);
            })->groupBy('users.id')
            ->get();
//        dd($avg_progress);
        return $users;
    }

    private function avg_progress(){
        $admin = request()->user('api');

        return Course::select(DB::raw('  AVG (completed_course_percentages.completed_percentage) as avg_progress'))

            ->join("completed_course_percentages", function ($join) {
                $join->on("courses.id", "=", "completed_course_percentages.course_id");
            })
            ->join("users", function ($join) {
                $join->on("completed_course_percentages.user_id", "=", "users.id");
            })
            ->join("course_weights", function ($join) {
                $join->on("courses.id", "=", "course_weights.course_id");
            })
            ->join("package_subscriptions", function ($join) {
                $join->on("package_subscriptions.id", "course_weights.package_subscription_id", "=");
            })
            ->where("courses.id", "=", $this->id)
            ->where(function ($query) use ($admin) {
                $query->where('users.enterprise_id', $admin->id)
                    ->orWhere('users.subaccount_id', $admin->id);
            })
            ->first()->avg_progress;
    }
    private function getLearnPaths(){
        $subscription_ids =  $this->active_subscription->pluck('package_id')->toArray();
        return PackageSubscription::whereIn('id', $subscription_ids)->get();
    }
}
