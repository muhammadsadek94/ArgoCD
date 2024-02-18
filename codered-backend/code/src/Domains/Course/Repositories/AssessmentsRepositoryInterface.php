<?php

namespace App\Domains\Course\Repositories;

use App\Domains\Course\Models\CompletedCourses;
use App\Domains\Course\Models\CourseAssessment;
use App\Foundation\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface AssessmentsRepositoryInterface extends RepositoryInterface
{

    /**
     * @param $course_id
     * @return Collection
     */
    public function getCourseAssessments($course_id): Collection;
    public function getCourseAssessmentsDataBase($course_id, $user_id): Collection;

    /**
     * Answers Evaluations
     *
     * @param $course_id
     * @param array|collection $answers
     * @param $user_id
     * @return CompletedCourses
     */
    public function generateResult($course_id, array $answers, $user_id);

    /**
     * @param $user_id
     * @param $course_id
     * @return CompletedCourses|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getUserCurrentCertificate($user_id, $course_id): ?CompletedCourses;

    /**
     * @param     $user_id
     * @param     $course_id
     * @param int $percentage
     * @param int $certificate_number
     * @return CompletedCourses|\Illuminate\Database\Eloquent\Model
     */
    public function createCertificateFile($user_id, $course_id, int $percentage, int $certificate_number);
}
