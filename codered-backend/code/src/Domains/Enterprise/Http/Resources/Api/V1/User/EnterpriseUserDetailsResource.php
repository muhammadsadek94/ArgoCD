<?php

namespace App\Domains\Enterprise\Http\Resources\Api\V1\User;

use App\Domains\Course\Models\Course;
use App\Domains\Enterprise\Models\CourseWeight;
use App\Domains\Payments\Enum\AccessType;
use App\Domains\Payments\Enum\SubscriptionPackageType;
use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\Enterprise\Models\License;

use App\Domains\Uploads\Http\Resources\FileResource;
use Carbon\Carbon;
use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Geography\Http\Resources\CityResource;
use App\Domains\Geography\Http\Resources\CountryResource;
use DB;

class EnterpriseUserDetailsResource extends JsonResource
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
            "first_name" => $this->full_name,
            "email" => $this->email,
            "company_name" => $this->company_name,
            "image" => new FileResource($this->image),
            "type" => $this->type,
            "mins_watched" =>  number_format($this->watched_lessons_time()/60,2, '.', ''),
            "enrollment" => $this->all_course_enrollments()->count(),
            "enrollment_courses" => UserCourseResource::collection($this->all_course_enrollments()->get()),
            "completed_courses" => CompletedCourseResource::collection($this->completed_courses()->get()),
            "subaccount" => new EnterpriseDetailsResource($this->enterpriseSubAccount()->first()),
            "enterprise" => new EnterpriseDetailsResource($this->enterpriseAccount()->first()),
            "join_date" => $this->created_at ? $this->created_at->format('d M Y'): null,
            'gender' => $this->gender,
            'country' => new CountryResource($this->country),
            'city' => new CityResource($this->city),
            'daily_target' => $this->daily_target,
            'tags' => $this->usertags()->get(),
            'learn_paths' => $this->getLearnPaths(),
            'subscription' => $this->active_subscription,
            'activation' => $this->activation,
            'progress' => isset($this->progress) ? $this->progress : null
        ];
    }

    private function getLearnPaths()
    {
        return PackageSubscription::join("user_subscriptions", function ($join) {
            $join->on("package_subscriptions.id", "=", "user_subscriptions.package_id");
        })
            ->join("users", function ($join) {
                $join->on("user_subscriptions.user_id", "=", "users.id");
            })
            ->whereIn('package_subscriptions.access_type',[AccessType::LEARN_PATH_SKILL , AccessType::LEARN_PATH_CAREER , AccessType::LEARN_PATH_CERTIFICATE, AccessType::PRO])
//            ->where("package_subscriptions.type", "=", SubscriptionPackageType::Enterprise)
            ->where("users.id", "=", $this->id)
//            ->select(DB::raw('package_subscriptions*'))
            ->get()->map(function ($package) {
                $package->progress = $this->progress($package);
                return $package;
            })->toArray();
    }

    private function progress($package)
    {
        return CourseWeight::select(DB::raw(' AVG((course_weights.weight/100) * completed_course_percentages.completed_percentage) as avg_progress'))
            ->join("completed_course_percentages", function ($join) {
                $join->on("course_weights.course_id", "=", "completed_course_percentages.course_id");
                $join->where("completed_course_percentages.user_id", "=", $this->id);
            })
            ->join("users", function ($join) {
                $join->on("completed_course_percentages.user_id", "=", "users.id");
            })
            ->join("package_subscriptions", function ($join) {
                $join->on("course_weights.package_subscription_id", "=", "package_subscriptions.id");
            })
            ->where("package_subscriptions.id", "=", $package->package_id)
            ->where("users.id", "=", $this->id)
            ->first()->avg_progress;


    }
}
