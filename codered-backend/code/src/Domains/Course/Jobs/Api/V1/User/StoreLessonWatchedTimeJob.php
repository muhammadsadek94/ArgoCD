<?php

namespace App\Domains\Course\Jobs\Api\V1\User;

use App\Domains\Payments\Enum\AccessType;
use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\CourseEnrollment;
use App\Domains\Course\Models\Lesson;
use App\Domains\Course\Models\WatchedLesson;
use App\Domains\Course\Models\WatchHistoryTime;
use App\Domains\User\Enum\SubscribeStatus;
use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Job;

class StoreLessonWatchedTimeJob extends Job
{
    /**
     * @var User
     */
    private $user;
    private $lesson;
    private $watched_time;

    /**
     * @var string
     */

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param      $lesson
     * @param      $time
     */
    public function __construct(User $user, Lesson $lesson, string $watched_time)
    {
        $this->user = $user;
        $this->lesson = $lesson;
        $this->watched_time = $watched_time;


    }

    /**
     * Execute the job.
     *
     * @return WatchedLesson|\Illuminate\Database\Eloquent\Model
     */
    public function handle()
    {
        $course = $this->lesson->course;
        $subscription_status = $this->getSubscriptionStatus($course);
//        dd($subscription_status);

        if ($this->lesson->type != LessonType::VIDEO) return;
        return WatchHistoryTime::firstOrCreate([
            'lesson_id' => $this->lesson->id,
            'course_id' => $this->lesson->course_id,
            'subscription_type' => $subscription_status,
            'course_type' => $course->course_type,
            'user_id' => $this->user->id,
            'instructor_id' => $course->user_id,
            'watched_time' => $this->watched_time,
            'status' => false,
            'created_at' => now()
        ]);

    }

    private function getSubscriptionStatus(Course $course)
    {
        $user = $this->user;
        if ($course->course_type == CourseType::COURSE) {
            if ($subscription = $user->hasActiveSubscription(AccessType::PRO)) {
                return $subscription->status ?? SubscribeStatus::TRIAL;
            }
            if ($subscription = $user->allowedToAccessCategory($course->course_category_id)) {

                return $subscription->status ?? SubscribeStatus::TRIAL;

            }
            if ($subscription = $user->allowedToAccessCourse($course->id)) {

                return $subscription->status ?? SubscribeStatus::TRIAL;
            }

        } elseif ($course->course_type == CourseType::MICRODEGREE) {
            return $user->microdegree_enrollments()->where('course_id', $course->id)->first()->subscription_type;
        }
        return SubscribeStatus::TRIAL;
    }

}
