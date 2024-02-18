<?php

namespace App\Domains\Course\Features\Api\V2\User;

use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Services\CyberQ\CyberQService;
use App\Domains\Course\Services\ILab\ILabService;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Domains\Course\Enum\CourseType;
use App\Foundation\Traits\Authenticated;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Course\Repositories\LessonRepositoryInterface;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\Course\Services\VitalSource\VitalSourceService;
use Log;

class GetVitalSourceSessionFeature extends Feature
{

    use Authenticated;

    public function handle(Request $request, LessonRepositoryInterface $lesson_repository, CourseRepositoryInterface $course_repository)
    {
        $user = $request->user('api');

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
        try {
            $vital_source_service = new VitalSourceService();
            $book_id = $lesson->book_id;
            $page_number = $lesson->page_number;
            $access_token =  $vital_source_service->verifyCredentials($request->user('api'));
            $has_access = $lesson_repository->checkBookAccess($user->id, $book_id);

            if(is_array($access_token)){
                return $this->run(RespondWithJsonErrorJob::class, [
                    'errors' => [
                        "name"    => "message",
                        'message' => $access_token['message']
                    ]
                ]);
            }
            if (!$has_access) {
                $user_access = $vital_source_service->giveUserAccessToBook($access_token, $book_id);
                if ($user_access) {
                    $has_access = $lesson_repository->setBookAccess($user->id, $book_id, $user_access);
                }
            }
            // dd($access_token);
            if ($has_access) {
                $book_url = $vital_source_service->redirectUrl($access_token, $book_id);
                $book_with_page_url = $vital_source_service->redirectUrlWithPageNumber($access_token, $book_id, $page_number);
            } else {
                return $this->run(RespondWithJsonErrorJob::class, [
                    'errors' => [
                        "name"    => "message",
                        'message' => "There's an error please try again later."
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
                'url'           => $book_url,
                'url_with_page' => $book_with_page_url,
            ]
        ]);
    }
}
