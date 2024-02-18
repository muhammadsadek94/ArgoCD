<?php

namespace App\Domains\Course\Features\Api\V2\User;

use App\Domains\Course\Enum\ProjectApplicationStatus;
use App\Domains\Course\Http\Requests\Api\V2\AddProjectApplicationRequest;
use App\Domains\Course\Http\Resources\Api\V2\Lesson\ProjectApplicationResource;
use App\Domains\Course\Jobs\Api\V2\User\AddProjectApplicationJob;
use App\Domains\Course\Jobs\Api\V2\User\CheckExistProjectApplicationJob;
use App\Domains\Course\Models\Lesson;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;


class AddProjectApplicationFeature extends Feature
{

    /**
     * Create a new feature instance
     */
    public function __construct()
    {

    }


    public function handle(AddProjectApplicationRequest $request)
    {
        $lesson = Lesson::find($request->lesson_id);
        if(!$lesson) {
            return $this->run(RespondWithJsonErrorJob::class, [
                "errors" => [
                    'name' => 'message',
                    "message" => 'no lesson found'
                ]
            ]);
        }

        $projectApplication = $this->run(CheckExistProjectApplicationJob::class, [
            'user' => $request->user('api'),
            'lesson' => $lesson,
        ]);

        if($projectApplication && $projectApplication->status == ProjectApplicationStatus::UNDER_REVIEW) {
            return $this->run(RespondWithJsonErrorJob::class, [
                "errors" => [
                    'name' => 'message',
                    "message" => 'Already under review'
                ]
            ]);
        }

        $newProjectApplication = $this->run(AddProjectApplicationJob::class, [
            'user' => $request->user('api'),
            'request' => $request,
            'lesson' => $lesson
        ]);

        if(!$newProjectApplication) {
            return $this->run(RespondWithJsonErrorJob::class, [
                "errors" => [
                    'name' => 'message',
                    "message" => 'request cannot be resolved'
                ]
            ]);
        }
        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'project_application' => new ProjectApplicationResource( $newProjectApplication)
            ]
        ]);
    }
}
