<?php

namespace App\Domains\Course\Models;

use INTCore\OneARTFoundation\Model;

class LessonUserNote extends Model
{
    protected $fillable = ['course_id', 'chapter_id', 'lesson_id', 'user_id', 'note', 'title'];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

}
