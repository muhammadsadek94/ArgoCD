<?php

namespace App\Domains\Enterprise\Http\Resources\Api\V1\User;

use App\Domains\Course\Models\Course;
use App\Domains\Enterprise\Models\License;
use App\Domains\Uploads\Http\Resources\FileResource;
use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Geography\Http\Resources\CityResource;
use App\Domains\Geography\Http\Resources\CountryResource;
use DB;

class EnterpriseLearnPathsDetailsResource extends JsonResource
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
            "id" => $this->id,
            "name" => $this->name,
            "deadline_type" => $this->deadline_type,
            "expiration_date" => $this->expiration_date,
            "expiration_days" => $this->expiration_days,
            "created_at" => $this->created_at->format('d M Y'),
            "status" => $this->activation,
            "enterprise_id" => $this->enterprise_id,
            "enrolled" => count($this->users()),
            "avg_progress" => number_format($this->progress(), 2),
            "courses" => EnterpriseLearnPathCoursesWithProgressResource::collection($this->courses),
            "users" => EnterpriseUserDetailsResource::collection($this->users())
        ];
    }


    public function users()
    {
        $admin = request()->user('api');
        return User::join("user_subscriptions", function ($join) {
            $join->on("users.id", "=", "user_subscriptions.user_id");
        })
            ->join("package_subscriptions", function ($join) {
                $join->on("package_subscriptions.id", "=", "user_subscriptions.package_id");
            })
            ->where("package_subscriptions.id", "=", $this->id)
            ->selectRaw('users.*')
            ->where(function ($query) use ($admin) {
                $query->where('users.enterprise_id', $admin->id)
                    ->orWhere('users.subaccount_id', $admin->id);
            })
            ->get();

    }

    public function progress()
    {
        $admin = request()->user('api');
        $avg_progress = Course::join("completed_course_percentages", function ($join) {
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
            ->select(DB::raw('AVG(completed_course_percentages.completed_percentage) as avg_progress'))
            ->where("package_subscriptions.id", "=", $this->id)
            ->where(function ($query) use ($admin) {
                $query->where('users.enterprise_id', $admin->id)
                    ->orWhere('users.subaccount_id', $admin->id);
            })
            ->first()->avg_progress;
        return number_format((float)$avg_progress, 2, '.', '');
    }
}
