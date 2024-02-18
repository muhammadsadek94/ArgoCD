<?php

namespace App\Domains\Payments\Models;

use App\Domains\Course\Models\Lookups\CourseCategory;
use INTCore\OneARTFoundation\Model;

class LearnPathCategory extends Model
{

    protected $fillable = ['course_category_id', 'learn_path_id'];


    public function learnPath()
    {
        return $this->belongsTo(LearnPathInfo::class);
    }

    public function category()
    {
        return $this->belongsTo(CourseCategory::class, 'course_category_id', 'id');
    }

}



