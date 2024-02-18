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

class BasicEnterpriseUserDetailsResource extends JsonResource
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
            'progress' => isset($this->progress) ? $this->progress : null
        ];
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
