<?php

namespace App\Domains\Course\Models;

use App\Domains\UserActivity\Traits\Loggable;
use INTCore\OneARTFoundation\Model;

/**
 * @property mixed answers
 */
class LessonMsq extends Model
{
    use Loggable;

    protected $fillable = ['lesson_id', 'question', 'answers', 'activation', 'description', 'related_lesson_id'];
    protected $casts = [
        'answers' => 'json'
    ];
    protected $attributes = [
        'activation' => 1
    ];

    protected $appends = ['answers_array'];

    public function getAnswersArrayAttribute()
    {
        if(is_string($this->answers)) {
            $data = json_decode($this->answers, true);
        }else {
            $data = $this->answers;
        }
        return $data;
    }

    public function scopeActive($query)
    {
        return $query->where('activation', 1);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function relatedLesson()
    {
        return $this->belongsTo(Lesson::class, 'related_lesson_id', 'id');
    }

}
