<?php

namespace App\Domains\Course\Jobs\Api\V2\User;

use App\Domains\Payments\Enum\AccessType;
use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\CourseEnrollment;
use App\Domains\Course\Models\Lesson;
use App\Domains\Course\Models\WatchedLesson;
use App\Domains\User\Enum\SubscribeStatus;
use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Job;

class MarkLessonAsWatchedJob extends Job
{
    /**
     * @var User
     */
    private $user;
    private $lesson;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param      $lesson
     */
    public function __construct(User $user, Lesson $lesson)
    {
        $this->user = $user;
        $this->lesson = $lesson;
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
        if ($course->course_type == CourseType::COURSE) {
            return WatchedLesson::firstOrCreate([
                'lesson_id'         => $this->lesson->id,
                'chapter_id'        => $this->lesson->chapter_id,
                'course_id'         => $this->lesson->course_id,
                'subscription_type' => $subscription_status,
                'user_id'           => $this->user->id
            ]);
        }
        if ($course->course_type == CourseType::MICRODEGREE || $course->course_type == CourseType::COURSE_CERTIFICATION) {
//            $enroll = CourseEnrollment::where(['user_id' => $this->user->id, 'course_id' => $course->id])->first();
            return WatchedLesson::firstOrCreate([
                'lesson_id'         => $this->lesson->id,
                'chapter_id'        => $this->lesson->chapter_id,
                'course_id'         => $this->lesson->course_id,
                'subscription_type' => $subscription_status,
                'user_id'           => $this->user->id
            ]);
        }

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
        
        elseif ($course->course_type == CourseType::COURSE_CERTIFICATION) {
            return $user->certifications_enrollments()->where('course_id', $course->id)->first()->subscription_type;
        }


        return SubscribeStatus::TRIAL;
    }
}
