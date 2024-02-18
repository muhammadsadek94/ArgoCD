<?php

namespace App\Domains\Course\Features\Api\V2\User;

use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Domains\Course\Enum\LessonType;
use App\Foundation\Traits\Authenticated;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Course\Repositories\LessonRepositoryInterface;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\Course\Http\Resources\Api\V2\Lesson\LessonInformationResource;
use App\Domains\Course\Models\Lesson;

class GetLessonFeature extends Feature
{

    use Authenticated;

    public function handle(Request $request, LessonRepositoryInterface $lesson_repository, CourseRepositoryInterface $course_repository)
    {
        $user = $request->user('api');
        $user?->load('all_course_enrollments');
        $user?->load(['active_subscription' => function ($query) {
            $query->with('package');
        }]);



        $lesson = Lesson::where('id', $request->lesson_id)
            ->with('course')
            ->with(['course.course_enrollments' => function ($query) use ($user) {
                $query->where('user_id', $user?->id);
            }])
            ->with(['notes' => function ($query) use ($user) {
                $query->where('user_id', $user?->id)->orderBy('created_at')
                    ->with(['course' => function ($query) {
                        $query->withCount(['reviews' => function ($query) {
                            $query->where('activation', 1);
                        }])
                            ->withCount('course_enrollments as course_enrollments_count');
                    }]);
            }])
            ->with(['lesson_tasks' => function ($query) use ($user) {
                $query->active()->orderBy('sort');
            }])
            ->with('mcq', 'resources', 'faq', 'chapter', 'course')
            ->first();

        $course = $lesson->course;

        $user?->load(['watched_lessons' => function ($query) use ($course) {
            $query->where('watched_lessons.lesson_id', $course->id)->orderBy('watched_lessons.id', 'desc')->select('name', 'lessons.id', 'time', 'type', 'lessons.chapter_id', 'sort', 'lessons.course_id')->with('course:id,slug_url');
        }]);

        if (($lesson->type == LessonType::CYPER_Q || $lesson->type == LessonType::VOUCHER || $lesson->type == LessonType::VITAL_SOURCE || $lesson->type == LessonType::DOCUMENT || $lesson->type == LessonType::LAB) && lesson_access_permission($lesson)) {
            $this->run(MarkLessonAsWatchedFeature::class, [
                'user'   => $user,
                'lesson' => $lesson
            ]);
        }



        if (!$lesson->is_free) {
            if (!has_access_course_eager($course, $user)) {
                return $this->run(RespondWithJsonErrorJob::class, [
                    'errors' => [
                        'name'    => 'message',
                        'message' => 'You must have active subscription',
                        'status' => 1001

                    ]
                ]);
            }

            if (!lesson_access_permission($lesson)) {
                return $this->run(RespondWithJsonErrorJob::class, [
                    'errors' => [
                        'name'    => 'message',
                        'message' => 'not have allowed to be here',
                        'status' => 1001

                    ]
                ]);
            }



            if ($user->all_course_enrollments->where('id', $lesson->course_id)->count() == 0) {
                return $this->run(RespondWithJsonErrorJob::class, [
                    'errors' => [
                        "name"    => "message",
                        'message' => trans('You are not enrolled in this course'),
                        'status' => 1002

                    ]
                ]);
            }

            $user->chapters_packages = collect([]);

            $subscription_id = $user->all_course_enrollments->where('id', $lesson->course_id)->sortByDesc('pivot.created_at')->first()?->pivot?->user_subscription_id;

            if ($subscription_id) {
                $user_subscription = $user->active_subscription->where('id', $subscription_id)->first();
                $user->installment_chapters = $user_subscription?->is_installment;
                $user->paid_installment_count = $user_subscription?->paid_installment_count;
                $package_subscription = $user_subscription?->package;
                $user->course_user_subscription = $user_subscription;
                $user->chapters_packages = $package_subscription?->chapters;
            }

            if (!check_lesson_drip_time($lesson)) {
                return $this->run(RespondWithJsonErrorJob::class, [
                    'errors' => [
                        'name'    => 'message',
                        'message' => 'You are not allow to access this chapter lesson',
                        'status' => 1003

                    ]
                ]);
            }
        }

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'lesson' => new LessonInformationResource($lesson),
            ]
        ]);
    }
}
