<?php

namespace App\Domains\Challenge\Http\Controllers\Api\V2;

use App\Domains\Challenge\Features\Api\V2\CompetitionCompletedFeature;
use App\Domains\Challenge\Features\Api\V2\FlagSubmissionFeature;
use App\Domains\Challenge\Features\Api\V2\CreateChallengeSessionFeature;
use App\Domains\Challenge\Features\Api\V2\GetChallengeByIdFeature;
use INTCore\OneARTFoundation\Http\Controller;

class ChallengeController extends Controller
{

    public function show()
    {
        return $this->serve(GetChallengeByIdFeature::class);
    }
    
    public function flagSubmission()
    {
        return $this->serve(FlagSubmissionFeature::class);
    }

    public function competitionCompleted()
    {
        return $this->serve(CompetitionCompletedFeature::class);
    }

    public function getChallengeSession()
    {
        return $this->serve(CreateChallengeSessionFeature::class);
    }
    
    
}