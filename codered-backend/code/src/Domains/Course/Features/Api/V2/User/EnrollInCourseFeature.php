<?php

namespace App\Domains\Course\Features\Api\V2\User;

use App\Domains\Course\Events\Course\CourseEnrollment;
use App\Domains\Course\Models\Course;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Events\Course\CourseEnrollmentInLearnPath;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Course\Jobs\Api\V2\User\EnrollInCourseJob;
use App\Domains\Course\Jobs\Api\V2\User\IsEnrollCourseJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\Payments\Models\LearnPathCourse;
use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\User\Models\User;
use Carbon\Carbon;

class EnrollInCourseFeature extends Feature
{

    public function handle(Request $request, CourseRepositoryInterface $course_repository)
    {

        $user = $request->user('api');
        //        $course = $course_repository->find($course_id);
        $course = Course::where(['id' => $request->course_id])
            ->orWhere(['slug_url' => $request->course_id])->firstOrFail();
        $course_id = $course->id;

        if (!has_access_course($course)) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'name'    => 'message',
                    'message' => 'You must have active subscription',
                    'status'  => 1001

                ]
            ]);
        }

        if ($course->course_type == CourseType::MICRODEGREE) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'name'    => 'message',
                    'message' => 'You are not applicable to enroll in microdegree',
                    'status'  => 1002

                ]
            ]);
        }

        $is_exists = $this->run(IsEnrollCourseJob::class, [
            'user' => $user,
            'course_id' => $course_id
        ]);

        if(!$is_exists) {

            $this->run(EnrollInCourseJob::class, [
                'user'      => $user,
                'course_id' => $course_id,
            ]);
            $learn_path = $this->getLearnPathNameIfExists($user, $course);
            if($learn_path) {
                event(new CourseEnrollmentInLearnPath($course, $user, $learn_path));
            } else {
                event(new CourseEnrollment($course, $user));
            }

        }

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'status' => true
            ]
        ]);
    }

    private function getLearnPathNameIfExists(User $user, Course $course)
    {
        $subscription_ids =  $user->purchased_subscription()
            ->where('expired_at', '>=', Carbon::now()->format('Y-m-d'))
            ->pluck('package_id')
            ->toArray();

        $learn_paths_ids = PackageSubscription::whereIn('id', $subscription_ids)
            ->whereNotNull('learn_path_id')
            ->pluck('learn_path_id');

        $learn_path = LearnPathCourse::whereIn('learn_path_id', $learn_paths_ids)
            ->where('course_id', $course->id)
            ->first();

        if($learn_path) {
            return $learn_path->learnPath;
        }

        return false;
    }
}
