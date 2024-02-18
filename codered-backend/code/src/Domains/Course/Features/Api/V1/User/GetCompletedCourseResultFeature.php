<?php

namespace App\Domains\Course\Features\Api\V1\User;

use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Models\CompletedCourses;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\Course\Http\Resources\Api\V1\AssessmentsResource;
use App\Domains\Course\Repositories\AssessmentsRepositoryInterface;
use App\Domains\Course\Http\Resources\Api\V1\CompletedCourseResource;

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
