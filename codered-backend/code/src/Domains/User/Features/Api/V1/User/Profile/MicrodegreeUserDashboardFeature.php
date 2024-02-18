<?php

namespace App\Domains\User\Features\Api\V1\User\Profile;

use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\User\Repositories\UserRepository;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\Course\Repositories\MicrodegreeRepositoryInterface;
use App\Domains\Course\Http\Resources\Api\V1\CourseBasicInfoResource;
use App\Domains\Course\Http\Resources\Api\V1\Lesson\LessonBasicInfoResource;
use App\Domains\Course\Http\Resources\Api\V1\Microdegree\MicrodegreeWithChaptersResource;


class MicrodegreeUserDashboardFeature extends Feature
{

    public function handle(Request $request, UserRepository $user_repository, CourseRepositoryInterface $course_repository, MicrodegreeRepositoryInterface $microdegree_repository)
    {
        $user = $request->user('api');
        $micro_degree_id = $request->micro_degree_id;

        $micro_degree = $microdegree_repository->getMicroDegreeById($micro_degree_id);
        $micro_degree_id=$micro_degree->id;
        $watched_lessons = $user_repository->countWatchedVideosTodayMicrodegrees($user->id, $micro_degree_id);
        $activities = $user_repository->getLastActivitiesMicrodegrees($user->id, $micro_degree_id);
        $recommended_to_watch = $user_repository->getUpComingRecommendedMicrodegree($user->id, $micro_degree_id);
        $completion = $user_repository->getMicrodegreeCompletionProgress($user->id, $micro_degree_id);

        $last_watched_lesson = $user_repository->getLastWatchedLessonMicrodegree($user->id, $micro_degree_id);

        $inprogress_courses = $user_repository->getInProgressCourses($user->id);
        $recommended_courses = $course_repository->getRecommendedCourses($user);
        $coming_soon = $course_repository->getComingSoonCourses();


        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'micro_degree'        => new MicrodegreeWithChaptersResource($micro_degree),
                'last_watched_lesson' => $last_watched_lesson ? new LessonBasicInfoResource($last_watched_lesson) : null,
                'goals'               => [
                    'daily_target'         => $user->daily_target,
                    'watched_lessons'      => $watched_lessons,
                    'activities'           => $activities,
                    'recommended_to_watch' => LessonBasicInfoResource::collection($recommended_to_watch),
                    'completion' => $completion
                ],
                'inprogress_courses'  => CourseBasicInfoResource::collection($inprogress_courses),
                'recommended_courses' => CourseBasicInfoResource::collection($recommended_courses),
                'most_coming_soon'    => CourseBasicInfoResource::collection($coming_soon),

            ]
        ]);
    }
}
