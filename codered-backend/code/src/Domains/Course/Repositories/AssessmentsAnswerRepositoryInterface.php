<?php

namespace App\Domains\Course\Repositories;

use App\Domains\Course\Models\CompletedCourses;
use App\Domains\Course\Models\CourseAssessment;
use App\Foundation\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface AssessmentsAnswerRepositoryInterface extends RepositoryInterface
{

    /**
     * Add answer
     *
     * @param $course_id
     * @param $user_id
     * @param $assessment_id
     * @param $answer_id
     */

    public function updateAnswer($course_id, $user_id , $assessment_id , $answer_id);

    /**
     * Answers Evaluations
     *
     * @param $course_id
     * @param array|collection $answers
     * @param $user_id
     * @return CompletedCourses
     */
    public function generateResult($course_id, $user_id);



}
