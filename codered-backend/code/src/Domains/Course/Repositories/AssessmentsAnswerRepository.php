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

class AssessmentsAnswerRepository extends Repository implements AssessmentsAnswerRepositoryInterface
{

    public function __construct(FinalAssessmentAnswers $model)
    {
        parent::__construct($model);
    }


    public function updateAnswer($course_id, $user_id, $assessment_id, $answer_id)

    {
        $this->getModel()->newQuery()
            ->where('course_id', $course_id)
            ->where('user_id', $user_id)
            ->where('assessment_id', $assessment_id)
            ->update(['assessment_answer_id' => $answer_id]);
    }


    public function getCourseAssessmentsDataBase($course_id, $user_id): Collection
    {
        $assessements = $this->getModel()->newQuery()->where('course_id', $course_id)->where('user_id', $user_id)->get();
        if (count($assessements)) { // check if user has been assigned to spacific assessment
            return $assessements;
        } else {// if not then assign at most 50 random question form the database
            $this->generateAssessmnet($course_id, $user_id);
            // here to get the assessment back to user
            return $this->getModel()->newQuery()->where('course_id', $course_id)->where('user_id', $user_id)->get();

        }
    }

    private function generateAssessmnet($course_id, $user_id)
    {

        $list_assessment_ids = CourseAssessment::where('course_id', $course_id)->inRandomOrder()->limit(125)->get()->pluck('id');
        foreach ($list_assessment_ids as $assessment_id) {
            $this->getModel()::create([
                'user_id' => $user_id,
                'course_id' => $course_id,
                'assessment_id' => $assessment_id
            ]);
        }
    }
    public function getUserCurrentCertificate($user_id, $course_id): ?CompletedCourses
    {
        return CompletedCourses::where([
            'user_id'   => $user_id,
            'course_id' => $course_id
        ])->first();
    }

    /**
     * Answers Evaluations
     *
     * @param                  $course_id
     * @param array|collection $answers
     * @param                  $user_id
     * @return CompletedCourses|null
     */
    public function generateResult($course_id,  $user_id)
    {
        $result = $this->getUserCurrentCertificate($user_id, $course_id);
        if ($result) return $result;

        $score = 0;
        $answers = $this->getModel()->newQuery()->where('course_id', $course_id)->where('user_id', $user_id)->get();
        foreach ($answers as $answer) {
            if ($answer->assessment->correct_answer_id == $answer['assessment_answer_id']) $score++;
        }
        $percentage = 0;
        if (count($answers) > 0) {
            $percentage = $score / count($answers) * 100;
        }

        if ($percentage < 80) {
            return
                [
                    'result' => -1,
                    'percentage' => $percentage,
                    'num_of_correct_answers' => $score
                ];
        };

        $result = $this->createCertificateFile($user_id, $course_id, $percentage);
        return $result->refresh();
    }

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
}

