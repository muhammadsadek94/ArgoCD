<?php

namespace App\Domains\Course\Jobs\Api\V1;

use App\Domains\Course\Models\FinalAssessmentTimer;
use Carbon\Carbon;
use INTCore\OneARTFoundation\Job;

class EndFinalAssessmentJob extends Job
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
        $this->course_id = $course_id;
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $final_assessment = FinalAssessmentTimer::where('course_id', $this->course_id)->where('user_id', $this->user_id)->first();
        if ($final_assessment) {
            $final_assessment->ended_at = Carbon::now();
            $final_assessment->save();
        }

    }
}
