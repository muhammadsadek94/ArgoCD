<?php

namespace App\Domains\Enterprise\Features\Api\V1\LearnPath;

use App\Domains\Course\Repositories\CourseTagsRepositoryInterface;
use App\Foundation\Traits\Authenticated;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonJob;


class GetFillterTagsFeature extends Feature
{
    use Authenticated;

    /**
     * Create a new feature instance
     */
    public function __construct()
    {

    }


    public function handle(Request $request, CourseTagsRepositoryInterface $course_repository)
    {
        $tags = $course_repository->getFilterCourseTag($request->limit ? $request->limit : 20 ,$request->search);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'tags' => $tags
            ]
        ]);
    }
}
