<?php

namespace App\Domains\Course\Features\Api\V1\User;

use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Course\Http\Resources\Api\V1\CompletedCourseResource;

class GetUserCertificates extends Feature
{
    public function handle(Request $request)
    {
        $completed_courses = $request->user('api')->completed_courses;
        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'certificates' => CompletedCourseResource::collection($completed_courses)
            ]
        ]);
    }
}
