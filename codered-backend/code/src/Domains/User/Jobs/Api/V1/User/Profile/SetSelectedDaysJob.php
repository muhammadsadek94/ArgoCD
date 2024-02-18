<?php

namespace App\Domains\User\Jobs\Api\V1\User\Profile;

use App\Domains\User\Models\User;
use Carbon\Carbon;
use INTCore\OneARTFoundation\Job;

class SetSelectedDaysJob extends Job
{
    /**
     * @var User
     */
    private $user;
    private $target;
    private $course_id;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param $target
     */
    public function __construct(User $user, $target, $course_id)
    {
        $this->user = $user;
        $this->target = $target;
        $this->course_id = $course_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->user->microdegree_certifications_enrollments()->where('course_id', $this->course_id)->update([
            'selected_days' => $this->target,
            'weekly_target' => count($this->target),
            'week_start_date' =>  Carbon::now()->startOfWeek()->format('Y-m-d'),
            'week_end_date' => Carbon::now()->endOfWeek()->format('Y-m-d'),
        ]);
    }
}
