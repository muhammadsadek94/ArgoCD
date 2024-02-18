<?php

namespace App\Domains\Enterprise\Http\Resources\Api\V1\Reports\SubAccount;

use App\Domains\Course\Models\CompletedCoursePercentage;
use App\Domains\Course\Models\CompletedCourses;
use App\Domains\Course\Models\CourseEnrollment;
use App\Domains\Course\Models\WatchHistoryTime;
use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\Enterprise\Http\Resources\Api\V1\User\EnterpriseDetailsResource;
use App\Domains\Enterprise\Models\License;
use App\Domains\Partner\Http\Resources\Api\V1\CourseResource;
use App\Domains\Enterprise\Http\Resources\Api\V1\User\UserCourseResource;

use App\Domains\Uploads\Http\Resources\FileResource;
use Carbon\Carbon;
use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Geography\Http\Resources\CityResource;
use App\Domains\Geography\Http\Resources\CountryResource;
use DateTime;
use DB;

class EnterpriseSubAccountReportsDetailsResource extends JsonResource
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
            "id"                => $this->id,
            "first_name"        => $this->company_name,
            "email"             => $this->email,
            "image"             => new FileResource($this->image),
            "mins_watched"      => $this->mins_watched(),
            "enrollment"        => $this->enrollment(),
            "join_date"         => $this->created_at ? $this->created_at->format('d M Y') : Carbon::now()->format('d M Y'),
            'tags'              => $this->usertags()->get(),
            'activation'        => $this->activation,
            'completion_rate'   => $this->completed_courses_percentage(),
            'average_score'     => $this->average_score(),
            'completed_Course' => $this->completed_courses()
        ];


    }
    private function mins_watched()
    {
        $query = WatchHistoryTime::rightJoin( 'users', function ($join)   {
            $join->on(  'watch_history_times' . '.user_id', '=', 'users.id');
        })->  where('subaccount_id', $this->id);
        $query = $this->getSearchWithTIme($query ,'watch_history_times' );
        return number_format($query->sum('watched_time') / 60,2, '.', '');

    }

    private function enrollment()
    {
        $query = CourseEnrollment::join( 'users', function ($join)   {
            $join->on(  'course_enrollment' . '.user_id', '=', 'users.id');
        })->  where('subaccount_id', $this->id);
        $query = $this->getSearchWithTIme($query , 'course_enrollment');
//        dd($query->dd());
        return $query->count();

    }

    private function average_score()
    {
        $query = CompletedCourses::rightJoin( 'users', function ($join)   {
            $join->on(  'completed_courses' . '.user_id', '=', 'users.id');
        })->  where('subaccount_id', $this->id);
        $query = $this->getSearchWithTIme($query,'completed_courses');
        return number_format($query->average('degree'), 2, '.', '');

    }
    private function completed_courses()
    {
        $query = CompletedCourses::rightJoin( 'users', function ($join)   {
            $join->on(  'completed_courses' . '.user_id', '=', 'users.id');
        })->  where('subaccount_id', $this->id);
        $query = $this->getSearchWithTIme($query,'completed_courses');
        return number_format($query->count(), 2, '.', '');

    }

    private function completed_courses_percentage()
    {

        $query = CompletedCoursePercentage::leftJoin( 'users', function ($join)   {
            $join->on(  'completed_course_percentages' . '.user_id', '=', 'users.id');
        })->  where('subaccount_id', $this->id);
        $query = $this->getSearchWithTIme($query,"completed_course_percentages");
        return number_format($query->average('completed_percentage'), 2, '.', '');

    }


    private function getSearchWithTIme($query ,$table)
    {
        $request = Request();
        if (isset($request->start_date) || isset($request->end_date)) {
            $query = $query->when($request->start_date, function ($query) use ($request , $table) {
                return $query->where(function ($q) use ($request ,$table) {
                    return $q->where($table . '.created_at', ">", $request->start_date);
                });
            });

            $query = $query->when($request->end_date, function ($query) use ($request ,$table) {
                return $query->where(function ($q) use ($request ,$table) {
                    $end_date =new DateTime( $request->end_date);
                    $end_date= $end_date->modify('+1 day');
                    return $q->whereDate($table .'.created_at', "<=",$end_date );
                });
            });
        } else {
            // $query = $query->where('created_at', ">", Carbon::now()->subMonth()->format('Y-m-d'));
        }
        return $query;
    }

}
