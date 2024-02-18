<?php

namespace App\Domains\Course\Models;

use INTCore\OneARTFoundation\Model;

class FinalAssessmentAnswers extends Model
{
    protected $fillable = ['user_id', 'course_id', 'assessment_id', 'assessment_answer_id', 'activation'];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function course()
    {
        return $this->hasOne(Course::class,'id','course_id');
    }
     public function assessment()
    {
        return $this->hasOne(CourseAssessment::class,'id','assessment_id');
    }

    public function assessment_answer()
    {
        return $this->hasOne(CourseAssessmentAnswers::class,'id','assessment_answer_id');
    }

}
