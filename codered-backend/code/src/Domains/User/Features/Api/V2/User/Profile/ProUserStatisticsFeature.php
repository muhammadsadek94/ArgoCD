<?php

namespace App\Domains\User\Features\Api\V2\User\Profile;

use App\Domains\User\Repositories\UserRepository;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;

class ProUserStatisticsFeature extends Feature
{

    public function __construct(){}

    public function handle(Request $request, UserRepository $user_repository)
    {
        
        $user = $request->user();

        $lessons_completed = $user_repository->lessonsCompletedForOneMonth($user);
        $mins_watched = $user_repository->minsWatchedThisWeek($user);
        $total_lessons_watched = $user_repository->totalLessonsWatched($user);
        $total_mins_watched = $user_repository->totalMinsWatched($user);
        $total_completed_courses = $user_repository->totalCompletedCourses($user);
        $total_enrolled_courses = $user_repository->totalEnrolledCourses($user);
        $total_lessons_watched_today  = $user_repository->countWatchedVideosThisWeek($user->id);
        $completion_percentage = $user->daily_target ? ceil(($total_lessons_watched_today / $user->daily_target) * 100) . '%' : '0%';
        

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
              'lessons_completed'                   => $lessons_completed,
              'mins_watched'                        => $mins_watched,
              'daily_target'                        => $user->daily_target,
              'total_lessons_watched'               => $total_lessons_watched,
              'total_mins_watched'                  => $total_mins_watched,
              'total_enrolled_courses'              => $total_enrolled_courses,
              'total_completed_courses'             => $total_completed_courses,
              'total_lessons_watched_today'         => $total_lessons_watched_today,
              'completion_percentage'               => $completion_percentage,
            ]
        ]);
    }


}
