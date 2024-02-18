<?php

namespace App\Domains\Course\Features\Api\V1\User;

use App\Domains\Course\Events\Course\AssessmentFailed;
use App\Domains\Course\Events\Course\AssessmentPassed;
use App\Domains\Course\Jobs\Api\V1\EndFinalAssessmentJob;
use App\Domains\Course\Jobs\Api\V1\GetFinalAssessmentTimerJob;
use App\Domains\Course\Jobs\Api\V1\User\GenerateCertificateJob;
use App\Domains\Course\Models\CompletedCourses;
use App\Domains\Course\Repositories\AssessmentsAnswerRepository;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Domains\Course\Enum\CourseType;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Course\Jobs\Api\V1\User\AddToWatchHistoryJob;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\Course\Jobs\Api\V1\User\MarkLessonAsWatchedJob;
use App\Domains\Course\Models\Lesson;
use App\Domains\Course\Repositories\AssessmentsRepositoryInterface;
use App\Domains\Course\Http\Resources\Api\V1\CompletedCourseResource;

class SubmitAssessmentOneAnswerFeature extends Feature
{

    /**
     * Create a new feature instance
     */
    public function __construct()
    {

    }

    public function handle(Request $request, AssessmentsAnswerRepository $assessments_answer_repository, CourseRepositoryInterface $course_repository)
    {

        $user = $request->user('api');
        $course = $course_repository->find($request->course_id);

        if ($course->course_type == CourseType::MICRODEGREE) {
            $max_time = config('course.services.final_assessment.time');
            $final_assessment_timer = $this->run(GetFinalAssessmentTimerJob::class, ['course_id' => $course->id, 'user_id' => $user->id]);
            if ($final_assessment_timer > $max_time) {
                return $this->run(RespondWithJsonErrorJob::class, [
                    'errors' => [
                        'name' => 'message',
                        'message' => 'you have exceed time limit for exam',
                    ]
                ]);
            }
        }
        if (!has_access_course($course)) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'name' => 'message',
                    'message' => 'You must have active subscription',
                    'status' => 1001

                ]
            ]);
        }

        if ($user->all_course_enrollments()->where('course_id', $request->course_id)->count() == 0) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    "name" => "message",
                    'message' => trans('You are not enrolled in this course'),
                    'status' => 1002

                ]
            ]);
        }


        $course_id = $request->course_id;
        $answer = $request->answer;
        $assessments_answer_repository->updateAnswer($course_id, $user->id, $answer['question_id'], $answer['answer_id']);


        return $this->run(RespondWithJsonJob::class, [
            "content" => 'Answer Has been submitted'
        ]);
    }
}
