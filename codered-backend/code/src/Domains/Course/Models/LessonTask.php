<?php

namespace App\Domains\Course\Models;

use INTCore\OneARTFoundation\Model;

class LessonTask extends Model
{
    protected $fillable = ['title','description', 'lesson_id', 'course_id', 'chapter_id', 'activation', 'sort'];

    protected $attributes = [
      'activation' => 1
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function scopeActive($query)
    {
        return $query->where('activation', 1);
    }
}
