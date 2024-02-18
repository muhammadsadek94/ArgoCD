<?php

namespace App\Domains\User\Features\Api\V2\Instructor\Profile;

use App\Domains\User\Enum\UserType;
use App\Domains\User\Http\Resources\Api\V2\Instructor\InstructorResource;
use App\Domains\User\Models\User;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;

class GetInstructorFeature extends Feature
{
    public function handle(Request $request)
    {

        $user = $request->user('api');

        $instructor = User::where('type', UserType::PROVIDER)
            ->with(['instructor_courses' => function ($query) use ($user) {
                $query->active()
                    ->with(['image', 'category', 'cover', 'sub', 'reviews', 'category.image', 'sub.image', 'cousre_enrollments'])
                    ->withCount(['reviews' => function ($query) {
                        $query->where('activation', 1);
                    }])
                    ->withCount('course_enrollments as course_enrollments_count')
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
                    ->orderBy('course_enrollments_count', 'desc');
            }])
            ->find($request->id);



        if (!$instructor) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'message' => 'Instructor not exists!',
                ]
            ]);
        }

        $user?->load(['active_subscription' => function ($query) {
            $query->with('package');
        }]);

        $instructor->instructor_courses_average_rate = $instructor->instructor_courses->map(function ($course) {
            return $course->reviews->avg('rate');
        })->avg();

        return $this->run(RespondWithJsonJob::class, [
            'content' => [
                'instructor'        => new InstructorResource($instructor),
            ]
        ]);
    }
}
