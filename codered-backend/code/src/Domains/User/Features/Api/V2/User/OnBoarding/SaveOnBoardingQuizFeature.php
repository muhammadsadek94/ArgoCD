<?php

namespace App\Domains\User\Features\Api\V2\User\OnBoarding;

use App\Domains\User\Jobs\Api\V2\User\OnBoarding\SaveOnBoardingQuizJob;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\User\Http\Requests\Api\V2\User\OnBoardingQuizRequest;


class SaveOnBoardingQuizFeature extends Feature
{

    public function handle(OnBoardingQuizRequest $request)
    {
        $user = $request->user('api');
       
        $this->run(SaveOnBoardingQuizJob::class, [
            'course_categories' => $request->course_categories,
            'course_tags'       => $request->course_tags,
            'goals'             => $request->goals,
            'level'             => $request->level,
            'user' => $user
        ]);


        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'status' => true
            ]
        ]);
    }
}
