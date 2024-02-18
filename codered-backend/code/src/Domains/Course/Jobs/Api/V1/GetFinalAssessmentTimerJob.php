<?php

namespace App\Domains\Course\Jobs\Api\V1;

use App\Domains\Course\Models\FinalAssessmentTimer;
use Carbon\Carbon;
use INTCore\OneARTFoundation\Job;

class GetFinalAssessmentTimerJob extends Job
{
    private $user_id;
    private $course_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($course_id, $user_id)
    {
        $this->user_id = $user_id;
        $this->course_id = $course_id;
    }

    /**
     * Execute the job.
     *
     */
    public function handle()
    {
        $max_time = config('course.services.cyberq.final_assessment');

        $final_assessment = FinalAssessmentTimer::where('course_id', $this->course_id)->where('user_id', $this->user_id)->first();
        if ($final_assessment) {
            if (!$final_assessment->ended_at) {

                return $final_assessment->time;
            } elseif ($final_assessment->time > $max_time) {
                $final_assessment->started_at = Carbon::now();
                return $final_assessment->time;
            }
            return $final_assessment->time;
        }
        return 0;
    }
}
