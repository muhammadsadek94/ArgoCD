<?php

namespace App\Domains\Enterprise\Http\Resources\Api\V1\Reports;

use App\Domains\Course\Models\WatchHistoryTime;
use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\Enterprise\Http\Resources\Api\V1\User\EnterpriseDetailsResource;
use App\Domains\Enterprise\Models\License;
use App\Domains\Partner\Http\Resources\Api\V1\CourseResource;
use App\Domains\Enterprise\Http\Resources\Api\V1\User\UserCourseResource;

use App\Domains\Uploads\Http\Resources\FileResource;
use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Geography\Http\Resources\CityResource;
use App\Domains\Geography\Http\Resources\CountryResource;
use DateTime;
use DB;
use Twilio\Rest\Autopilot\V1\Assistant\ReadQueryOptions;

class EnterpriseUserReportsDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

//        dd($this->completed_courses_percentage);
        return [
            "id" => $this->id,
            "first_name" => $this->full_name,
            "company_name" => $this->company_name,
            "email" => $this->email,
            "image" => new FileResource($this->image),
            "mins_watched" => $this->mins_watched() > 0 ? number_format(($this->mins_watched()), 2, '.', '') : $this->mins_watched(),
            "enrollment" => $this->enrollment(),
            "subaccount" => new EnterpriseDetailsResource($this->enterpriseSubAccount()->first()),
            "enterprise" => new EnterpriseDetailsResource($this->enterpriseAccount()->first()),
            "join_date" => $this->created_at->format('d M Y'),
            'tags' => $this->usertags()->get(),
            'activation' => $this->activation,
            'completion_rate' => $this->completion_rate(),
            'completed_Course' => $this->completed_course(),
            'average_score' => $this->average_score() > 0 ? number_format((float)$this->average_score(), 2, '.', '') :  $this->average_score()
        ];


    }

    private function getLearnPaths()
    {
        $subscription_ids = $this->active_subscription->pluck('package_id')->toArray();
        return PackageSubscription::whereIn('id', $subscription_ids)->get()->map(function ($package) {
            $package->progress = $this->progress($package);
            return $package;
        })->toArray();

    }

    private function enrollment()
    {
        return $this->getSearchWithTIme($this->all_course_enrollments() , 'course_enrollment')->count();
    }

    private function average_score()
    {
        $query = $this->getSearchWithTIme($this->completed_courses() , 'completed_courses')->average('degree');
        return number_format((float)$query, 2, '.', '');
    }

    private function completed_course()
    {
        return $this->getSearchWithTIme($this->completed_courses() , 'completed_courses')->count();
    }

    private function mins_watched()
    {
        $query = $this->getSearchWithTIme(WatchHistoryTime::where('user_id', $this->id) , 'watch_history_times')->sum('watched_time') / 60;
        return number_format((float)$query, 2, '.', '');
    }

    private function completion_rate()
    {
        $query = $this->getSearchWithTIme($this->completed_courses_percentage() ,'completed_course_percentages')->average('completed_percentage');
        return number_format((float)$query, 2, '.', '');
    }

    private function progress($package)
    {
        $admin = request()->user('api');
        $avg_progress = DB::table("courses")
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
            ->select(DB::raw('AVG(completed_course_percentages.completed_percentage) as avg_progress'))
            ->where("package_subscriptions.id", "=", $package)
            ->where(function ($query) use ($admin) {
                $query->where('users.enterprise_id', $admin->id)
                    ->orWhere('users.subaccount_id', $admin->id);
            })
            ->first()->avg_progress;
        return number_format((float)$avg_progress, 2, '.', '');;
    }

    private function getSearchWithTIme($query ,$table)
    {

        $request = Request();
        if (isset($request->start_date) || isset($request->end_date)) {

            $query = $query->when($request->start_date, function ($query) use ($request , $table) {
                return $query->where(function ($query) use ($request ,$table) {
                    return $query->where( $table .'.created_at', ">=", $request->start_date);
                });
            });

            $query = $query->when($request->end_date, function ($query) use ($request , $table) {
                return $query->where(function ($query) use ($request ,$table) {
                    $end_date =new DateTime( $request->end_date);
                    $end_date= $end_date->modify('+1 day');
                    return $query->whereDate( $table .'.created_at', "<=",$end_date );

                });
            });
        } else {
            // $query = $query->where('created_at', ">", Carbon::now()->subMonth()->format('Y-m-d'));
        }
        return $query;
    }

}
