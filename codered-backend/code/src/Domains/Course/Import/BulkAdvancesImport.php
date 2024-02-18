<?php

namespace App\Domains\Course\Import;

use App\Domains\Course\Models\Course;
use App\Domains\User\Models\User;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BulkAdvancesImport implements ToArray, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function array(array $courses)
    {
        foreach ($courses as $courseData) {
            if (array_key_exists('course_id', $courseData) && array_key_exists('advances', $courseData)) {
                $course = Course::find($courseData['course_id']);
                if ($course) {
                    $course->update(['advances' => $courseData['advances']]);
                }
            }
        }
    }
}
