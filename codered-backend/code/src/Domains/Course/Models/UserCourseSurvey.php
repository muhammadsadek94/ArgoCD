<?php

namespace App\Domains\Course\Models;

use App\Domains\UserActivity\Traits\Loggable;
use INTCore\OneARTFoundation\Model;

class UserCourseSurvey extends Model
{
    use Loggable;

    protected $table = 'user_course_survey';

    protected $fillable = ['course_id', 'chapter_id', 'lesson_id', 'user_id', 'survey'];


    protected $casts = [
        'survey' => 'array',
    ];

}
