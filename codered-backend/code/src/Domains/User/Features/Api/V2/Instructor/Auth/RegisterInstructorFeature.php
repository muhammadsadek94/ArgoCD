<?php

namespace App\Domains\User\Features\Api\V2\Instructor\Auth;

use App\Domains\Uploads\Jobs\MarkFileAsUsedJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;
use App\Domains\User\Jobs\Api\V2\Instructor\Auth\RegisterInstructorJob;
use App\Domains\User\Http\Requests\Api\V2\Instructor\RegisterInstructorRequest;

class RegisterInstructorFeature extends Feature
{
    public function handle(RegisterInstructorRequest $request)
    {
        // register user in database
        $user = $this->run(RegisterInstructorJob::class, [
            'request' => $request
        ]);


        $this->markFilesAsUsed($request);


        return $this->run(RespondWithJsonJob::class, [
            'content' => [
                'message' => 'Thank you for registration'
            ]
        ]);
    }

    private function markFilesAsUsed(RegisterInstructorRequest $request)
    {
        // mark user profile image in use
        if($request->image_id) {
            $this->run(MarkFileAsUsedJob::class, [
                'file_id' => $request->image_id
            ]);
        }

        // cv
        if($request->cv_id) {
            $this->run(MarkFileAsUsedJob::class, [
                'file_id' => $request->cv_id
            ]);
        }

        // cv
        if($request->video_sample_id) {
            $this->run(MarkFileAsUsedJob::class, [
                'file_id' => $request->video_sample_id
            ]);
        }
    }
}
