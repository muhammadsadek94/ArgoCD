<?php

namespace App\Domains\Course\Models;

use INTCore\OneARTFoundation\Model;

class CourseAssessmentAnswers extends Model
{
    protected $table = 'course_assessments_answers';

    protected $fillable = ['course_assessments_id', 'answer', 'is_correct'];
}
