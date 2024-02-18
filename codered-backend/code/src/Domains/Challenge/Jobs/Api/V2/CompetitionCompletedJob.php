<?php

namespace App\Domains\Challenge\Jobs\Api\V2;

use App\Domains\Challenge\Models\Challenge;
use App\Domains\Challenge\Models\UserCompetition;
use INTCore\OneARTFoundation\Job;

class CompetitionCompletedJob extends Job
{

    private $request;
    private $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request, $data)
    {
        $this->request = $request;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $this->data['competition_id'] = $this->request->CompetitionId;
        $this->data['event_id'] = $this->request->EventId;
        $this->data['is_lab_launched'] = !!$this->request->IsLabLaunched;
        $this->data['is_lab_completed'] = !!$this->request->IsLabCompleted;
        $this->data['started_at'] = $this->request->StartDatetime;
        $this->data['completed_at'] = $this->request->EndDateTime;
        $this->data['total_score'] = $this->request->TotalScore;
        $this->data['exam_score'] = $this->request->ExamScore;
        $this->data['lab_type'] = $this->request->LabType;
        $this->data['challenge_id'] =  Challenge::where('competition_id', $this->request->CompetitionId)->first()->id;

        $flag = UserCompetition::updateOrCreate(
            [
                'user_id' => $this->data['user_id'] ?? null,
                'guest_id' => $this->data['guest_id'] ?? null,
                'competition_id' => $this->data['competition_id'],
            ],
            $this->data
        );

        return !!$flag;
    }
}
