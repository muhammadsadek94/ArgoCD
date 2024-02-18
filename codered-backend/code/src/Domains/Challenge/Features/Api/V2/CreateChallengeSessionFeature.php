<?php

namespace App\Domains\Challenge\Features\Api\V2;

use App\Domains\Challenge\Models\Challenge;
use App\Domains\Course\Models\CourseEnrollment;
use App\Domains\Course\Services\CyberQ\CyberQService;
use App\Domains\User\Enum\UserActivation;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Traits\Authenticated;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;

class CreateChallengeSessionFeature extends Feature
{

    use Authenticated;

    public function handle(Request $request)
    {
        $user = $request->user('api');

        if ($user->activation == UserActivation::SUSPEND) {
            return response('Unauthorized.', 401);
        }

        $ip_address = $request->get('ip');

        $course = $user->active_certifications()->whereHas('challenge', function ($query) use ($request) {
            $query->where('id', $request->challenge_id);
        })->first();

        $challenge = Challenge::find($request->challenge_id);

        if (!$course) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    "name"    => "message",
                    'message' => trans('You are not enrolled in this course'),
                    'status' => 1002

                ]
            ]);
        }

        $course_enrollment = CourseEnrollment::where('user_id', $user->id)->where('course_id', $course->id)->latest()->first();

        try {

            $cyberq = new CyberQService();
            $response = $cyberq->createCyberQ($user, $challenge->competition_id, $ip_address, $course_enrollment, $course);

            $session_url = $response->data['Url'];
        } catch (\Exception $e) {
            return response()->json([
                'message'   => $e->getMessage(),
            ], 500);
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    "name"    => "message",
                    'message' => trans('This lab is no longer available because you have another lab open or
                     your lab access has expired. Please close any open labs and try again, or send us
                    a message to coderedsupport@eccouncil.org to renew your license for another 6 months.(2)')
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
