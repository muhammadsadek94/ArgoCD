<?php

namespace App\Domains\Challenge\Jobs\Api\V2;

use App\Domains\Challenge\Models\Challenge;
use App\Domains\Challenge\Models\UserFlag;
use INTCore\OneARTFoundation\Job;

class SubmitFlagJob extends Job
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
        $this->data['flag_id'] = $this->request->FlagId;
        $this->data['total_time'] = $this->request->TotalTime;
        $this->data['time_taken'] = $this->request->TimeTaken;
        $this->data['user_score'] = $this->request->UserScore;
        $this->data['challenge_id'] =  Challenge::where('competition_id', $this->request->CompetitionId)->first()->id;

        $flag = UserFlag::updateOrCreate(
            [
                'user_id' => $this->data['user_id'] ?? null,
                'guest_id' => $this->data['guest_id'] ?? null,
                'competition_id' => $this->data['competition_id'],
                'flag_id' => $this->data['flag_id'],
            ],
            $this->data
        );

        return !!$flag;
    }
}
