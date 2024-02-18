<?php

namespace App\Domains\Course\Features\Api\V2\User;

use App\Domains\Course\Http\Requests\Api\SubmitSurveyRequest;
use App\Domains\Course\Models\UserCourseSurvey;
use INTCore\OneARTFoundation\Feature;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Foundation\Http\Jobs\RespondWithJsonJob;

class SubmitCourseSurvey extends Feature
{

    public function handle(SubmitSurveyRequest $request, CourseRepositoryInterface $course_repository)
    {

        $user = $request->user('api');

        $request->merge([
            'user_id' => $user->id,
            'survey' => $request->survey
        ]);

        UserCourseSurvey::create($request->all());

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'message' => 'success'
            ]
        ]);
    }
}
