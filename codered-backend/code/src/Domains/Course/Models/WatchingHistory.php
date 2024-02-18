<?php

namespace App\Domains\Course\Models;


class WatchingHistory extends \Eloquent
{
    protected $fillable = ['lesson_id', 'user_id', 'course_id'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
