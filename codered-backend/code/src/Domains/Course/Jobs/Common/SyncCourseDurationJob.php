<?php

namespace App\Domains\Course\Jobs\Common;

use INTCore\OneARTFoundation\Job;
use App\Domains\Course\Models\Course;

class SyncCourseDurationJob extends Job
{

    /**
     * @var Course
     */
    private $course;

    /**
     * Create a new job instance.
     *
     * @param Course $course
     */
    public function __construct(Course $course)
    {
        $this->course = $course;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $total_duration = $this->course->lessons()->sum('time') / 60;

        $this->course->update([
            'timing' => $total_duration
        ]);
    }

}
