<?php

namespace App\Domains\Course\Models;

use App\Domains\UserActivity\Traits\Loggable;
use INTCore\OneARTFoundation\Model;

class CourseAssessment extends Model
{
    use Loggable;

    protected $fillable = ['course_id', 'question', 'correct_answer_id', 'related_lesson_id'];

    public function answers()
    {
        return $this->hasMany(CourseAssessmentAnswers::class, 'course_assessments_id', 'id');
    }

    public function correct_answer()
    {
        return $this->hasOne(CourseAssessmentAnswers::class, 'id', 'correct_answer_id');
    }

    public function relatedLesson()
    {
        return $this->belongsTo(Lesson::class, 'related_lesson_id', 'id');
    }

}
