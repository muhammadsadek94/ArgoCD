<?php

namespace App\Domains\Course\Jobs\Api\V2\User;

use App\Domains\Course\Models\CompletedCoursePercentage;
use App\Domains\Course\Models\CourseEnrollment;
use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Job;

class IsEnrollCourseJob extends Job
{
    private $user;
    private $course_id;


    /**
     * Create a new job instance.
     *
     * @param User   $user
     * @param string $course_id
     * @param null   $expired_at_date
     */
    public function __construct(User $user, string $course_id)
    {
        $this->user = $user;
        $this->course_id = $course_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return CourseEnrollment::where(['user_id' => $this->user->id, 'course_id' => $this->course_id])->exists();
    }
}
