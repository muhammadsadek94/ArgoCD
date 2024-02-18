<?php

namespace App\Domains\Course\Repositories;

use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Jobs\Api\V1\User\GenerateCertificateJob;
use App\Domains\Course\Models\CompletedCourses;
use App\Domains\Course\Models\CourseAssessment;
use App\Domains\Course\Models\CourseAssessmentAnswers;
use App\Domains\Course\Models\FinalAssessmentAnswers;
use App\Foundation\Repositories\Repository;
use Illuminate\Database\Eloquent\Collection;
use DB;
use Log;

class AssessmentsRepository extends Repository implements AssessmentsRepositoryInterface
{

    public function __construct(CourseAssessment $model)
    {
        parent::__construct($model);
    }

    /**
     * @param $course_id
     * @return \Illuminate\Database\Eloquent\Builder[]|Collection
     */
    public function getCourseAssessments($course_id): Collection
    {
        return $this->getModel()->newQuery()->where('course_id', $course_id)->with('answers')->get();
    }


    public function getCourseAssessmentsDataBase($course_id, $user_id): Collection
    {
        $list_assessment_ids = $this->getListOfAssessmentId($course_id, $user_id);
        return $this->getModel()->newQuery()->where('course_id', $course_id)->whereIn('id', $list_assessment_ids)->with('answers')->get();
    }

    private function getListOfAssessmentId($course_id, $user_id)
    {
        $list_assessment_ids = FinalAssessmentAnswers::where('course_id', $course_id)->where('user_id', $user_id)->get()->pluck('id');
        if (count($list_assessment_ids)) {
            return $list_assessment_ids;
        } else {
            $list_assessment_ids = $this->getModel()->newQuery()->where('course_id', $course_id)->inRandomOrder()->limit(50)->get()->pluck('id');
            foreach ($list_assessment_ids as $assessment_id) {
                FinalAssessmentAnswers::create([
                    'user_id' => $user_id,
                    'course_id' => $course_id,
                    'assessment_id' => $assessment_id
                ]);
            }
            return $list_assessment_ids;
        }
    }
    /**
     * Answers Evaluations
     *
     * @param                  $course_id
     * @param array|collection $answers
     * @param                  $user_id
     * @return CompletedCourses|null
     */
    public function generateResult($course_id, array $answers, $user_id)
    {
        $result = $this->getUserCurrentCertificate($user_id, $course_id);
        if ($result) return $result;

        $score = 0;
        foreach ($answers as $answer) {
            $question = $this->getModel()->newQuery()->findOrFail($answer['question_id']);
            if ($question->correct_answer_id == $answer['answer_id']) $score++;
        }
        $percentage = 0;
        if (count($answers) > 0) {
            $percentage = $score / count($answers) * 100;
        }

        if ($percentage < 80) {
            return
                [
                    'result'                 => -1,
                    'percentage'             => $percentage,
                    'num_of_correct_answers' => $score
                ];
        };

        $result = $this->createCertificateFile($user_id, $course_id, $percentage);

        return $result->refresh();
    }

    /**
     * @param $user_id
     * @param $course_id
     * @return CompletedCourses|null
     */
    public function getUserCurrentCertificate($user_id, $course_id): ?CompletedCourses
    {
        return CompletedCourses::where([
            'user_id'   => $user_id,
            'course_id' => $course_id
        ])->first();
    }

    private function generateCertificateNumber()
    {

        try {
            $certificate = DB::table('completed_courses')->select(DB::raw('MAX(CAST(certificate_number AS UNSIGNED)) as max'))->get()[0];
            $last_certificate = $certificate->max;
        } catch (\Exception $exception) {
            Log::error("can not generate certificate number !");
        }

        if ($last_certificate)
            return (int) ($last_certificate) + 1;
        return rand(0, 1000);
    }

    /**
     * @param     $user_id
     * @param     $course_id
     * @param int $percentage
     * @param int $certificate_number
     * @return CompletedCourses|\Illuminate\Database\Eloquent\Model
     */
    public function createCertificateFile($user_id, $course_id, int $percentage, int $certificate_number = 0)
    {
        $result = $this->getUserCurrentCertificate($user_id, $course_id);
        if ($result) return $result;

        if ($certificate_number == 0) {
            $certificate_number = $this->generateCertificateNumber();
        }

        //$certificate_number = $this->generateCertificateNumber();
        $result = CompletedCourses::create([
            'user_id'            => $user_id,
            'course_id'          => $course_id,
            'certificate_id'     => null, // auto generated with job
            'degree'             => $percentage,
            'certificate_number' => $certificate_number
        ]);

        dispatch_now(new GenerateCertificateJob($result->user, $result));
        return $result;
    }
}
