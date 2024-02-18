<?php

namespace App\Domains\Course\Jobs\Api\V1;

use App\Domains\Course\Models\FinalAssessmentTimer;
use App\Domains\Course\Repositories\AssessmentsAnswerRepository;
use Carbon\Carbon;
use DateTime;
use INTCore\OneARTFoundation\Job;

class StartFinalAssessmentJob extends Job
{
    private $user_id;
    private $course_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id, $course_id)
    {
        $this->course_id = $course_id;
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     *
     */
    public function handle(AssessmentsAnswerRepository $assessments_answer_repository)
    {

        $assessments = $assessments_answer_repository->getCourseAssessmentsDataBase($this->course_id, $this->user_id);
        if (count( $assessments) >0) { // start timer if micro degree has already assessments
            $final_assessment_timer = FinalAssessmentTimer::where('course_id', $this->course_id)->where('user_id', $this->user_id)->first();


            if ($final_assessment_timer) {
                $diffTime = Carbon::parse($final_assessment_timer->end_time)->gt(now());
                if(!$diffTime) return null;
                return $final_assessment_timer;
            } else {
                $now = Carbon::now();

                return FinalAssessmentTimer::create([
                    "user_id" => $this->user_id,
                    "course_id" => $this->course_id,
                    "started_at" => $now,
                    "start_time" => $now->timestamp,
                    "end_time" => $now->addHour()->timestamp
                ]);
            }
        }

    }
}
