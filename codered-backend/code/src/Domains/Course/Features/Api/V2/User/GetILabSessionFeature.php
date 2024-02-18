<?php

namespace App\Domains\Course\Features\Api\V2\User;

use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Services\CyberQ\CyberQService;
use App\Domains\Course\Services\ILab\ILabService;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Models\CourseEnrollment;
use App\Foundation\Traits\Authenticated;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Course\Repositories\LessonRepositoryInterface;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\User\Enum\UserActivation;
use Log;

class GetILabSessionFeature extends Feature
{

    use Authenticated;

    public function handle(Request $request, LessonRepositoryInterface $lesson_repository, CourseRepositoryInterface $course_repository)
    {
        $user = $request->user('api');

        if ($user->activation == UserActivation::SUSPEND) {
            return response('Unauthorized.', 401);
        }

        $lesson = $lesson_repository->find($request->lesson_id);
        $course = $course_repository->find($lesson->course_id);

        if (!has_access_course($course)) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'name'    => 'message',
                    'message' => 'You must have active subscription',
                    'status' => 1001

                ]
            ]);
        }

        if ($user->all_course_enrollments()->where('course_id', $lesson->course_id)->count() == 0) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    "name"    => "message",
                    'message' => trans('You are not enrolled in this course'),
                    'status' => 1002

                ]
            ]);
        }

        $course_enrollment = CourseEnrollment::where('user_id', $user->id)->where('course_id', $course->id)->latest()->first();

        $ip_address = $request->get('ip');

        try {
            if ($lesson->type == LessonType::LAB) {
                $ilab = new ILabService();
                $response =  $ilab->createLab($lesson->ilab_id, $user, request()->ip());
            } else if ($lesson->type == LessonType::CYPER_Q || $lesson->type == LessonType::CHECKPOINT) {
                $cyberq = new CyberQService();
                $response = $cyberq->createCyberQ($user, $lesson->cyperq_id, $ip_address, $course_enrollment, $course);
            }



            $session_url = $response->data['Url'];
            if (empty($session_url)) {
                Log::error('Session url not provided-response data:' . collect($response)->toJson());
                return $this->run(RespondWithJsonErrorJob::class, [
                    'errors' => [
                        "name"    => "message",
                        'message' => trans('This lab is no longer available because you have another lab open or
                     your lab access has expired. Please close any open labs and try again, or send us
                    a message to Learnersupport@eccouncil.org to renew your license for another 6 months (.1)')
                    ]
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message'   => $e->getMessage(),
            ], 500);
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    "name"    => "message",
                    'message' => trans('This lab is no longer available because you have another lab open or
                     your lab access has expired. Please close any open labs and try again, or send us
                    a message to Learnersupport@eccouncil.org to renew your license for another 6 months.(2)')
                ]
            ]);
        }


        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'url' => $session_url,
            ]
        ]);
    }
}
