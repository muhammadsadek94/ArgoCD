<?php

namespace App\Domains\Enterprise\Http\Resources\Api\V1\Reports;

use App\Domains\Course\Models\CompletedCoursePercentage;
use App\Domains\Course\Models\CompletedCourses;
use App\Domains\Course\Models\CourseEnrollment;
use App\Domains\Course\Models\CourseReview;
use App\Domains\Course\Models\WatchHistoryTime;

use App\Domains\Uploads\Http\Resources\FileResource;
use App\Domains\User\Enum\UserType;
use App\Domains\User\Models\User;
use DateTime;
use http\Env\Request;
use INTCore\OneARTFoundation\Http\JsonResource;

class EnterpriseCourseReportsDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $mins_watched = $this->mins_watched();
        $completed_courses_percentage = $this->completed_courses_percentage();
        $course_avg_rate = $this->course_avg_rate();
        $average_score = $this->average_score();
        return [
            "id" => $this->id,
            "name" => $this->name,
            "image" => new FileResource($this->image),
            "mins_watched" => number_format($mins_watched ? $mins_watched : 0, 2, '.', ''),
            "enrollment" => $this->cousre_enrollments_by_enterprise->count(),
            "join_date" => $this->created_at->format('d M Y'),
            'activation' => $this->activation,
            'completion_rate' =>number_format($completed_courses_percentage ? $completed_courses_percentage: 0, 2, '.', ''),
            'average_rate' =>number_format($course_avg_rate ? $course_avg_rate : 0, 2, '.', ''),
            'average_score' => number_format($average_score ? $average_score : 0, 2, '.', '')
        ];


    }

    private function mins_watched()
    {
        return $this->watch_history_time_by_enterprise->sum('pivot.watched_time')/60;

    }


    private function average_score()
    {

        return $this->completed_course_by_enterprise->average('pivot.degree');
    }

    private function completed_courses_percentage()
    {
        return $this->completed_course_percentages_by_enterprise->average('pivot.completed_percentage');
    }

    private function course_avg_rate()
    {
        return $this->course_review_by_enterprise->average('pivot.rate');

    }



    private function getSearchWithTIme($query)
    {
        $request = $this->request;
        if (isset($request->start_date) || isset($request->end_date)) {

            $query = $query->when($request->start_date, function ($query) use ($request) {
                return $query->where(function ($q) use ($request) {
                    return $q->where('created_at', ">=", $request->start_date);
                });
            });

            $query = $query->when($request->end_date, function ($query) use ($request) {
                return $query->where(function ($q) use ($request) {
                    $end_date =new DateTime( $request->end_date);
                    $end_date= $end_date->modify('+1 day');
                    return $q->whereDate('created_at', "<=",$end_date );
                });
            });
        } else {
            // $query = $query->where('created_at', ">", Carbon::now()->subMonth()->format('Y-m-d'));
        }
        return $query;
    }

}
