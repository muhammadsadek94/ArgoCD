<?php

namespace App\Domains\User\Features\Api\V2\User\Profile;

use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Course\Http\Resources\Api\V2\CourseBasicInfoResource;
use App\Domains\Course\Models\Course;

class EnrolledInCoursesFeature extends Feature
{

    public function handle(Request $request)
    {
        $user = $request->user('api');
        $courses = Course::whereHas('cousre_enrollments', function ($query) use ($user) {
            $query->where('user_id', $user->id)->distinct('course_id');
        })
            ->with(['image', 'category', 'cover', 'sub', 'reviews', 'category.image', 'sub.image'])
            ->withCount(['reviews' => function ($query) {
                $query->where('activation', 1);
            }])
            ->withCount('cousre_enrollments as course_enrollments_count')
            ->when($user, function ($query) use ($user) {
                $query->with(['completedPercentageLoad' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                }])
                    ->with(['lessons' => function ($query) {
                        $query->select('id', 'chapter_id', 'name', 'course_id', 'time', 'type', 'is_free', 'activation')->active()->whereHas('chapter', function ($query) {
                            $query->active();
                        });
                    }]);
            })
            ->active()
            ->get();

        $user?->load(['active_subscription' => function ($query) {
            $query->with('package');
        }]);

        $user?->load('watched_lessons:id,course_id,chapter_id');

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'courses' => CourseBasicInfoResource::collection($courses),
            ]
        ]);
    }
}
