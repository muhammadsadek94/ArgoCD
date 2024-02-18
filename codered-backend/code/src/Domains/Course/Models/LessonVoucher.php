<?php

namespace App\Domains\Course\Models;

use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Model;

class LessonVoucher extends Model
{
    protected $fillable = ['voucher', 'user_id', 'lesson_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id', 'id');
    }
    public function scopeActive($query)
    {
        return $query->where('activation', 1);
    }

}
