<?php

namespace App\Domains\Course\Features\Api\V2\User;

use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Domains\Course\Models\CompletedCourses;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Course\Http\Resources\Api\V2\CompletedCourseResource;

class GetCompletedCourseResultFeature extends Feature
{

    /**
     * Create a new feature instance
     */
    public function __construct()
    {

    }

    public function handle(Request $request, CompletedCourses $completed_courses)
    {
        $result = $completed_courses->findOrFail($request->id);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'result' => new CompletedCourseResource($result)
            ]
        ]);
    }
}
