<?php

namespace App\Domains\Course\Models;

use INTCore\OneARTFoundation\Model;

class LessonFaq extends Model
{
    protected $fillable = ['lesson_id', 'question', 'answer', 'activation'];

    protected $attributes = [
        'activation' => 1
    ];

    public function scopeActive($query)
    {
        return $query->where('activation', 1);
    }
}
