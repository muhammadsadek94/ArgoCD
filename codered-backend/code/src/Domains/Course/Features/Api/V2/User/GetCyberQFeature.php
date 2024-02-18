<?php

namespace App\Domains\Course\Features\Api\V2\User;

use App\Domains\Course\Models\CourseEnrollment;
use App\Foundation\Traits\Authenticated;
use App\Domains\Course\Services\CyberQ\CyberQService;
use App\Domains\Course\Repositories\LessonRepositoryInterface;
use App\Domains\User\Enum\UserActivation;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use Log;

class GetCyberQFeature extends Feature
{

    use Authenticated;

    public function handle(Request $request, LessonRepositoryInterface $lesson_repository)
    {

        $user = $request->user('api');

        $lesson = $lesson_repository->find($request->lesson_id);

        if ($user->activation == UserActivation::SUSPEND) {
            return response('Unauthorized.', 401);
        }

        $ip_address = $request->get('ip');

        if (!filter_var($ip_address, FILTER_VALIDATE_IP)) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    "name"    => "message",
                    'message' => trans('Not a valid IP Address'),
                    'status' => 1002
                ]
            ]);
        }

        $course_enrollment = CourseEnrollment::where('user_id', $user->id)->where('course_id', $lesson->course_id)->latest()->first();

        $cyberq = new CyberQService();
        $response = $cyberq->createCyberQ($user, $lesson->cyperq_id, $ip_address, $course_enrollment, $lesson->course);

        $session_url = $response->data['Url'];
        if (empty($session_url)) {
            Log::error('Session url not provided-response data:' . collect($response)->toJson());
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    "name"    => "message",
                    'message' => trans('This lab is no longer available because you have another lab open or
                     your lab access has expired. Please close any open labs and try again, or send us
                    a message to coderedsupport@eccouncil.org to renew your license for another 6 months (.1)')
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
