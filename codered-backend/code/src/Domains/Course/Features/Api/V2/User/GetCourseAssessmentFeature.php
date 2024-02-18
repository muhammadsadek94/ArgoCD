<?php

namespace App\Domains\Course\Features\Api\V2\User;

use App\Domains\Course\Http\Requests\Api\StartAssessmentRequest;
use App\Domains\Course\Http\Resources\Api\V1\CourseBasicInfoResource;
use App\Domains\Course\Http\Resources\Api\V1\CourseExamInfoResource;
use App\Domains\Course\Jobs\Api\V1\GetFinalAssessmentTimerJob;
use App\Domains\Course\Jobs\Api\V1\StartFinalAssessmentJob;
use App\Domains\Course\Models\ProctorUsers;
use App\Domains\Course\Repositories\AssessmentsAnswerRepository;
use INTCore\OneARTFoundation\Feature;
use App\Domains\Course\Enum\CourseType;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\Course\Http\Resources\Api\V1\AssessmentsResource;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Repositories\AssessmentsRepositoryInterface;
use Hash;

class GetCourseAssessmentFeature extends Feature
{
    protected $courseInfo;

    public function handle(StartAssessmentRequest $request, AssessmentsRepositoryInterface $assessments_repository,AssessmentsAnswerRepository $assessments_answer_repository, CourseRepositoryInterface $course_repository)
    {

        $user = $request->user('api');
        // $course = $course_repository->find($request->course_id);
        $this->courseInfo = $course = Course::where(['id' => $request->course_id])->orWhere(['slug_url' => $request->course_id])->firstOrFail();
        if (!has_access_course($course)) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'name' => 'message',
                    'message' => 'You must have active subscription',
                    'status' => 1001

                ]
            ]);
        }
        if ($course->course_type == CourseType::MICRODEGREE) {
            $max_time = config('course.services.final_assessment.time');

            $final_assessment_timer = $this->run(GetFinalAssessmentTimerJob::class, ['course_id' => $course->id, 'user_id' => $user->id]);

            if ($final_assessment_timer > intval($max_time)) {
                return $this->run(RespondWithJsonErrorJob::class, [
                    'errors' => [
                        'name' => 'message',
                        'message' => 'you have exceed time limit for exam',
                    ]
                ]);
            }
        }
        if ($user->all_course_enrollments()->where('course_id', $course->id)->count() == 0) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    "name" => "message",
                    'message' => trans('You are not enrolled in this course'),
                    'status' => 1002
                ]
            ]);
        }
        if ($course->course_type == CourseType::MICRODEGREE) {
          if (!$this->checkProctorCredentials($request)) {
              return $this->run(RespondWithJsonErrorJob::class, [
                  'errors' => [
                      "name" => "message",
                      'message' => trans('Invalid credentials'),
                  ]
              ]);
          }

            $finalAssessment = $this->run(StartFinalAssessmentJob::class, [
                "user_id" => $user->id,
                "course_id" => $course->id
            ]);
        }

        $assessments = $assessments_answer_repository->getCourseAssessmentsDataBase($course->id , $user->id);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'course' => new CourseExamInfoResource($course),
                'assessments' => AssessmentsResource::collection($assessments),
                'final_assessment_info' => isset($finalAssessment) ? $finalAssessment : null
            ]
        ]);
    }

    private function checkProctorCredentials($request)
    {
        $proctor_user = ProctorUsers::active()->where('username', $request->username)->first();
        if (empty($proctor_user)) return false;

        if (!in_array($this->courseInfo->id, $proctor_user->course_ids)) return false;

        return Hash::check($request->password, $proctor_user->password);
    }
}
