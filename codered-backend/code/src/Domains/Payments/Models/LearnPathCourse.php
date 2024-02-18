<?php

namespace App\Domains\Payments\Models;

use App\Domains\Course\Models\Course;
use INTCore\OneARTFoundation\Model;

class LearnPathCourse extends Model
{

    protected $fillable = ['course_id', 'learn_path_id', 'weight', 'sort'];


    public function learnPath()
    {
        return $this->belongsTo(LearnPathInfo::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

}



