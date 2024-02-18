<?php

namespace App\Domains\Course\Models;

class WatchedLesson extends \Eloquent
{
    protected $fillable = ['lesson_id', 'user_id', 'subscription_type', 'course_id', 'chapter_id'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

}
