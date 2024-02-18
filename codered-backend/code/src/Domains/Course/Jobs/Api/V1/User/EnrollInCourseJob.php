<?php

namespace App\Domains\Course\Jobs\Api\V1\User;

use App\Domains\Course\Models\CompletedCoursePercentage;
use App\Domains\Course\Models\CourseEnrollment;
use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Job;

class EnrollInCourseJob extends Job
{
    private $user;
    private $course_id;
    private $expired_at_date;

    /**
     * Create a new job instance.
     *
     * @param User   $user
     * @param string $course_id
     * @param null   $expired_at_date
     */
    public function __construct(User $user, string $course_id, $expired_at_date = null)
    {
        $this->user = $user;
        $this->course_id = $course_id;
        $this->expired_at_date = $expired_at_date;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        CourseEnrollment::create([
            'user_id' => $this->user->id,
            'course_id' => $this->course_id,
            'expired_at' => $this->expired_at_date
        ]);
        
        CompletedCoursePercentage::firstOrCreate([
            'user_id'              => $this->user->id,
            'course_id'            =>  $this->course_id,
            'completed_percentage' => '0',
            'is_finished' => false
        ]);
    }
}
